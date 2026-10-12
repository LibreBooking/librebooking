<?php

declare(strict_types=1);

/**
 * availability.php — public, read-only room-busyness feed for the Podziemie
 * landing page. Deployed next to LibreBooking (e.g. /rezerwacje/availability.php)
 * and reads its database directly (SELECT only). Exposes aggregated numbers per
 * room — never who booked what, and no coach data.
 *
 * Response contract v1 (consumed by lib/availability.ts on the site):
 * {
 *   "version": 1, "generatedAt": "…", "timezone": "Europe/Warsaw",
 *   "date": "YYYY-MM-DD", "open": {"openMin": 360, "closeMin": 1320} | null,
 *   "rooms": [{"id","name","resourceId","bookedMinutes","openMinutes","ratio","level"}]
 * }
 * "open" is null on closed days; rooms then report openMinutes 0 and level "low".
 */

const CONTRACT_VERSION = 1;
const TIMEZONE = 'Europe/Warsaw';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method !== 'GET' && $method !== 'HEAD') {
    http_response_code(405);
    header('Allow: GET, HEAD');
    echo json_encode(['version' => CONTRACT_VERSION, 'error' => 'method_not_allowed']);
    exit;
}

try {
    $config = require __DIR__ . '/availability.config.php';
    $cacheTtl = (int) ($config['cacheTtl'] ?? 300);
    header('Cache-Control: public, max-age=' . $cacheTtl);

    $cacheFile = __DIR__ . '/availability.cache.json';
    if ($cacheTtl > 0 && is_file($cacheFile) && (time() - (int) filemtime($cacheFile)) < $cacheTtl) {
        $cached = file_get_contents($cacheFile);
        if ($cached !== false && $cached !== '') {
            if ($method !== 'HEAD') {
                echo $cached;
            }
            exit;
        }
    }

    $body = json_encode(buildFeed($config), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    if ($cacheTtl > 0) {
        // Atomic best-effort write; caching is skipped silently on read-only dirs.
        $tmp = @tempnam(__DIR__, 'avail');
        if ($tmp !== false && @file_put_contents($tmp, $body) !== false && @rename($tmp, $cacheFile)) {
            // cached
        } elseif ($tmp !== false) {
            @unlink($tmp);
        }
    }

    if ($method !== 'HEAD') {
        echo $body;
    }
} catch (Throwable $e) {
    // Never leak error messages or credentials to the public response.
    http_response_code(503);
    echo json_encode(['version' => CONTRACT_VERSION, 'error' => 'unavailable']);
}

function buildFeed(array $config): array
{
    $tz = new DateTimeZone(TIMEZONE);
    $now = new DateTimeImmutable('now', $tz);
    $weekday = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'][(int) $now->format('N') - 1];
    $window = $config['openingHours'][$weekday] ?? null;

    $rooms = [];
    foreach ($config['rooms'] as $resourceId => $room) {
        $rooms[(int) $resourceId] = [
            'id' => (string) $room['id'],
            'name' => (string) $room['name'],
            'resourceId' => (int) $resourceId,
            'capacity' => max(1, (int) ($room['capacity'] ?? 1)),
            'bookedMinutes' => 0,
            'openMinutes' => 0,
            'ratio' => 0.0,
            'level' => 'low',
        ];
    }

    $open = null;
    if (is_array($window) && count($window) === 2) {
        $openMin = hhmmToMin($window[0]);
        $closeMin = hhmmToMin($window[1]);
        if ($closeMin > $openMin) {
            $open = ['openMin' => $openMin, 'closeMin' => $closeMin];
            $windowMinutes = $closeMin - $openMin;

            // LibreBooking stores reservation datetimes in UTC — convert the
            // Warsaw opening window before comparing.
            $utc = new DateTimeZone('UTC');
            $winStart = $now->setTime(intdiv($openMin, 60), $openMin % 60, 0);
            $winEnd = $now->setTime(intdiv($closeMin, 60), $closeMin % 60, 0);
            $booked = queryBookedMinutes(
                $config['db'],
                array_keys($rooms),
                $winStart->setTimezone($utc)->format('Y-m-d H:i:s'),
                $winEnd->setTimezone($utc)->format('Y-m-d H:i:s'),
            );

            foreach ($rooms as $resourceId => &$room) {
                // Denominator is the total slot-time for this room today: the
                // opening window multiplied by capacity. A capacity-2 room over
                // a 16h day has 32 slot-hours, so one 16h booking = 50% busy.
                $capacityMinutes = $windowMinutes * $room['capacity'];
                $minutes = min($booked[$resourceId] ?? 0, $capacityMinutes);
                $ratio = $capacityMinutes > 0 ? $minutes / $capacityMinutes : 0.0;
                $room['bookedMinutes'] = $minutes;
                $room['openMinutes'] = $capacityMinutes;
                $room['ratio'] = round($ratio, 3);
                $room['level'] = $ratio < 1 / 3 ? 'low' : ($ratio < 2 / 3 ? 'medium' : 'high');
            }
            unset($room);
        }
    }

    return [
        'version' => CONTRACT_VERSION,
        'generatedAt' => $now->format(DateTimeInterface::ATOM),
        'timezone' => TIMEZONE,
        'date' => $now->format('Y-m-d'),
        'open' => $open,
        'rooms' => array_values($rooms),
    ];
}

/**
 * Sum of booked minutes per resource inside [winStart, winEnd) UTC.
 * status_id 2 = Deleted is excluded; Pending (3) still occupies the room.
 * Repeating reservations are pre-expanded into reservation_instances, so no
 * recurrence math is needed. Blackouts are intentionally not counted — they
 * represent closures, not busyness.
 */
function queryBookedMinutes(array $db, array $resourceIds, string $winStartUtc, string $winEndUtc): array
{
    if ($resourceIds === []) {
        return [];
    }
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['name']),
        $db['user'],
        $db['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    );

    $in = implode(',', array_fill(0, count($resourceIds), '?'));
    $sql = "SELECT rr.resource_id,
                   COALESCE(SUM(TIMESTAMPDIFF(MINUTE,
                       GREATEST(ri.start_date, ?),
                       LEAST(ri.end_date, ?))), 0) AS booked_minutes
            FROM reservation_instances ri
            INNER JOIN reservation_series rs ON rs.series_id = ri.series_id
            INNER JOIN reservation_resources rr ON rr.series_id = rs.series_id
            WHERE rs.status_id <> 2
              AND ri.start_date < ?
              AND ri.end_date > ?
              AND rr.resource_id IN ($in)
            GROUP BY rr.resource_id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge([$winStartUtc, $winEndUtc, $winEndUtc, $winStartUtc], $resourceIds));

    $booked = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $booked[(int) $row['resource_id']] = max(0, (int) $row['booked_minutes']);
    }
    return $booked;
}

function hhmmToMin(string $hhmm): int
{
    [$h, $m] = array_map('intval', explode(':', $hhmm));
    return $h * 60 + $m;
}
