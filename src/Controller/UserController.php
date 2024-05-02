<?php

namespace LeanPHP\Controller;
use LeanPHP\Model\User;
use LeanPHP\Core\ErrorHandler;
use LeanPHP\Core\Request;
use LeanPHP\Core\Response;
use Exception;

class UserController {
    
    private $userModel;
    private $errorHandler;  // ErrorHandler özelliğini doğru şekilde tanımla

    public function __construct() {
        $this->userModel = new User;
        $this->errorHandler = new ErrorHandler;  // ErrorHandler örneğini başlat
    }

    public function getAllUsers(Request $request, Response $response): void {
        try {
            $users = $this->userModel->getAll();
            $response->withJson(['data' => $users])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function getUserById(Request $request, Response $response, $id): void {
        try {
            $user = $this->userModel->getById($id);
            $response->withJson(['data' => $user])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function createUser(Request $request, Response $response): void {
        try {
            $userData = $request->getParsedBody();
            $newUser = $this->userModel->create(
                $userData['email'],
                $userData['username'],
                $userData['password'],
            );
            $response->withJson(['data' => $newUser])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function updateUser(Request $request, Response $response, $id): void {
        try {
            $userData = $request->getParsedBody();
            $updatedUser = $this->userModel->update($id, $userData);
            $response->withJson(['data' => $updatedUser])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function deleteUser(Request $request, Response $response, $id): void {
        try {
            $this->userModel->delete($id);
            $response->withJson(['message' => 'User deleted successfully'])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function patchUser(Request $request, Response $response, $id): void {
        try {
            $userData = $request->getParsedBody();
            $patchedUser = $this->userModel->patch($id, $userData);
            $response->withJson(['data' => $patchedUser])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function searchUser(Request $request, Response $response, $query): void {
        try {
            $results = $this->userModel->search($query);
            $response->withJson(['data' => $results])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function countUsers(Request $request, Response $response): void {
        try {
            $count = $this->userModel->count();
            $response->withJson(['data' => ['count' => $count]])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }

    public function filterUsers(Request $request, Response $response, array $filters): void {
        try {
            $filteredUsers = $this->userModel->filter($filters);
            $response->withJson(['data' => $filteredUsers])->send();
        } catch (Exception $e) {
            $this->errorHandler->handle($e);
        }
    }
}
