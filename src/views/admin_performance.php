<?php
$title = "Performances";
ob_start();
?>
<h1>Statistiques</h1>
<p>Total des réparations : <?php echo $totalRepairs; ?></p>
<p>Total des techniciens : <?php echo $totalTechnicians; ?></p>
<p>Total des utilisateurs : <?php echo $totalUsers; ?></p>

<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
