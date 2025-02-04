<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$title = "Accueil";
ob_start();
?>

<!-- ✅ Messages de succès et statut de connexion -->
<div class="container mt-3">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success text-center"><?= $_SESSION['success_message']; ?></div>
        <?php unset($_SESSION['success_message']); // Supprimer après affichage ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="alert alert-primary text-center">
            ✅ Connecté en tant que <strong><?= ucfirst($_SESSION['role']); ?></strong>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            ❌ Vous n'êtes pas connecté.
        </div>
    <?php endif; ?>
</div>

<header class="hero">
    <div class="container text-center text-white">
        <h1 class="fw-bold">Réparez votre moto en un clic</h1>
        <p class="lead">Un mécanicien vient à vous, où que vous soyez</p>
        <a href="request_repair.php" class="btn btn-warning btn-lg">Demander une réparation</a>
    </div>
</header>

<section class="container my-5">
    <h2 class="text-center">Nos services</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <img src="assets/img/service1.png" alt="Service 1" class="img-fluid">
            <h4>Réparation</h4>
            <p>Nous réparons votre moto à domicile.</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="assets/img/service2.png" alt="Service 2" class="img-fluid">
            <h4>Entretien</h4>
            <p>Service d'entretien préventif.</p>
        </div>
        <div class="col-md-4 text-center">
            <img src="assets/img/service3.png" alt="Service 3" class="img-fluid">
            <h4>Dépannage d'urgence</h4>
            <p>Nous intervenons immédiatement en cas de panne.</p>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include "layout.php";
?>
