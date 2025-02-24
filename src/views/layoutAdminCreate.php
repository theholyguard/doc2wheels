<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Doc2Wheels" ?></title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>

<body>

    <nav class="navbar">
        <div class="container flex-space-between align-items-center">
            <a class="navbar-brand" href="/">Doc2Wheels</a>
            <ul class="navbar-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="../../dashboard">Utilisateur</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../logout">Déconnexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <div class="flex">
        <div class="sidebar">
            <ul class="sidebar-list">
                <li>
                    <a href="../performance">Performances</a>
                </li>
                <li>
                    <a href="../user">Gestion utilisateurs</a>
                </li>
                <li>
                    <a href="../service">Gestion services</a>
                </li>
                <li>
                    <a href="../history">Historique</a>
                </li>
                <li>
                    <a href="../review">Avis client</a>
                </li>
            </ul>
        </div>

        <div class="flex flex-col">
            <?= $content ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        &copy; 2024 Doc2Wheels. Tous droits réservés.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>