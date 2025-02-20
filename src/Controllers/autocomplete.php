<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../src/Services.php';

$service = new Service();

$query = isset($_GET['q']) ? trim($_GET['q']) : '';

$suggestions = [];

if (!empty($query)) {
    $suggestions = $service->searchServiceSuggestions($query);
}

header('Content-Type: application/json');
echo json_encode($suggestions);
?>
