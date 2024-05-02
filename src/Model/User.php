<?php

namespace LeanPHP\Model;
use LeanPHP\Model\BaseModel;

class User extends BaseModel{

    protected $db;
    protected $table = 'users';

    public function __construct() {
        parent::__construct();  // BaseModel'in constructor'ını çağır
    }

    public function getAll(): array {
        return $this->executeQuery("SELECT * FROM $this->table");
    }


    public function getById(int $user_id): ?array {
        $query = "SELECT * FROM $this->table WHERE user_id = :userId";
        $result = $this->executeQuery($query, ['userId' => $user_id], false);
        return $result ?: ['error' => true, 'message' => 'Record not found'];
    }

    public function create(array $data): bool {
        $keys = array_keys($data);
        $fields = implode(", ", $keys);
        $placeholders = ":" . implode(", :", $keys);
        $query = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";
        return $this->executeQuery($query, $data, false);
    }

    public function update(int $user_id, array $data): bool {
        $setString = '';
        foreach ($data as $key => $value) {
            $setString .= "$key = :$key, ";
        }
        $setString = rtrim($setString, ', ');
        $query = "UPDATE $this->table SET $setString WHERE user_id = :userId";
        $data['userId'] = $user_id;
        return $this->executeQuery($query, $data, false);
    }

    public function delete(int $user_id): bool {
        $query = "DELETE FROM $this->table WHERE user_id = :userId";
        return $this->executeQuery($query, ['userId' => $user_id], false);
    }

    public function getByEmail(string $email): ?array {
        $query = "SELECT * FROM $this->table WHERE email = :email";
        return $this->executeQuery($query, ['email' => $email], false);
    }
}    
