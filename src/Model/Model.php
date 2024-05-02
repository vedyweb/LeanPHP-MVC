<?php

namespace LeanPHP\Model;
use LeanPHP\Config\AppConfig;

abstract class Model {
    protected static $db;

    public static function getDb() {
        if (!self::$db) {
            // Burada DatabaseManager'dan bağlantıyı almak için bir kod
            self::$db = AppConfig::getInstance()->getDatabaseConfig();
        }
    }
}

// Uygulamanın başında AbstractModel::init() çağrısını yaparak veritabanı bağlantısını başlatın.
