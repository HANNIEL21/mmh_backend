<?php

class ErrorHandler{
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            "code" => $exception->getCode(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine(),
            "message" => $exception->getMessage(),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}