<?php

function rateLimitMiddleware($token, $limit = 10, $interval = 60) // 10 requests per 60 seconds
{
    $file = __DIR__ . '/../storage/rate_limits.json';

    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }

    $data = json_decode(file_get_contents($file), true);
    $now = time();

    if (!isset($data[$token])) {
        // First request
        $data[$token] = [
            'count' => 1,
            'start_time' => $now
        ];
    } else {
        $elapsed = $now - $data[$token]['start_time'];

        if ($elapsed < $interval) {
            // Within window
            if ($data[$token]['count'] >= $limit) {
                http_response_code(429);
                echo json_encode(['error' => 'Too many requests. Try again later.']);
                exit;
            }

            $data[$token]['count']++;
        } else {
            // Reset count after interval
            $data[$token] = [
                'count' => 1,
                'start_time' => $now
            ];
        }
    }

    file_put_contents($file, json_encode($data));
}
