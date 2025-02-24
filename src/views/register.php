<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="bg-white">

    <div class="flex flex-center vh-100">
        <div class="container">
            <div class="grid grid-cols-1">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center">Inscription</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php elseif (isset($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php endif; ?>
                    <form method="POST" class="form flex-col">
                        <div class="mb-3">
                            <label class="input-label">Nom</label>
                            <input type="text" name="name" class="input" placeholder="Nom" required>
                        </div>
                        <div class="mb-3">
                            <label class="input-label">Email</label>
                            <input type="email" name="email" class="input" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="input-label">Mot de passe</label>
                            <input type="password" name="password" class="input" placeholder="Mot de passe" required>
                        </div>
                        <div class="mb-3">
                            <label class="input-label">Rôle</label>
                            <select name="role" class="input">
                                <option value="client">Client</option>
                                <option value="technician">Technicien</option>
                            </select>
                        </div>
                        <button type="submit" class="button button-primary w-100">S'inscrire</button>
                        <p class="mt-3 text-center"><a href="login.php">Déjà inscrit ? Se connecter</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
$content = ob_get_clean();
include __DIR__ . '/../views/layout.php';
?>