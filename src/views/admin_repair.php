<?php
$title = "Gestion des interventions";
ob_start();
?>


<h2>Liste de toutes les réparations</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Utilisateur</th>
        <th>Type de service</th>
        <th>Technicien</th>
        <th>Statut</th>
        <th>Date</th>
    </tr>
    <?php foreach ($repairs as $repair): ?>
        <tr>
            <td><?= htmlspecialchars($repair['id']) ?></td>
            <td><?= htmlspecialchars($repair['user_id']) ?></td>
            <td><?= htmlspecialchars($repair['type_service']) ?></td>
            <td><?= htmlspecialchars($repair['technician_id']) ?></td>
            <td><?= htmlspecialchars($repair['status']) ?></td>
            <td><?= htmlspecialchars($repair['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>


<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>




