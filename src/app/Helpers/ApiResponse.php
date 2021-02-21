<?php
namespace App\Helpers;

class ApiResponse
{

    const HTTP_OK = 200;

    /**
     * Sets API response
     *
     * @param array $data
     */
    public static function json(int $statusCode, string $message, array $data)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        die(json_encode([
            'message' => $message,
            'data' => $data
        ]));
    }
}