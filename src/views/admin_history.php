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
                <?php foreach ($history as $repair): ?>
                    <tr>
                        <td><?php echo $repair['id']; ?></td>
                        <td><?php echo htmlspecialchars($repair['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($repair['technician_name']); ?></td>
                        <td><?php echo htmlspecialchars($repair['type_service']); ?></td>
                        <td><?php echo htmlspecialchars($repair['vehicle_name']); ?></td>
                        <td><?php echo htmlspecialchars($repair['price']); ?> €</td>
                        <td><?php echo htmlspecialchars($repair['status']); ?></td>
                        <td><?php echo htmlspecialchars($repair['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>

