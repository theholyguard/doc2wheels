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
            $user = new User();
            $email = $_POST['email'];
            $password = $_POST['password'];

            $loggedInUser = $user->loginUser($email, $password);

            if ($loggedInUser) {
                $_SESSION['user_id'] = $loggedInUser['id'];
                $_SESSION['role'] = $loggedInUser['role'];
                $_SESSION['success_message'] = "Connexion réussie !";

                if ($_SESSION['role'] === 'technician') {
                    header("Location: /dashboard"); // Redirige les techniciens vers le dashboard
                } else {
                    header("Location: /"); // Redirige les clients vers l'accueil
                }
                exit();
            } else {
                $error = "Email ou mot de passe incorrect.";
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
            $user = new User();
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role']; // Récupérer le rôle choisi (client ou technicien)

            if ($user->createUser($name, $email, $password, $role)) {
                $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez vous connecter.";
                header("Location: /login");
                exit();
            } else {
                $error = "Une erreur est survenue, veuillez réessayer.";
            }
        }

        include __DIR__ . '/../views/register.php';
    }

    public function logout()
    {
        session_start();
        session_destroy(); // Supprime toutes les données de session
        header("Location: /"); // Redirige vers l'accueil
        exit();
    }
}
?>