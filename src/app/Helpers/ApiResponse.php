<?php
namespace App\Helpers;

class ApiResponse
{

    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_NOT_ALLOWED = 405;

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