<?php
$title = "Gestion des interventions";
ob_start();
?>


<h2>Liste de toutes les réparations</h2>
<table>
    <tr>
        <th style="border: 1px solid #000; padding: 8px;">ID</th>
        <th style="border: 1px solid #000; padding: 8px;">Utilisateur</th>
        <th style="border: 1px solid #000; padding: 8px;">Type de service</th>
        <th style="border: 1px solid #000; padding: 8px;">Technicien</th>
        <th style="border: 1px solid #000; padding: 8px;">Statut</th>
        <th style="border: 1px solid #000; padding: 8px;">Date</th>
    </tr>
    <?php foreach ($repairs as $repair): ?>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($repair['id']) ?></td>
            <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($repair['user_id']) ?></td>
            <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($repair['type_service']) ?></td>
            <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($repair['technician_id']) ?></td>
            <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($repair['status']) ?></td>
            <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($repair['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>


<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>




