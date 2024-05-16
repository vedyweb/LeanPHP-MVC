<?php

namespace LeanPHP\Core;
use Exception;

class EnvLoader
{

    private $envPath;

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
            $value = $this->parseEnvValue(trim($value));
        
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
    private function parseEnvValue($value)
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

    public function loadYaml($yamlFile)
    {
        $yamlPath = realpath(dirname(__DIR__) . '/' . $yamlFile);

        if (!file_exists($yamlPath)) {
            throw new Exception('YAML file does not exist: ' . $yamlPath);
        }

        $lines = file($yamlPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $context = '';

        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue; // Yorum satırlarını atla
            }

            if (preg_match('/^(\w+):$/', trim($line), $matches)) {
                // Yeni bir başlık bulundu
                $context = $matches[1];
                continue;
            }

            if ($context && strpos($line, ':') !== false) {
                list($name, $value) = explode(':', $line, 2);
                $name = trim($name);
                $value = $this->parseYamlValue(trim($value));

                $envName = strtoupper($context . '_' . $name);
                putenv("$envName=$value");
                $_ENV[$envName] = $value;
                $_SERVER[$envName] = $value;
            }
        }
    }

    private function parseYamlValue($value)
    {
        if (is_numeric($value)) {
            return $value;
        } elseif (in_array(strtolower($value), ['true', 'false'])) {
            return strtolower($value) === 'true';
        }

        return trim($value, '"');
    }
}

// Load the YAML configuration
$loader = new ConfigLoader();
$loader->loadYaml('config.yaml');

// Accessing environment variables
echo 'App Name: ' . getenv('APP_NAME') . "\n";
echo 'Database Host: ' . getenv('DATABASE_HOST') . "\n";

?>
