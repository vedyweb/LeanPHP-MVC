<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloaderi kullanarak dosyaları dahil et
require_once __DIR__ . '/src/Core/Autoloader.php';

// Autoloader nesnesi oluşturma
use LeanPHP\Core\Autoloader;
$autoloader = new Autoloader('LeanPHP\\', __DIR__ . '/src');

$envFile = '.env.local';
$autoloader->loadEnv($envFile);
$autoloader->loadClass();

$requestClass = $_ENV['APP_NAME'] . '\\Core\\Http\\Request';
$responseClass = $_ENV['APP_NAME'] . '\\Core\\Http\\Response';
$routesPath = __DIR__ . '/src/routes.php';

$autoloader->loadRoutes($requestClass, $responseClass, $routesPath);







