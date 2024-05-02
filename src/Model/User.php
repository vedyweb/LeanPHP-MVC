<?php

namespace LeanPHP\Model;
use LeanPHP\Core\ErrorHandler;
use PDO;
use PDOException;
use Exception;
use LeanPHP\Config\DBConfig;
use LeanPHP\Model\BaseModel;

class User extends BaseModel{

    protected $db;
    protected $table = 'users';

    public function __construct() {
        parent::__construct();  // BaseModel'in constructor'ını çağır
    }

    public function getByEmail(string $email): ?array {
        $query = "SELECT * FROM $this->table WHERE email = :email";
        return $this->executeQuery($query, ['email' => $email], false);
    }
}    
