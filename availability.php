<?php

declare(strict_types=1);

/**
 * availability.php — public, read-only room-busyness feed for the Podziemie
 * landing page. Deployed next to LibreBooking (e.g. /rezerwacje/availability.php)
 * and reads its database directly (SELECT only). Exposes aggregated numbers per
 * hour per room per day — never who booked what, and no coach data.
 *
 * Response contract v2 (consumed by lib/availability.ts on the site):
 * {
 *   "version": 2,
 *   "generatedAt": "...",
 *   "timezone": "Europe/Warsaw",
 *   "today": "YYYY-MM-DD",
 *   "days": [{
 *     "date": "YYYY-MM-DD",
 *     "weekday": "mon"|...|"sun",
 *     "isToday": bool,
 *     "isPast": bool,
 *     "open": {"openMin": 360, "closeMin": 1320} | null,
 *     "rooms": [{
 *       "id": "sala-maszyn",
 *       "resourceId": 1,
 *       "capacity": 2,
 *       "hours": [{"h": 6, "bookedMin": 30, "capMin": 120, "ratio": 0.25, "level": "low"}, ...]
 *     }]
 *   }]
 * }
 * Hour buckets cover the widest opening window observed across the week
 * (typically 06:00-22:00 => h=6..21). Days closed on that weekday report
 * open=null and hours=[].
 */

const CONTRACT_VERSION = 2;
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
    $utc = new DateTimeZone('UTC');
    $now = new DateTimeImmutable('now', $tz);
    $today = $now->setTime(0, 0, 0);

    // Current calendar week Mon-Sun in Warsaw TZ. (ISO-8601: 1=Mon..7=Sun.)
    $monday = $today->modify('-' . ((int) $today->format('N') - 1) . ' days');
    $weekEnd = $monday->modify('+7 days');

    $weekdayKeys = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    // Union of opening hours across the week determines the hour axis.
    // Rooms are listed once (config['rooms']) and the shape stays constant
    // across days for consistent client rendering.
    [$axisFromHour, $axisToHour] = axisHours($config['openingHours'] ?? []);

    $roomsMeta = [];
    foreach ($config['rooms'] as $resourceId => $room) {
        $roomsMeta[(int) $resourceId] = [
            'id' => (string) $room['id'],
            'name' => (string) $room['name'],
            'capacity' => max(1, (int) ($room['capacity'] ?? 1)),
        ];
    }

    // Single DB round-trip: all reservations touching the week for our rooms.
    $reservations = queryReservations(
        $config['db'],
        array_keys($roomsMeta),
        $monday->setTimezone($utc)->format('Y-m-d H:i:s'),
        $weekEnd->setTimezone($utc)->format('Y-m-d H:i:s'),
    );

    $days = [];
    for ($i = 0; $i < 7; $i++) {
        $dayStart = $monday->modify("+$i days");
        $weekdayKey = $weekdayKeys[$i];
        $window = $config['openingHours'][$weekdayKey] ?? null;

        $open = null;
        $openMin = null;
        $closeMin = null;
        if (is_array($window) && count($window) === 2) {
            $openMin = hhmmToMin($window[0]);
            $closeMin = hhmmToMin($window[1]);
            if ($closeMin > $openMin) {
                $open = ['openMin' => $openMin, 'closeMin' => $closeMin];
            } else {
                $openMin = null;
                $closeMin = null;
            }
        }

        $daysRooms = [];
        foreach ($roomsMeta as $resourceId => $meta) {
            $capMin = 60 * $meta['capacity'];
            $hours = [];
            for ($h = $axisFromHour; $h < $axisToHour; $h++) {
                $inOpenWindow = $open !== null && $h * 60 >= $openMin && ($h + 1) * 60 <= $closeMin;
                $bookedMin = 0;
                if ($inOpenWindow) {
                    $hourStart = $dayStart->setTime($h, 0, 0);
                    $hourEnd = $dayStart->setTime($h + 1, 0, 0);
                    $bookedMin = min($capMin, sumOverlapMinutes(
                        $reservations,
                        $resourceId,
                        $hourStart->setTimezone($utc),
                        $hourEnd->setTimezone($utc),
                    ));
                }
                $ratio = $capMin > 0 && $bookedMin > 0 ? $bookedMin / $capMin : 0.0;
                $hours[] = [
                    'h' => $h,
                    'bookedMin' => $bookedMin,
                    'capMin' => $inOpenWindow ? $capMin : 0,
                    'ratio' => round($ratio, 3),
                    'level' => $ratio < 1 / 3 ? 'low' : ($ratio < 2 / 3 ? 'medium' : 'high'),
                ];
            }

            $daysRooms[] = [
                'id' => $meta['id'],
                'resourceId' => (int) $resourceId,
                'capacity' => $meta['capacity'],
                'hours' => $hours,
            ];
        }

        $days[] = [
            'date' => $dayStart->format('Y-m-d'),
            'weekday' => $weekdayKey,
            'isToday' => $dayStart->format('Y-m-d') === $today->format('Y-m-d'),
            'isPast' => $dayStart < $today,
            'open' => $open,
            'rooms' => $daysRooms,
        ];
    }

    return [
        'version' => CONTRACT_VERSION,
        'generatedAt' => $now->format(DateTimeInterface::ATOM),
        'timezone' => TIMEZONE,
        'today' => $today->format('Y-m-d'),
        'axis' => ['fromHour' => $axisFromHour, 'toHour' => $axisToHour],
        'days' => $days,
    ];
}

