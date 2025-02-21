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
            $result = $user->loginUser($email, $password);

            if (is_array($result)) {
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['role'] = $result['role'];
                header("Location: /dashboard");
                exit();
            } else {
                $error = $result;
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
            $result = $user->createUser($name, $email, $password, $role);

            if ($result === true) {
                $success = "Un email de validation a été envoyé à votre adresse email. Veuillez vérifier votre email pour valider votre compte.";
            } else {
                $error = $result;
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

    public function verifyEmail() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $user = new User();
            $verificationResult = $user->verifyEmail($token);

            if ($verificationResult === true) {
                $_SESSION['success_message'] = "Votre compte a été validé avec succès ! Vous pouvez maintenant vous connecter.";
                header("Location: /login");
                exit();
            } else {
                $error = $verificationResult;
            }
        } else {
            $error = "Token de validation manquant.";
        }

        include __DIR__ . '/../views/verify.php';
    }
}
?>