<?php

namespace App\Controllers;

use App\Entities\Repair;
use App\Entities\Service;
use App\Entities\Auth;

class DashboardController
{
    public function index()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        $repair = new Repair();
        $service = new Service();
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        $repairs = ($role === 'technician') ? $repair->getRepairs() : $repair->getUserRepairs($user_id);

        // Récupérer tous les services disponibles, triés par catégorie
        $allServicesByCategory = $service->getAllServicesGroupedByCategory();

        // Récupérer les services sélectionnés par le technicien
        $technicianServices = $service->getTechnicianServices($user_id);

        include __DIR__ . '/../views/dashboard.php';
    }
}
?>