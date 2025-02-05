<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../src/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Récupérer le rôle choisi (client ou technicien)

    if ($user->createUser($name, $email, $password, $role)) {
        $_SESSION['success_message'] = "Inscription réussie ! Vous pouvez vous connecter.";
        header("Location: login.php");
        exit();
    } else {
        $error = "Une erreur est survenue, veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Doc2Wheels</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Déjà inscrit ?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Annuler</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg p-4">
                        <h2 class="text-center">Inscription</h2>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="name" class="form-control" placeholder="Nom" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mot de passe</label>
                                <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rôle</label>
                                <select name="role" class="form-select" required>
                                    <option value="client">Client</option>
                                    <option value="technician">Technicien</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                            <p class="mt-3 text-center"><a href="login.php">Déjà inscrit ? Se connecter</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
