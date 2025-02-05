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
    $repair_id = $_POST['repair_id'];
    $status = $_POST['status'];

    $repair = new Repair();
    $repair->updateRepairStatus($repair_id, $status);

    header("Location: dashboard.php");
    exit();
}
?>
