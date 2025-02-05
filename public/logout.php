<?php
session_start();
session_destroy(); // Supprime toutes les données de session
header("Location: index.php"); // Redirige vers l'accueil
exit();
?>
