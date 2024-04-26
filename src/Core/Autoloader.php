<?php

namespace LeanPHP\Core;
use Exception;

class Autoloader
{
    private $prefix;
    private $prefixLength;
    private $baseDir;
    private $envPath;
    private $router;


    public function __construct($prefix, $baseDir)
    {
        $this->prefix = $prefix;
        $this->prefixLength = strlen($prefix);
        $this->baseDir = $baseDir;
    }

    public function loadClass()
    {
        spl_autoload_register([$this, 'classLoader']);
    }

    public function loadRoutes($requestClass, $responseClass, $routeFile)
    {
        try {
            $this->router = new Router();
            $routes = require $routeFile;
            $routes($this->router);

            $request = new $requestClass();
            $response = new $responseClass();

            $this->router->dispatch($_SERVER['REQUEST_URI'], $request, $response);
        } catch (Exception $e) {
            echo $e->getMessage(); // Basic error output for now
        }
    }

    public function classLoader($class)
    {
        if (strncmp($this->prefix, $class, $this->prefixLength) !== 0) {
            return false;
        }

        $relativeClass = substr($class, $this->prefixLength);
        $file = $this->baseDir . '/' . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
            return true;
        }

        return false;
    }

    public function loadEnv($envFile)
    {
        $this->envPath = realpath(dirname(__DIR__, 2) . '/' . $envFile);

        if (!file_exists($this->envPath)) {
            throw new Exception('Environment file does not exist: ' . $this->envPath);
        }

        $lines = file($this->envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Yorum satırlarını atla
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = $this->parseValue(trim($value));
        
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }

    private function parseValue($value)
    {
        // Süslü parantezler içindeki değeri ayıkla
        if (preg_match('/\{\{"(.*?)"\}\}/', $value, $matches)) {
            // Süslü parantezler içinde çift tırnaklarla çevrili değer varsa
            return $matches[1];
        } elseif (preg_match('/\{\{(.*?)\}\}/', $value, $matches)) {
            // Sadece süslü parantezler içinde değer varsa
            return $matches[1];
        }
    
        // Değer süslü parantez içermezse veya diğer kalıplarla eşleşmezse, çift tırnakları kaldır
        return trim($value, '"');
    }
}

