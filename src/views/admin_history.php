<?php
$title = "Historique";
ob_start();
?>
<h1>Statistiques</h1>
<p>Total des réparations : <?php echo $totalRepairs; ?></p>
<p>Total des techniciens : <?php echo $totalTechnicians; ?></p>
<p>Total des utilisateurs : <?php echo $totalUsers; ?></p>
<a href="/dashboard">Retour au tableau de bord</a>  

<div class="grid grid-cols-12">
    <div class="col-4 h-8 bg-l-brown"></div>
    <div class="col-4 h-8 bg-l-gold"></div>
    <div class="col-4 h-8 bg-l-brown"></div>
    <div class="col-6 h-8 bg-l-gold"></div>
    <div class="col-6 h-8 bg-l-brown"></div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
