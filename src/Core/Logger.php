<?php
namespace LeanPHP\Core;

class Logger
{

    public static function logError($error) {
        if ($error instanceof \Exception) {
                    //file_put_contents(__DIR__ . '/../../logs/app.log', $logMessage, FILE_APPEND);
        
            file_put_contents(__DIR__ . '/../../app.log', $error, FILE_APPEND);
            error_log("Error: " . $error->getMessage() . " in " . $error->getFile() . " on line " . $error->getLine());
        } else {
            error_log("Error: " . $error);
        }
    }
}
