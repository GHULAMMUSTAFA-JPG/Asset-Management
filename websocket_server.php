<?php

$host = '0.0.0.0';
$port = 8080;

$server = stream_socket_server(
    "tcp://{$host}:{$port}",
    $errno,
    $errstr
);

if (!$server) {
    die("Failed to start WebSocket server: {$errstr}\n");
}

echo "WebSocket server running on port {$port}\n";

$clients    = [];
$dashboards = [];

$buffers = [];

// keep running forever
while (true) {

    $read   = array_merge([$server], $clients);
    $write  = null;
    $except = null;

    if (stream_select($read, $write, $except, 0, 200000) === false) {
        break;
    }

    if (in_array($server, $read)) {
        $client = stream_socket_accept($server);
        if ($client) {
            $clients[] = $client;
            $buffers[(int)$client] = '';
            echo "New connection\n";
        }
        unset($read[array_search($server, $read)]);
    }

    foreach ($read as $client) {
        $data = fread($client, 65536);

        if (!$data) {
            $key = array_search($client, $clients);
            if ($key !== false) unset($clients[$key]);
            $key = array_search($client, $dashboards);
            if ($key !== false) unset($dashboards[$key]);
            unset($buffers[(int)$client]);
            fclose($client);
            echo "Client disconnected\n";
            continue;
        }

        if (strpos($data, 'Upgrade: websocket') !== false) {
            performHandshake($client, $data);
            echo "Handshake done\n";
            continue;
        }

        $trimmed = trim($data);

        // raw JSON from Laravel — buffer it
        if (str_starts_with($trimmed, '{') || isset($buffers[(int)$client]) && $buffers[(int)$client] !== '') {
            $buffers[(int)$client] .= $trimmed;

            // try to parse buffered data
            $json = json_decode($buffers[(int)$client], true);

            if ($json === null) {
                // not complete yet, wait for more chunks
                echo "Buffering... current size: " . strlen($buffers[(int)$client]) . "\n";
                continue;
            }

            // complete JSON received
            $message = $buffers[(int)$client];
            $buffers[(int)$client] = '';

        } else {
            // WebSocket frame from browser
            $message = decodeMessage($data);
            if (!$message) {
                echo "Could not decode message\n";
                continue;
            }

            $json = json_decode($message, true);
            if (!$json) {
                echo "Could not parse JSON\n";
                continue;
            }
        }

        echo "Message type: " . $json['type'] . "\n";

        if ($json['type'] === 'dashboard') {
            $dashboards[] = $client;
            echo "Dashboard connected. Total dashboards: " . count($dashboards) . "\n";

        } elseif ($json['type'] === 'error') {
            echo "Error received, broadcasting to " . count($dashboards) . " dashboards\n";
            foreach ($dashboards as $dashboard) {
                fwrite($dashboard, encodeMessage($message));
            }
        }
    }
}

function performHandshake($client, $request): void
{
    preg_match('/Sec-WebSocket-Key: (.+)\r\n/', $request, $matches);
    $key = trim($matches[1]);

    $acceptKey = base64_encode(
        sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true)
    );

    $response  = "HTTP/1.1 101 Switching Protocols\r\n";
    $response .= "Upgrade: websocket\r\n";
    $response .= "Connection: Upgrade\r\n";
    $response .= "Sec-WebSocket-Accept: {$acceptKey}\r\n\r\n";

    fwrite($client, $response);
}

function encodeMessage(string $message): string
{
    $length = strlen($message);
    $frame  = chr(0x81);

    if ($length <= 125) {
        $frame .= chr($length);
    } elseif ($length <= 65535) {
        $frame .= chr(126) . pack('n', $length);
    } else {
        $frame .= chr(127) . pack('J', $length);
    }

    return $frame . $message;
}

function decodeMessage(string $data): ?string
{
    if (strlen($data) < 2) return null;

    $masked  = (ord($data[1]) >> 7) & 0x1;
    $length  = ord($data[1]) & 0x7F;
    $offset  = 2;

    if ($length === 126) {
        $length = unpack('n', substr($data, 2, 2))[1];
        $offset = 4;
    } elseif ($length === 127) {
        $length = unpack('J', substr($data, 2, 8))[1];
        $offset = 10;
    }

    if (!$masked) {
        return substr($data, $offset, $length);
    }

    $mask    = substr($data, $offset, 4);
    $payload = substr($data, $offset + 4, $length);
    $decoded = '';

    for ($i = 0; $i < strlen($payload); $i++) {
        $decoded .= $payload[$i] ^ $mask[$i % 4];
    }

    return $decoded;
}