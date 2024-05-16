<?php

require_once 'Logger.php';
require_once 'ErrorHandler.php';
require_once 'Router.php';
require_once 'DBConfig.php';

use LeanPHP\Core\Logger;
use LeanPHP\Core\ErrorHandler;
use LeanPHP\Core\Router;
use LeanPHP\Config\DBConfig;

// Logger sınıfı otomatik olarak kullanılacak, Logger::logError, Logger::logInfo vb.

// DBConfig'i başlat ve bağlantıyı sağla
$dbConfig = DBConfig::getInstance();
$connection = $dbConfig->getConnection();

// Router'ı başlat ve isteği yönlendir
$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER, $_SERVER);
