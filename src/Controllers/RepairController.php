<?php

namespace App\Controllers;

use App\Entities\Repair;
use App\Entities\Service;
use App\Entities\Auth;

class RepairController
{
    public function requestRepair()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        $repair = new Repair();
        $service = new Service();
        $user_id = $_SESSION['user_id'];
        $allServicesByCategory = $service->getAllServicesGroupedByCategory();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $type_service = $_POST['type_service'];
            $location = $_POST['location'];

            if ($repair->createRepair($user_id, $type_service, $location)) {
                $_SESSION['success_message'] = "Votre demande a bien été envoyée !";
                header("Location: /dashboard");
                exit();
            } else {
                $error = "Une erreur est survenue lors de l'envoi de la demande.";
            }
        }

        include __DIR__ . '/../views/request_repair.php';
    }

    public function updateRepair()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SESSION['role'] !== 'technician') {
            header("Location: /dashboard");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $repair_id = $_POST['repair_id'];
            $status = $_POST['status'];

            $repair = new Repair();
            $repair->updateRepairStatus($repair_id, $status);

            header("Location: /dashboard");
            exit();
        }
    }
}
?>