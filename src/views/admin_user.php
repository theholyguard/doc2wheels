<?php
if (!isset($users)) {
    die("Erreur : les utilisateurs ne sont pas chargés correctement.");
}
$title = "Gestion des utilisateurs";
ob_start();
?>

<h2>Liste de tous les utilisateurs</h2>
<ul>
    <?php foreach ($users as $user): ?>
        <li><?= htmlspecialchars($user['name']) ?> - <?= htmlspecialchars($user['email']) ?> - <?= htmlspecialchars($user['role']) ?></li>
    <?php endforeach; ?>
</ul>

<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>

