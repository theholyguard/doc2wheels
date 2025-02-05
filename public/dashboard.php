<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../src/Repair.php';
require_once '../src/Service.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$repair = new Repair();
$service = new Service();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$repairs = ($role === 'technician') ? $repair->getRepairs() : $repair->getUserRepairs($user_id);

// Récupérer tous les services disponibles, triés par catégorie
$allServicesByCategory = $service->getAllServicesGroupedByCategory();

// Récupérer les services sélectionnés par le technicien
$technicianServices = $service->getTechnicianServices($user_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">Doc2Wheels</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="text-center">Tableau de bord</h2>
        <p class="text-center">Bienvenue, <?= ucfirst($role); ?>.</p>

        <!-- ✅ Affichage des demandes clients -->
        <div class="container my-4">
            <h3 class="text-center">📌 Demandes de réparation</h3>
            <?php if (empty($repairs)): ?>
                <p class="text-center text-muted">Aucune demande en attente.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($repairs as $repair): ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">🔧 <?= htmlspecialchars($repair['type_service']); ?></h5>
                                    <p class="card-text"><strong>📍 Lieu :</strong> <?= htmlspecialchars($repair['location']); ?></p>
                                    <p class="card-text"><strong>🛠 Statut :</strong> 
                                        <span class="badge bg-info"><?= ucfirst($repair['status']); ?></span>
                                    </p>
                                    <p class="card-text"><strong>👤 Client :</strong> <?= htmlspecialchars($repair['client_name']); ?></p>

                                    <!-- Boutons Accepter / Refuser -->
                                    <?php if ($role === 'technician' && $repair['status'] === 'en attente'): ?>
                                        <form method="POST" action="update_repair.php" class="d-inline">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <input type="hidden" name="status" value="en cours">
                                            <button type="submit" class="btn btn-success btn-sm">✅ Accepter</button>
                                        </form>
                                        <form method="POST" action="update_repair.php" class="d-inline">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <input type="hidden" name="status" value="refusé">
                                            <button type="submit" class="btn btn-danger btn-sm">❌ Refuser</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ✅ Gestion des services proposés par le technicien -->
        <?php if ($role === 'technician'): ?>
    <div class="card p-4 mb-4">
        <h4 class="mb-3">🛠️ Mes services proposés</h4>
        <form method="POST" action="update_services.php">
            <input type="hidden" name="technician_id" value="<?= $user_id ?>">

            <?php foreach ($allServicesByCategory as $category => $services): ?>
                <h5 class="mt-3 text-primary"><?= htmlspecialchars($category); ?></h5>
                <div class="row">
                    <?php foreach ($services as $s) : ?>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="services[]" value="<?= $s['id'] ?>"
                                    <?= in_array($s['id'], array_column($technicianServices, 'service_id')) ? 'checked' : '' ?>>
                                <label class="form-check-label"><?= htmlspecialchars($s['name']); ?></label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <hr>
            <?php endforeach; ?>

            <button type="submit" class="btn btn-primary mt-3">💾 Sauvegarder</button>
        </form>
    </div>
<?php endif; ?>

    </div>

</body>
</html>
