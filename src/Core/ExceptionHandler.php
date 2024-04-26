<?php 

namespace LeanPHP\Core;

class ExceptionHandler
{
    public static function handleException($exception)
    {
        // HTTP durum kodunu ayarla
        http_response_code(500);

        // Hata mesajını JSON formatında gönder
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Internal Server Error',
            'message' => $exception->getMessage()
        ]);

        // Hata loglaması yap
        Logger::logError($exception);
    }

    public static function handleShutdown()
    {
        $error = error_get_last();
        if ($error !== NULL) {
            $exception = new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
            self::handleException($exception);
        }
    }
}
