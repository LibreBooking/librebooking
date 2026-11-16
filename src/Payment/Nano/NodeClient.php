<?php

declare(strict_types=1);

namespace LibreBooking\Payment\Nano;

use InvalidArgumentException;

/**
 * Reads one block from a Nano node the operator configures (`block_info`,
 * read only). There is no key and no seed here: nothing is signed or sent.
 *
 * The lookup is bounded (connect and total timeouts, response size), and a
 * plain-http URL is accepted only for a node on the same machine, so a block
 * reply cannot be altered on the way from a remote node.
 */
final class NodeClient
{
    private const TIMEOUT_SECONDS = 10;
    private const MAX_RESPONSE_BYTES = 65536;
    private const LOOPBACK_HOSTS = ['localhost', '127.0.0.1', '::1', '[::1]'];

    public function __construct(private readonly string $nodeUrl)
    {
        self::assertUrlAllowed($nodeUrl);
    }

    public static function isUrlAllowed(string $url): bool
    {
        $parts = parse_url(trim($url));
        if (!is_array($parts) || !isset($parts['scheme'], $parts['host'])) {
            return false;
        }

        $scheme = strtolower($parts['scheme']);
        if ($scheme === 'https') {
            return true;
        }

        return $scheme === 'http' && in_array(strtolower($parts['host']), self::LOOPBACK_HOSTS, true);
    }

    public static function assertUrlAllowed(string $url): void
    {
        if (!self::isUrlAllowed($url)) {
            throw new InvalidArgumentException('A Nano node URL must be https, or http on this machine only.');
        }
    }

    /**
     * @return array<string, mixed> the node's `block_info` reply (json_block=true)
     *
     * @throws NanoException when the node cannot be read
     */
    public function blockInfo(string $blockHash): array
    {
        $body = json_encode(['action' => 'block_info', 'json_block' => 'true', 'hash' => $blockHash]);

        $received = '';
        $handle = curl_init($this->nodeUrl);
        curl_setopt_array($handle, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_CONNECTTIMEOUT => self::TIMEOUT_SECONDS,
            CURLOPT_TIMEOUT => self::TIMEOUT_SECONDS,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_WRITEFUNCTION => static function ($curl, string $chunk) use (&$received): int {
                if (strlen($received) + strlen($chunk) > self::MAX_RESPONSE_BYTES) {
                    return 0; // aborts the transfer
                }
                $received .= $chunk;

                return strlen($chunk);
            },
        ]);

        $ok = curl_exec($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        if ($ok === false || $status !== 200) {
            throw new NanoException('The Nano node could not be read.');
        }

        $reply = json_decode($received, true);
        if (!is_array($reply)) {
            throw new NanoException('The Nano node returned an unreadable reply.');
        }

        return $reply;
    }
}
