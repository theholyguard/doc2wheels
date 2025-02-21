<?php
$title = "Historique";
ob_start();
?>

<h2>Historique des réparations</h2>
<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Technicien</th>
                    <th>Type de service</th>
                    <th>Catégorie de véhicule</th>
                    <th>Prix</th>
                    <th>Status</th>
                    <th>Date de création</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $history): ?>
                    <tr>
                        <td><?php echo $history['id']; ?></td>
                        <td><?php echo htmlspecialchars($history['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($history['technician_name']); ?></td>
                        <td><?php echo htmlspecialchars($history['type_service']); ?></td>
                        <td><?php echo htmlspecialchars($history['vehicle_name']); ?></td>
                        <td><?php echo htmlspecialchars($history['price']); ?> €</td>
                        <td><?php echo htmlspecialchars($history['status']); ?></td>
                        <td><?php echo htmlspecialchars($history['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>

