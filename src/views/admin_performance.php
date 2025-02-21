<?php
$title = "Performances";
ob_start();
?>
<h1>Statistiques</h1>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #000; padding: 8px;">Type</th>
            <th style="border: 1px solid #000; padding: 8px;">Total</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Réparations</td>
            <td style="border: 1px solid #000; padding: 8px;"><?php echo $totalRepairs; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Techniciens</td>
            <td style="border: 1px solid #000; padding: 8px;"><?php echo $totalTechnicians; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 8px;">Utilisateurs</td>
            <td style="border: 1px solid #000; padding: 8px;"><?php echo $totalUsers; ?></td>
        </tr>
    </tbody>
</table>

<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
