<?php

namespace LeanPHP\Core;

/**
 * Class ErrorHandler
 *
 * Handles exceptions and logs them using the Logger class.
 *
 * @package LeanPHP\Core
 * @author Vedat Yıldırım
 */
class ErrorHandler
{
    /**
     * Handles the given exception.
     *
     * @param \Exception $exception The exception to handle.
     */
    public static function handle($exception)
    {
        Logger::logError($exception);
        self::respondWithError($exception);
    }

    /**
     * Responds with an error message based on the exception.
     *
     * @param \Exception $exception The exception to respond to.
     */
    private static function respondWithError($exception)
    {
        $statusCode = self::determineStatusCode($exception);
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => self::getErrorMessage($statusCode),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'identifier' => uniqid('error_', true)
        ]);
        exit;
    }

    /**
     * Determines the HTTP status code based on the exception.
     *
     * @param \Exception $exception The exception to determine the status code for.
     * @return int The HTTP status code.
     */
    private static function determineStatusCode($exception)
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

    /**
     * Returns the error message based on the status code.
     *
     * @param int $statusCode The HTTP status code.
     * @return string The error message.
     */
    private static function getErrorMessage($statusCode)
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