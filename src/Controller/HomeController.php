<?php

namespace LeanPHP\Controller;

use LeanPHP\Core\Request;
use LeanPHP\Core\Response;
use LeanPHP\Core\JwtHelper;
use LeanPHP\Core\ErrorHandler;
use Exception;

class HomeController {
    private $jwtHelper;
    private $errorHandler;

    public function __construct() {
        $this->jwtHelper = new JwtHelper();
        $this->errorHandler = new ErrorHandler();
    }
    /**
     * PAGE: index
     * This method handles what happens when you move to http://yourdomain/HomeController/index (which is the default page btw)
     */
    public function hi() {
        $article = "Welcome to LeanPHP";
        $response = new Response();
    
        if(!$article) {
            return $response->withJSON(['error' => 'Article not found'], 404)->send();
        }
        return $response->withJSON($article)->send();
    }

    public function secured() {
        $article = "Welcome to Secured Area";
        $response = new Response();
    
        if (!$article) {
            return $response->withJSON(['error' => 'Article not found'], 404)->send();
        }
        return $response->withJSON($article)->send();
    }


    public function getUserProfile(Request $request, Response $response) {
        try {
            $this->jwtHelper->getAuthenticate($request, $response);
            $user = JwtHelper::user();

            if (!$user) {
                $response->withJSON(['error' => 'User not authenticated'], 401)->send();
                return;
            }

            $userProfile = [
                'username' => $user['name'],
                'role' => $user['role']
            ];

            $response->withJSON(['user' => $userProfile])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
            $response->withJSON(['error' => $e->getMessage()], 500)->send();
        }
    }

public function install() {
    $dbManager = DBConfig::getInstance();  // DatabaseManager sınıfından bir örnek alın
    $connection = $dbManager->getConnection();    // Veritabanı bağlantısını alın

    // SQL komutlarını doğrudan tanımla
    $sqlContent = <<<SQL
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expiry` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_german2_ci;

INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `email`, `avatar_url`, `created_at`, `updated_at`, `username`, `password`, `token`, `expiry`, `reset_token`) VALUES
(1, 'John', 'Doe', 'john.doe@example.com', 'http://example.com/avatar1.jpg', '2024-04-17 19:30:32', '2024-04-17 19:30:32', 'johndoe', 'password123', 'token123', NULL, NULL),
(2, 'Jane', 'Smith', 'jane.smith@example.com', 'http://example.com/avatar2.jpg', '2024-04-17 19:30:32', '2024-04-17 19:30:32', 'janesmith', 'password456', 'token456', NULL, NULL),
(3, 'Alice', 'Johnson', 'alice.johnson@example.com', 'http://example.com/avatar3.jpg', '2024-04-17 19:30:32', '2024-04-17 19:30:32', 'alicejohnson', 'password789', 'token789', NULL, NULL);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;
SQL;

    // SQL komutlarını veritabanında çalıştır
    try {
        $connection->exec($sqlContent);
        echo "Veritabanı başarıyla kuruldu.";
    } catch (PDOException $e) {
        echo "SQL dosyası yüklenirken hata oluştu: " . $e->getMessage();
    }
}

}