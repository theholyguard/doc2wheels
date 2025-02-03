<?php
require_once '../src/Repair.php';
require '../public/auth.php';

session_start();
if (isset($_SESSION['success_message'])) {
    echo "<p style='color: green;'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']); // Supprimer le message après affichage
}

redirectIfNotLoggedIn();
if (!isTechnician()) {
    die("Accès interdit.");
}

$repair = new Repair();
$repairs = $repair->getRepairs();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repair->updateRepairStatus($_POST['repair_id'], "validée");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Technicien</title>
</head>
<body>
<h2>Tableau de bord des réparations</h2>

<table border="1">
    <thead>
        <tr>
            <th>Client</th>
            <th>Service</th>
            <th>Adresse</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($repairs as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['client_name']) ?></td>
                <td><?= htmlspecialchars($r['type_service']) ?></td>
                <td><?= htmlspecialchars($r['location']) ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td>
                    <?php if ($r['status'] === 'en attente'): ?>
                        <form method="POST">
                            <input type="hidden" name="repair_id" value="<?= $r['id'] ?>">
                            <button type="submit">Valider</button>
                        </form>
                    <?php else: ?>
                        ✅
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
