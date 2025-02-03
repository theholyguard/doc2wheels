<?php
require_once '../src/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // "client" ou "technician"

    if ($user->createUser($name, $email, $password, $role)) {
        header("Location: login.php?success=1");
        exit();
    } else {
        echo "Erreur lors de l'inscription.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
<h2>Inscription</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Nom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <select name="role">
        <option value="client">Client</option>
        <option value="technician">Technicien</option>
    </select>
    <button type="submit">S'inscrire</button>
</form>
</body>
</html>
