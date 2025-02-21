<?php

namespace App\Controllers;

use App\Entities\Admin;
use App\Entities\Auth;

class AdminController
{
    public function __construct() {
        session_start();
        Auth::redirectIfNotLoggedIn();
    }

    public function viewStatistics()
    {
        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit();
        }

        $performances = new Admin();

        $totalRepairs = $performances->getTotalRepairs();
        $totalTechnicians = $performances->getTotalTechnicians();
        $totalUsers = $performances->getTotalUsers();

        include __DIR__ . '/../views/admin_performance.php';
    }
}
?>
