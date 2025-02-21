<?php
$title = "Avis client";
ob_start();
?>

<h1>Liste des Avis</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Service</th>
            <th>Auteur</th>
            <th>Note</th>
            <th>Commentaire</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($reviews)): ?> <!-- Ajoutez cette condition -->
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td><?php echo htmlspecialchars($review['id']); ?></td>
                    <td><?php echo htmlspecialchars($review['type_service']); ?></td>
                    <td><?php echo htmlspecialchars($review['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($review['rating']); ?></td>
                    <td><?php echo htmlspecialchars($review['comment']); ?></td>
                    <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?> <!-- Ajoutez cette partie pour un message vide -->
            <tr>
                <td colspan="6">Aucun avis disponible.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
