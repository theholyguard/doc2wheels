<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Ne démarre la session que si elle n'est pas déjà active
}


require_once '../src/User.php';


session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $loggedInUser = $user->loginUser($email, $password);

    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['role'] = $loggedInUser['role'];

        if ($loggedInUser['role'] === 'technician') {
            header("Location: dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
<h2>Connexion</h2>
<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>
</body>
</html>