/**
 * Widest opening window observed across the week, floor to hour.
 * Defaults to 06:00-22:00 if nothing configured.
 */
function axisHours(array $openingHours): array
{
    $minOpen = null;
    $maxClose = null;
    foreach ($openingHours as $win) {
        if (!is_array($win) || count($win) !== 2) {
            continue;
        }
        $o = intdiv(hhmmToMin($win[0]), 60);
        $c = (int) ceil(hhmmToMin($win[1]) / 60);
        $minOpen = $minOpen === null ? $o : min($minOpen, $o);
        $maxClose = $maxClose === null ? $c : max($maxClose, $c);
    }
    return [$minOpen ?? 6, $maxClose ?? 22];
}

/**
 * Fetch all reservation instances for the given resources overlapping the
 * requested UTC window. Deleted (status_id 2) excluded. Pending (3) still
 * occupies the room. reservation_instances already expands recurring series
 * so no client-side recurrence math is needed.
 */
function queryReservations(array $db, array $resourceIds, string $winStartUtc, string $winEndUtc): array
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
    $sql = "SELECT rr.resource_id, ri.start_date, ri.end_date
            FROM reservation_instances ri
            INNER JOIN reservation_series rs ON rs.series_id = ri.series_id
            INNER JOIN reservation_resources rr ON rr.series_id = rs.series_id
            WHERE rs.status_id <> 2
              AND ri.start_date < ?
              AND ri.end_date > ?
              AND rr.resource_id IN ($in)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_merge([$winEndUtc, $winStartUtc], $resourceIds));

    $utc = new DateTimeZone('UTC');
    $byResource = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $rid = (int) $row['resource_id'];
        $byResource[$rid] ??= [];
        $byResource[$rid][] = [
            'start' => new DateTimeImmutable($row['start_date'], $utc),
            'end' => new DateTimeImmutable($row['end_date'], $utc),
        ];
    }
    return $byResource;
}

function sumOverlapMinutes(array $reservations, int $resourceId, DateTimeImmutable $winStart, DateTimeImmutable $winEnd): int
{
    $total = 0;
    foreach ($reservations[$resourceId] ?? [] as $r) {
        $start = $r['start'] > $winStart ? $r['start'] : $winStart;
        $end = $r['end'] < $winEnd ? $r['end'] : $winEnd;
        if ($end > $start) {
            $total += (int) (($end->getTimestamp() - $start->getTimestamp()) / 60);
        }
    }
    return $total;
}

function hhmmToMin(string $hhmm): int
{
    [$h, $m] = array_map('intval', explode(':', $hhmm));
    return $h * 60 + $m;
}
