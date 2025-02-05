<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../src/Repair.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$repair = new Repair();
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$repairs = ($role === 'technician') ? $repair->getRepairs() : $repair->getUserRepairs($user_id);
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

        <?php if ($role === 'technician'): ?>
            <!-- Formulaire d'ajout de service -->
            <div class="card p-4 mb-4">
                <h4>Ajouter un service</h4>
                <form method="POST" action="add_service.php">
                    <div class="mb-3">
                        <label class="form-label">Type de service</label>
                        <input type="text" name="type_service" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lieu</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </form>
            </div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type de service</th>
                    <th>Lieu</th>
                    <th>Statut</th>
                    <?php if ($role === 'technician'): ?>
                        <th>Action</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repairs as $repair): ?>
                    <tr>
                        <td><?= $repair['id']; ?></td>
                        <td><?= $repair['type_service']; ?></td>
                        <td><?= $repair['location']; ?></td>
                        <td><?= ucfirst($repair['status']); ?></td>
                        <?php if ($role === 'technician'): ?>
                            <td>
                                <form method="POST" action="update_repair.php">
                                    <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                    <select name="status" class="form-select">
                                        <option value="en attente">En attente</option>
                                        <option value="en cours">En cours</option>
                                        <option value="terminé">Terminé</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm mt-1">Mettre à jour</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
