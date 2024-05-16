<?php

namespace LeanPHP\Model;

use Exception;
use PDOException;
use PDO;

/**
 * AuthModel class.
 * Handles authentication-related database operations.
 */
class Auth extends BaseModel{
    protected $db;
    protected $table = 'users';
    private $errorHandler;  // ErrorHandler özelliğini doğru şekilde tanımla

    public function __construct() {
        parent::__construct();  // BaseModel'in constructor'ını çağır
    }

    /**
     * Registers a new user.
     *
     * @param string $username User's username.
     * @param string $password User's password.
     * @param string $email User's email.
     */

    public function registerUser($username, $password, $email)
    {
        try {
            // Kullanıcı adı veya e-posta zaten kullanılıyor mu kontrol et
            $sql = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([':username' => $username, ':email' => $email]);
            if ($stmt->fetchColumn() > 0) {
                return ['error' => true, 'message' => 'Username or email already exists'];
            }

            // Kullanıcı kaydet
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (username, password, email) VALUES (:username, :hashed_password, :email)";
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([':username' => $username, ':hashed_password' => $hashed_password, ':email' => $email]);
            return ['error' => false, 'message' => 'User registered successfully'];
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while registering user.");
        }
    }


    public function loginUser($username)
    {
        try {
            $sql = "SELECT * FROM users WHERE username = :username";
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([':username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while fetching user by username.");
        }
    }

    /**
     * Verifies if a reset token is valid and not expired.
     *
     * @param string $reset_token The token to verify.
     * @return int|false Returns the user_id if the token is valid; otherwise, returns false.
     */
    public function verifyResetToken($reset_token)
    {
        try {
            $sql = "SELECT user_id FROM $this->table WHERE reset_token = :reset_token AND expiry > NOW()";
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([':reset_token' => $reset_token]);
            $result = $stmt->fetch();

            return $result ? $result['user_id'] : false;
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while verifying reset token.");
        }
    }

    /**
     * Stores reset token for a user.
     *
     * @param int $userId User's ID.
     * @param string $token Reset token.
     */
    public function storeResetToken($userId, $token)
    {
        try {
            $query = "UPDATE $this->table SET reset_token = ? WHERE user_id = ?";
            $stmt = $this->getDb()->prepare($query);
            $stmt->execute([$token, $userId]);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while storing reset token.");
        }
    }

    /**
     * Stores authentication token and its expiry for a user.
     *
     * @param int $userId User's ID.
     * @param string $token Authentication token.
     * @param string $expiry Token's expiry time.
     */
    public function validateTokenAndExpiry($token)
    {
        try {
            // $sql = "SELECT * FROM $this->table WHERE token = :token";
            $sql = "SELECT * FROM users WHERE token = :token";
            $stmt = $this->getDb()->prepare($sql);
            $stmt->execute([':token' => $token]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Token Not Found");
        }
    }

    /**
     * Stores authentication token and its expiry for a user.
     *
     * @param int $userId User's ID.
     * @param string $token Authentication token.
     * @param string $expiry Token's expiry time.
     */
    public function saveTokenAndExpiry($userId, $token, $expiry)
    {
        try {
            $query = "UPDATE $this->table SET token = ?, expiry = ? WHERE user_id = ?";
            $stmt = $this->getDb()->prepare($query);
            $stmt->execute([$token, $expiry, $userId]);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while storing token and its expiry.");
        }
    }

    /**
     * Updates user's password.
     *
     * @param int $userId User's ID.
     * @param string $newPassword New password.
     */
    public function updateUserPassword($userId, $newPassword)
    {
        try {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $query = "UPDATE $this->table SET password = ? WHERE user_id = ?";
            $stmt = $this->getDb()->prepare($query);
            $stmt->execute([$hashedPassword, $userId]);
        } catch (PDOException $e) {
            $this->errorHandler->handle($e->getMessage());
            throw new Exception("Database error while updating password.");
        }
    }

    public function getUserByResetToken($token)
    {
        // Veritabanı sorgusu örneği (PDO kullanılarak)
        $query = "SELECT user_id, reset_token FROM users WHERE reset_token = ?";
        $stmt = $this->getDb()->prepare($query);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail($email)
    {
        // Veritabanı sorgusu örneği (PDO kullanılarak)
        $query = "SELECT user_id, email FROM users WHERE email = ?";
        $stmt = $this->getDb()->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
