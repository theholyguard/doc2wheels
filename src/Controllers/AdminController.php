<?php

namespace App\Controllers;

use App\Entities\Admin;
use App\Entities\Auth;

class AdminController
{
    private $performances;

    public function __construct() {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit();
        }

        $this->performances = new Admin();
    }

    private function getStatistics()
    {
        return [
            'totalRepairs' => $this->performances->getTotalRepairs(),
            'totalTechnicians' => $this->performances->getTotalTechnicians(),
            'totalUsers' => $this->performances->getTotalUsers(),
        ];
    }

    public function viewStatisticsPerformance()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_performance.php';
    }

    public function viewStatisticsUser()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_user.php';
    }

    public function viewStatisticsRepair()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_repair.php';
    }

    public function viewStatisticsHistory()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_history.php';
    }

    public function viewStatisticsReview()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_review.php';
    }
}
?>
