<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../src/Repair.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'technician') {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $type_service = $_POST['type_service'];
    $location = $_POST['location'];

    $repair = new Repair();
    $repair->addService($user_id, $type_service, $location);

    header("Location: dashboard.php");
    exit();
}
?>
