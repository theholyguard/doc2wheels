<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Vérifie si une session est déjà active avant de la démarrer
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../src/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedInUser = $user->loginUser($email, $password);

    if ($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['role'] = $loggedInUser['role'];
        $_SESSION['success_message'] = "✅ Connexion réussie !";
    
        header("Location: " . ($loggedInUser['role'] === 'technician' ? "dashboard.php" : "index.php"));
        exit();
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
