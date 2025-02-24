<?php
$title = "Avis client";
ob_start();
?>

<h1>Liste des Avis</h1>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #000; padding: 8px;">ID</th>
            <th style="border: 1px solid #000; padding: 8px;">Service</th>
            <th style="border: 1px solid #000; padding: 8px;">Auteur</th>
            <th style="border: 1px solid #000; padding: 8px;">Note</th>
            <th style="border: 1px solid #000; padding: 8px;">Commentaire</th>
            <th style="border: 1px solid #000; padding: 8px;">Date</th>
            <th style="border: 1px solid #000; padding: 8px;">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $review): ?>
                <tr>
                    <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($review['id']); ?></td>
                    <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($review['type_service']); ?>
                    </td>
                    <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($review['user_name']); ?></td>
                    <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($review['rating']); ?></td>
                    <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($review['comment']); ?></td>
                    <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($review['created_at']); ?>
                    <td style="border: 1px solid #000; padding: 8px;">
                        <form action="/admin/review/delete" method="POST">
                            <input type="hidden" name="id" value="<?= $review['id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
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