<?php
$title = "Gestion des interventions";
ob_start();
?>


<h2>Liste de toutes les réparations</h2>
<a href="service/create">creer</a>
<table>
    <tr>
        <td>Catégorie</td>
        <td>Prix</td>
    </tr>
    <?php foreach ($services as $service): ?>
        <tr>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?= $service['id'] ?>">
                <td style="border: 1px solid #000; padding: 8px;">
                    <input type="text" name="category" value="<?= htmlspecialchars($service['category']) ?>" required>
                </td>
                <td style="border: 1px solid #000; padding: 8px;">
                    <input type="text" name="price" value="<?= htmlspecialchars($service['price']) ?>" required>
                </td>
                <td style="border: 1px solid #000; padding: 8px;">
                    <button type="submit">Mettre à jour</button>
            </form>
            <form action="/admin/service/delete" method="POST">
                <input type="hidden" name="id" value="<?= $service['id'] ?>">
                <button type="submit">Supprimer</button>
            </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>