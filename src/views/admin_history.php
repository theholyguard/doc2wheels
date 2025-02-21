<?php
$title = "Historique";
ob_start();
?>

<h2>Historique des réparations</h2>
<table>
                <tr>
                    <th style="border: 1px solid #000; padding: 8px;">ID</th>
                    <th style="border: 1px solid #000; padding: 8px;">Client</th>
                    <th style="border: 1px solid #000; padding: 8px;">Technicien</th>
                    <th style="border: 1px solid #000; padding: 8px;">Type de service</th>
                    <th style="border: 1px solid #000; padding: 8px;">Catégorie de véhicule</th>
                    <th style="border: 1px solid #000; padding: 8px;">Prix</th>
                    <th style="border: 1px solid #000; padding: 8px;">Status</th>
                    <th style="border: 1px solid #000; padding: 8px;">Date de création</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $history): ?>
                    <tr>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo $history['id']; ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['client_name']); ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['technician_name']); ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['type_service']); ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['vehicle_name']); ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['price']); ?> €</td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['status']); ?></td>
                        <td style="border: 1px solid #000; padding: 8px;"><?php echo htmlspecialchars($history['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>

