<?php 


error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/src/Core/Autoloader.php';
use LeanPHP\Core\Autoloader;
use LeanPHP\Config\AppConfig;

$autoloader = new Autoloader('LeanPHP\\', __DIR__ . '/src');

$envFile = '.env.local';
$autoloader->loadEnv($envFile);
$autoloader->loadClass();


$appConfig = AppConfig::getInstance();
// $databaseManager = DBConfig::getInstance();

$requestClass = $appConfig->getClassPath('request');
$responseClass = $appConfig->getClassPath('response');

$routesPath = __DIR__ . '/src/routes.php';
$autoloader->loadRoutes($requestClass, $responseClass, $routesPath);
