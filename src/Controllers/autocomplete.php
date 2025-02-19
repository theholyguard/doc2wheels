<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../src/Services.php';

$service = new Service();

// ✅ Récupération de la requête utilisateur
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

$suggestions = [];

if (!empty($query)) {
    // Recherche des services correspondant au texte entré
    $suggestions = $service->searchServiceSuggestions($query);
}

// ✅ Renvoie la réponse sous format JSON
header('Content-Type: application/json');
echo json_encode($suggestions);
?>
