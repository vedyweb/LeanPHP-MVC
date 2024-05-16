<?php

namespace LeanPHP\Config;

class AppConfig {
    private static $instance = null;
    private $config;

    private function __construct() {
        $this->loadConfig();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function loadConfig() {

        $this->config = [
            'appMode' => getenv('APP_MODE'),
            'appName' => getenv('APP_NAME'),
            'appHost' => getenv('APP_HOST'),
            'apiKeys' => [
                'service1' => getenv('SERVICE1_API_KEY'),
                'service2' => getenv('SERVICE2_API_KEY'),
            ],
            'emailConfig' => [
                'host' => getenv('EMAIL_HOST'),
                'port' => getenv('EMAIL_PORT'),
                'username' => getenv('EMAIL_USERNAME'),
                'password' => getenv('EMAIL_PASSWORD'),
            ],
            'classPaths' => [
                'request' => getenv('APP_NAME') . '\\Core\\Request',
                'response' => getenv('APP_NAME') . '\\Core\\Response',
            ]
        ];
    }
    
    public function getClassPath($key) {
        return $this->config['classPaths'][$key] ?? null;
    }

    
    public function getDatabaseConfig() {
        // return DBConfig::getInstance();
    }

    public function get($key) {
        return $this->config[$key] ?? null;
    }
    
}