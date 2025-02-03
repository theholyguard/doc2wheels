<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isTechnician() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'technician';
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}
?>
