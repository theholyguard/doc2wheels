<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "Doc2Wheels" ?></title>
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <nav class="navbar">
        <div class="container flex-space-between align-items-center">
            <a class="navbar-brand" href="/">Doc2Wheels</a>
            <ul class="navbar-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="dashboard">Utilisateur</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li><a href="admin/">Administration</a></li>
                    <?php endif; ?>
                    <li><a href="logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login">Connexion</a></li>
                    <li><a href="register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="container my-5">
        <?= $content ?>
    </div>

    <footer class="bg-d-grey text-white text-center py-3">
        &copy; 2024 Doc2Wheels. Tous droits réservés.
    </footer>

    <script src="assets/js/main.js"></script>
</body>

</html>