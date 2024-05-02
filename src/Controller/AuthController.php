<?php

namespace LeanPHP\Controller;

use LeanPHP\Core\Request;
use LeanPHP\Core\Response;
use LeanPHP\Model\Auth;
use LeanPHP\Core\JwtHelper;
use LeanPHP\Core\ErrorHandler;
use Exception;

class AuthController
{
    private $authModel;
    private $jwtHelper;
    private $errorHandler;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->authModel = new Auth();
        $this->jwtHelper = new JwtHelper();
        $this->errorHandler = new ErrorHandler();  // Initialize the ErrorHandler
    }

    /**
     * Handles user registration.
     *
     * @param Request $request
     * @param Response $response
     */
    public function register(Request $request, Response $response)
    {
        try {
            $username = $request->get('username');
            $password = $request->get('password');
            $email = $request->get('email');

            if (empty($username) || empty($password) || empty($email)) {
                $response->withJSON(['error' => 'All fields are required'], 400)->send();
                return;
            }

            $result = $this->authModel->registerUser($username, $password, $email);
            if ($result['error']) {
                $response->withJSON(['error' => $result['message']], 409)->send(); // 409 Conflict
                return;
            }
            $response->withJSON(['message' => 'User registered successfully'])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function login(Request $request, Response $response)
    {
        try {
            $username = $request->get('username');
            $password = $request->get('password');

            $user = $this->authModel->loginUser($username);
            if (!$user) {
                $response->withJSON(['error' => 'User not found'], 404)->send();
                return;

            }

            if (!password_verify($password, $user['password'])) {
                $response->withJSON(['error' => 'Invalid credentials'], 401)->send();
                return;
            }

            $payload = ["sub" => $user['user_id'], "name" => $user['username'], "iat" => time()];
            $tokenValidityInSeconds = 3600; // 1 hour
            $token = $this->jwtHelper->createJWT($payload, $tokenValidityInSeconds);
            $expiryDate = date('Y-m-d H:i:s', time() + $tokenValidityInSeconds);

            $this->authModel->saveTokenAndExpiry($user['user_id'], $token, $expiryDate);
            $response->withHeader('Authorization', 'Bearer ' . $token)
                     ->withJSON(['token' => $token])
                     ->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function forgotPassword(Request $request, Response $response)
    {
        try {
            $email = $request->get('email');
            if (empty($email)) {
                $response->withJSON(['error' => 'Email is required'], 400)->send();
                return;
            }

            $user = $this->authModel->getUserByEmail($email);

            if (!$user) {
                $response->withJSON(['error' => 'User not found'], 404)->send();
                return;
            }

            $resetToken = bin2hex(random_bytes(50));
            $expiry = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry

            $this->authModel->storeResetToken($user['user_id'], $resetToken, $expiry);
            $resetLink = getenv('APP_URL') . getenv('APP_FOLDER') . "resetPassword/{$resetToken}";
            print_r($resetLink);
            // Assuming you have a method to send emails
            $this->sendResetEmail($email, $resetLink);

            $response->withJSON(['message' => 'Reset email sent successfully', 'resetLink' => $resetLink])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    // Implement other methods similarly...

    /**
     * Helper method to send email for password reset
     */
    private function sendResetEmail($email, $resetLink)
    {
        $subject = "Your Password Reset Link";
        $message = "<html><body>";
        $message .= "<p>Click <a href='{$resetLink}'>here</a> to reset your password.</p>";
        $message .= "</body></html>";
        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Reply-To: no-reply@example.com\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";

        if (!mail($email, $subject, $message, $headers)) {
            throw new Exception('Failed to send email');
        }
    }

    public function resetPassword(Request $request, Response $response, $token)
    {
        try {

            if (empty($token)) {
                $response->withJSON(['error' => 'Token is required'], 400)->send();
                return;
            }

            $user = $this->authModel->getUserByResetToken($token);
            if (!$user) {
                $response->withJSON(['error' => 'Invalid or expired token'], 404)->send();
                return;
            }

            $newPassword = $request->get('newPassword');
            if (empty($newPassword)) {
                $response->withJSON(['error' => 'New password is required'], 400)->send();
                return;
            }

            $this->authModel->updateUserPassword($user['user_id'], $newPassword);
            $response->withJSON(['message' => 'Password updated successfully'])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    /**
     * Retrieves a user's data based on their email.
     *
     * @param Request $request
     * @param Response $response
     */
    public function getUserByEmail(Request $request, Response $response)
    {
        try {
            $email = $request->get('email');
            $user = $this->authModel->getUserByEmail($email);
            if (!$user) {
                $response->withJSON(['error' => 'User not found'], 404)->send();
                return;
            }
            $response->withJSON(['data' => $user])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }
}
