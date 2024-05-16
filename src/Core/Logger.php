<?php

namespace LeanPHP\Core;

/**
 * Class Logger
 *
 * Logs messages to specified log files.
 *
 * @package LeanPHP\Core
 * @author Vedat Yıldırım
 */
class Logger
{
    private static $logFile = __DIR__ . '/../../logs/app.log';
    private static $errorLogFile = __DIR__ . '/../../logs/error.log';

    /**
     * Logs an error message based on the exception or string message.
     *
     * @param \Exception|string $error The exception or error message to log.
     */
    public static function logError($error)
    {
        if ($error instanceof \Exception) {
            $logMessage = self::formatLogMessage('Error', $error->getMessage(), $error->getFile(), $error->getLine());
        } else {
            $logMessage = self::formatLogMessage('Error', $error);
        }
        self::writeLog($logMessage, self::$errorLogFile);
    }

    /**
     * Logs a custom message.
     *
     * @param string $message The message to log.
     */
    public static function logMessage($message)
    {
        $logMessage = self::formatLogMessage('Message', $message);
        self::writeLog($logMessage, self::$logFile);
    }

    /**
     * Logs an info message.
     *
     * @param string $message The info message to log.
     */
    public static function logInfo($message)
    {
        $logMessage = self::formatLogMessage('Info', $message);
        self::writeLog($logMessage, self::$logFile);
    }

    /**
     * Logs a request with URL and method.
     *
     * @param string $url The URL being requested.
     * @param string $method The HTTP method used for the request.
     */
    public static function logRequest($url, $method)
    {
        $logMessage = self::formatLogMessage('Request', "URL: $url, Method: $method");
        self::writeLog($logMessage, self::$logFile);
    }

    /**
     * Formats the log message.
     *
     * @param string $type The type of log (e.g., 'Error', 'Message', 'Info', 'Request').
     * @param string $message The log message.
     * @param string|null $file The file where the log occurred (if applicable).
     * @param int|null $line The line where the log occurred (if applicable).
     * @return string The formatted log message.
     */
    private static function formatLogMessage($type, $message, $file = null, $line = null)
    {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] $type: $message";
        if ($file && $line) {
            $logMessage .= " in $file on line $line";
        }
        return $logMessage . "\n";
    }

    /**
     * Writes the log message to the specified log file.
     *
     * @param string $logMessage The log message to write.
     * @param string $file The log file to write to.
     */
    private static function writeLog($logMessage, $file)
    {
        error_log($logMessage, 3, $file);
    }
}