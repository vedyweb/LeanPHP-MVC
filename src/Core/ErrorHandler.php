<?php

namespace LeanPHP\Core;

class ErrorHandler
{
    private $logFile;

    public function __construct()
    {
        $this->logFile = __DIR__ . '/../../errors.log';
    }

    public function handle($exception)
    {
        $this->logError($exception);
        $this->respondWithError($exception);
    }

    private function logError($exception)
    {
        // Exception bilgisini bir dosyaya yazar.
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Error: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
        error_log($logMessage, 3, $this->logFile);
    }

    private function respondWithError($exception)
    {
        $statusCode = $this->determineStatusCode($exception);
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => $this->getErrorMessage($statusCode),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'identifier' => uniqid('error_', true)
        ]);
        exit;
    }

    private function determineStatusCode($exception)
    {
        if ($exception instanceof \InvalidArgumentException) {
            return 400;
        } elseif ($exception instanceof \UnauthorizedException) {
            return 401;
        } elseif ($exception instanceof \NotFoundException) {
            return 404;
        }
        return 500;
    }

    private function getErrorMessage($statusCode)
    {
        switch ($statusCode) {
            case 400: return 'Bad Request';
            case 401: return 'Unauthorized';
            case 404: return 'Not Found';
            case 500: return 'Internal Server Error';
            default: return 'Error';
        }
    }
}
