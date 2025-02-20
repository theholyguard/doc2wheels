<?php

namespace App\Controllers;

use App\Entities\User;

class AuthController
{
    public function login()
    {
        session_start();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new User();
            $loginResult = $user->loginUser($email, $password);

            if (is_array($loginResult)) {
                $_SESSION['user_id'] = $loginResult['id'];
                $_SESSION['role'] = $loginResult['role'];
                header("Location: /dashboard");
                exit();
            } else {
                $error = $loginResult;
            }
        }

        include __DIR__ . '/../views/login.php';
    }

    public function register()
    {
        session_start();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'] ?? 'client';

            $user = new User();
            $registerResult = $user->createUser($name, $email, $password, $role);

            if ($registerResult === true) {
                header("Location: /login");
                exit();
            } else {
                $error = $registerResult;
            }
        }

        include __DIR__ . '/../views/register.php';
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit();
    }
}
?>