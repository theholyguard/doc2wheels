<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="bg-white">
    <nav class="navbar">
        <div class="container flex-space-between align-items-center">
            <a class="navbar-brand" href="/">Doc2Wheels</a>
            <ul class="navbar-menu">
                <li><a href="register">Créer un compte</a></li>
                <li><a href="/">Annuler</a></li>
            </ul>
        </div>
    </nav>

    <div class="flex flex-center vh-100">
        <div class="container">
            <div class="grid grid-cols-1">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center">Connexion</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST" class="form flex-col">
                        <div class="mb-3">
                            <label class="input-label">Email</label>
                            <input type="email" name="email" class="input" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label class="input-label">Mot de passe</label>
                            <input type="password" name="password" class="input" placeholder="Mot de passe" required>
                        </div>
                        <button type="submit" class="button button-primary w-100">Se connecter</button>
                        <p class="mt-3 text-center"><a href="register.php">Créer un compte</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>