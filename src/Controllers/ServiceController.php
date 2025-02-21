<?php

namespace App\Controllers;

use App\Entities\Service;
use App\Entities\Auth;

class ServiceController
{
    public function updateServices()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['technician_id'])) {
            $service = new Service();
            $technician_id = $_POST['technician_id'];
            $selected_services = $_POST['services'] ?? [];

            $service->updateTechnicianServices($technician_id, $selected_services);

            $_SESSION['success_message'] = "Services mis à jour avec succès !";
            header("Location: /dashboard");
            exit();
        }
    }

    public function addService()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SESSION['role'] !== 'technician') {
            header("Location: /dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'];
            $type_service = $_POST['type_service'];
            $location = $_POST['location'];

            $service = new Service();
            $service->addService($user_id, $type_service, $location);

            header("Location: /dashboard");
            exit();
        }
    }
}
?>