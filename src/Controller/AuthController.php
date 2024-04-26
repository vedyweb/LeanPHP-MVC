<?php

namespace LeanPHP\Controller;

use LeanPHP\Core\Http\Request;
use LeanPHP\Core\Http\Response;
use LeanPHP\Model\AuthModel;
use LeanPHP\Core\JwtAuth;
use LeanPHP\Core\EmailService;
use Exception;
use PDO;

class AuthController
{
    private $authModel;
    private $jwtAuth;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->authModel = new AuthModel();
    }

    /**
     * Handles user registration.
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function register(Request $request, Response $response)
    {
        try {
            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            if (empty($username) || empty($password) || empty($email)) {
                return $response->withJSON(['error' => 'All fields are required'], 400)->send();
            } else {
                $result = $this->authModel->registerUser($username, $password, $email);
                if ($result['error']) {
                    return $response->withJSON(['error' => $result['message']], 409)->send(); // 409 Conflict
                }
                return $response->withJSON(['message' => $result['message']])->send();
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            return $response->withJSON(['error' => 'Internal Server Error'], 500)->send();
        }
    }

    public function login(Request $request, Response $response)
    {

        $username = $request->get('username');
        $password = $request->get('password');

        $user = $this->authModel->loginUser($username);
        if (!$user) {
            return $response->withJSON(['error' => 'User not found'], 404);
        }

        if (!password_verify($password, $user['password'])) {
            return $response->withJSON(['error' => 'Invalid credentials'], 401);
        }

        if (password_verify($password, $user['password'])) {

            // Token için payload oluştur
            $payload = ["sub" => $user['user_id'], "name" => $user['username'], "iat" => time()];
            $tokenValidityInSeconds = 3600; // 1 saat

            $this->jwtAuth = new JwtAuth();
            $token = $this->jwtAuth->createJWT($payload, $tokenValidityInSeconds);
            $expiryDate = date('Y-m-d H:i:s', time() + $tokenValidityInSeconds);

            // Token ve expiry time'ı kaydet
            $this->authModel->saveTokenAndExpiry($user['user_id'], $token, $expiryDate);

            // Token'ı response header'a ekle
            $response = $response->withHeader('Authorization', 'Bearer ' . $token);

            //return $response->withJSON(['User Login successfully ' =>  $token])->send();
            return $response->withJSON(['token' => $token])->send();
        } else {
            // Eğer şifre doğrulanamazsa, hata mesajı dön
            return $response->withJSON(['error' => 'Invalid credentials'], 401)->send();
        }
    }

    /*
    public function forgotPassword(Request $request, Response $response, $token)
    {
        $email = $request->get('email');

        if (empty($email)) {
            $response->json(['error' => 'Email is required'], 400);
            return;
        }

        try {
            $user = $this->authModel->getUserByEmail($email);
            if (!$user) {
                $response->json(['error' => 'User not found'], 404);
                return;
            }

            $resetToken = bin2hex(random_bytes(50));
            $expiry = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

            $this->authModel->storeResetToken($user['user_id'], $resetToken, $expiry);

            $url = getenv('APP_URL');
            $folder = getenv('APP_FOLDER');
            $resetLink = $url . $folder . "resetPassword/{$resetToken}";

            print_r($resetLink);

            // Set headers
            $headers = "From: no-reply@vedyweb.com\r\n";
            $headers .= "Reply-To: no-reply@vedyweb.com\r\n";
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";

            // Mail subject
            $subject = "Your Password Reset Link";

            // Mail body
            $message = "<html><body>";
            $message .= "<p>Click <a href='{$resetLink}'>here</a> to reset your password.</p>";
            $message .= "</body></html>";


            // Multiple recipients
            $to = $email; // note the comma

            // Subject
            $subject = 'Your Password Reset Link from ' . $url;

            // Message
            $message = "<html><body>";
            $message .= "<p>Your custom HTML messega lines ...</p>";
            $message .= "<p>Click <a href='{$resetLink}'>here</a> to reset your password.</p>";
            $message .= "</body></html>";

            // To send HTML mail, the Content-type header must be set
            $headers = "From: no-reply@vedyweb.com\r\n";
            $headers .= "Reply-To: no-reply@vedyweb.com\r\n";
            //$headers .= 'Cc: birthdayarchive@example.com';
            //$headers. = 'Bcc: birthdaycheck@example.com';
            $headers .= "Content-type: text/html; charset=UTF-8\r\n";
            $headers .= "MIME-Version: 1.0\r\n";

            // Sending email
            if (!mail($to, $subject, $message, $headers)) {
                $response->json(['error' => 'Failed to send email'], 500);
                return;
            }

            $response->json(['message' => 'Reset email sent successfully', 'resetLink' => $resetLink]);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }
    }
*/

    public function resetPassword(Request $request, Response $response, $token)
    {
        //$token = $request->get('token');  // Token kullanıcıdan GET parametresi olarak alınır

        if (empty($token)) {
            $response->json(['error' => 'Token is required'], 400);
            return;
        }

        try {
            $user = $this->authModel->getUserByResetToken($token);  // Token'e göre kullanıcıyı getir
            if (!$user) {
                $response->json(['error' => 'Invalid or expired token'], 404);
                return;
            }

            $newPassword = $request->get('newPassword');
            
            $userId = $user['user_id'];

            print_r($user['user_id'] . " - " . $newPassword);

            if (empty($newPassword)) {
                $response->withJSON(['error' => 'New password is required'], 400);
            }


            // Şifreyi güncelle
            $this->authModel->updateUserPassword($userId, $newPassword);

            $response->json(['message' => 'Token is valid and Password updated successfully']);
        } catch (Exception $e) {
            $response->json(['error' => $e->getMessage()], 500);
        }

    }

    /*
        public function resetPassword(Request $request, Response $response, $token)
        {
            try {
                if (empty($token)) {
                    return $response->withJSON(['error' => 'Token is required'], 400);
                }

                $userId = $this->authModel->verifyResetToken($token);
                if (!$userId) {
                    $response->withJSON(['error' => 'Invalid or expired token'], 401);
                }

                $newPassword = $request->get('newPassword');
                print_r($newPassword);
                exit();

                if (empty($newPassword)) {
                    $response->withJSON(['error' => 'New password is required'], 400);
                }

                $this->authModel->updatePassword($userId, $newPassword);
                $response->withJSON(['message' => 'Password reset successfully']);
            } catch (Exception $e) {
                error_log($e->getMessage());
                $response->withJSON(['error' => 'Internal Server Error'], 500);
            }
        }
    */
    /**
     * Retrieves a user's data based on their email.
     *
     * @param string $email User's email.
     * @return array|false User data if exists, otherwise false.
     */
    public function getUserByEmail($email)
    {
        try {
            $query = "SELECT * FROM  $this->table WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDO $e) {
            $this->logError($e->getMessage());
            throw new Exception("Database error while fetching user by email.");
        }
    }
}
