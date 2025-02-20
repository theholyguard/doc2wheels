<?php

namespace App\Controllers;

use App\Entities\Repair;
use App\Entities\Service;
use App\Entities\Auth;
use App\Entities\Database;
use App\Entities\User;

class RepairController
{
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function requestRepair()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        $repair = new Repair();
        $service = new Service();
        $user = new User();
        $user_id = $_SESSION['user_id'];
        $allServicesByCategory = $service->getAllServicesGroupedByCategory();
        $userAddresses = $user->getUserAddresses($user_id);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedCategory = $_POST['category'];
            $selectedAddressId = $_POST['address_id'];
            $technicians = $service->findTechniciansByCategory($selectedCategory);
        }

        if (isset($_GET['technician_id']) && isset($_GET['category']) && isset($_GET['address_id'])) {
            $technician_id = $_GET['technician_id'];
            $category = $_GET['category'];
            $address_id = $_GET['address_id'];

            if ($repair->createRepair($user_id, $category, $address_id, $technician_id)) {
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