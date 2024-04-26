<?php

namespace LeanPHP\Model;
use LeanPHP\Config\DatabaseManager;

use PDO;
use PDOException;

class UserModel {
    protected $db;
    protected $table = 'users';

    protected function getDb() {
        if (!$this->db) {
            $databaseManager = new DatabaseManager();
            $this->db = $databaseManager->getConnection();  // Burada doğru şekilde atama yapıyoruz.
        }
        return $this->db;
    }

    public function getAll(): array {
        $query = "SELECT * FROM $this->table";
        try {
            $stmt = $this->getDb()->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleException($e);
            return [];
        }
    }

    /**
     * Fetch a single user by their ID.
     *
     * @param int $userId
     * @return array|null
     */
    public function getById(int $userId): ?array {
        $query = "SELECT * FROM $this->table WHERE user_id = :userId";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            $this->handleException($e);
            return null;
        }
    }

    /**
     * Create a new user in the database.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string|null $profilePicture
     * @param string|null $bio
     * @return bool
     */
    public function create(string $username, string $email, string $password): bool {
        $query = "INSERT INTO $this->table (username, email, password VALUES (:username, :email, :password)";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleException($e);
            return false;
        }
    }

    /**
     * Update a user's details in the database.
     *
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function update(int $userId, array $data): bool {
        $setString = '';
        foreach ($data as $key => $value) {
            $setString .= "$key = :$key, ";
        }
        $setString = rtrim($setString, ', ');

        $query = "UPDATE $this->table SET $setString WHERE user_id = :userId";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            foreach ($data as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleException($e);
            return false;
        }
    }

    /**
     * Delete a user from the database.
     *
     * @param int $userId
     * @return bool
     */
    public function delete(int $userId): bool {
        $query = "DELETE FROM $this->table WHERE user_id = :userId";
        try {
            $stmt = $this->getDb()->prepare($query);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $this->handleException($e);
            return false;
        }
    }

    /**
     * Search users by username.
     *
     * @param string $username
     * @return array
     */
    public function search(string $username): array {
        $query = "SELECT * FROM $this->table WHERE username LIKE :username";
        try {
            $stmt = $this->getDb()->prepare($query);
            $username = "%$username%";
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleException($e);
            return [];
        }
    }

    /**
     * Count the total number of users in the database.
     *
     * @return int
     */
    public function count(): int {
        $query = "SELECT COUNT(*) FROM $this->table";
        try {
            $stmt = $this->getDb()->query($query);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            $this->handleException($e);
            return 0;
        }
    }

    /**
     * Filter users based on given criteria.
     *
     * @param array $criteria
     * @return array
     */
    public function filter(array $criteria): array {
        $query = "SELECT * FROM $this->table WHERE 1=1";
        $params = [];

        if (isset($criteria['username'])) {
            $query .= " AND username LIKE :username";
            $params['username'] = "%" . $criteria['username'] . "%";
        }

        if (isset($criteria['email'])) {
            $query .= " AND email LIKE :email";
            $params['email'] = "%" . $criteria['email'] . "%";
        }

        if (isset($criteria['join_date_from'])) {
            $query .= " AND join_date >= :join_date_from";
            $params['join_date_from'] = $criteria['join_date_from'];
        }

        if (isset($criteria['join_date_to'])) {
            $query .= " AND join_date <= :join_date_to";
            $params['join_date_to'] = $criteria['join_date_to'];
        }

        if (isset($criteria['last_login_from'])) {
            $query .= " AND last_login >= :last_login_from";
            $params['last_login_from'] = $criteria['last_login_from'];
        }

        if (isset($criteria['last_login_to'])) {
            $query .= " AND last_login <= :last_login_to";
            $params['last_login_to'] = $criteria['last_login_to'];
        }

        try {
            $stmt = $this->getDb()->prepare($query);

            foreach ($params as $key => &$value) {
                $stmt->bindParam(":$key", $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->handleException($e);
            return [];
        }
    }
}