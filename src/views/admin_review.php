<?php
$title = "Avis client";
ob_start();
?>



<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
