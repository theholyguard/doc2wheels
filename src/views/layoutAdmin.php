<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Doc2Wheels" ?></title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>

<nav class="navbar">
			<div class="flex-space-between">
            <a class="navbar-brand" href="index.php">Doc2Wheels</a>
				<ul class="navbar-menu">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="dashboard">Utilisateur</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout">Déconnexion</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login">Connexion</a></li>
                    <li class="nav-item"><a class="nav-link" href="register">Inscription</a></li>
                <?php endif; ?>
				</ul>
			</div>
		</nav>

<div>
<div class="sidebar">
    <ul class="sidebar-list">
        <a href="#">
            <li>Performances</li>
        </a>
        
        <a href="">
            <li>Gestion utilisateurs</li>
        </a>
        
        <a href="">
        <li>Gestion interventions</li>
        </a>
        
        <a href="">
        <li>Historique global</li>
        </a>
        
        <a href="">
        <li>Retour clients</li>
        </a>

    </ul>
</div>
    <?= $content ?>
</div>

<footer class="bg-dark text-white text-center py-3">
    &copy; 2024 Doc2Wheels. Tous droits réservés.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
