<?php
session_start();
require_once '../src/Service.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['technician_id'])) {
    $service = new Service();
    $technician_id = $_POST['technician_id'];
    $selected_services = $_POST['services'] ?? [];

    $service->updateTechnicianServices($technician_id, $selected_services);

    $_SESSION['success_message'] = "Services mis à jour avec succès !";
    header("Location: dashboard.php");
    exit();
}
?>
