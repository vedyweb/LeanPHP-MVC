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

/* Yapılandırmadan çeşitli bilgileri al
$appMode = $appConfig->get('appMode');
$appName = $appConfig->get('appName');
$serviceKey = $appConfig->get('apiKeys')['service1'];  // 'service1' için API anahtarını al

// 'host' ve 'path' bilgilerini al (varsayılan değerlerle)
$host = $appConfig->get('appHost');
$path = '/api/path';  // Bu örnekte statik bir değer kullanılmıştır.

echo "Application Mode: $appMode\n";
echo "Application Name: $appName\n";
echo "Service Key: $serviceKey\n";
echo "Host: $host\n";
echo "Path: $path\n";
*/
$routesPath = __DIR__ . '/src/routes.php';
$autoloader->loadRoutes($requestClass, $responseClass, $routesPath);
