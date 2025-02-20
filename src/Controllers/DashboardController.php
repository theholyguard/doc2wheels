<?php

namespace App\Controllers;

use App\Entities\Repair;
use App\Entities\Service;
use App\Entities\Auth;
use App\Entities\User;

class DashboardController
{
    public function index()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        $repair = new Repair();
        $service = new Service();
        $user = new User();
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        $userInfo = $user->getUserById($user_id);
        $userName = $userInfo['name'];

        $repairs = ($role === 'technician') ? $repair->getRepairs() : $repair->getUserRepairs($user_id);
        error_log("Repairs Data: " . print_r($repairs, true));

        $allServicesByCategory = $service->getAllServicesGroupedByCategory();

        $technicianServices = $service->getTechnicianServices($user_id);

        $userAddresses = $user->getUserAddresses($user_id);

        include __DIR__ . '/../views/dashboard.php';
    }

    public function addAddress()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user_id = $_SESSION['user_id'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $postal_code = $_POST['postal_code'];

            if ($user->addAddress($user_id, $address, $city, $postal_code)) {
                $_SESSION['success_message'] = "Adresse ajoutée avec succès !";
            } else {
                $_SESSION['error_message'] = "Une erreur est survenue lors de l'ajout de l'adresse.";
            }

            header("Location: /dashboard");
            exit();
        }
    }

    public function deleteAddress()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $address_id = $_POST['address_id'];

            if ($user->deleteAddress($address_id)) {
                $_SESSION['success_message'] = "Adresse supprimée avec succès !";
            } else {
                $_SESSION['error_message'] = "Une erreur est survenue lors de la suppression de l'adresse.";
            }

            header("Location: /dashboard");
            exit();
        }
    }

    public function updateUserInfo()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User();
            $user_id = $_SESSION['user_id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($user->updateUserInfo($user_id, $name, $email, $password)) {
                $_SESSION['success_message'] = "Informations mises à jour avec succès !";
            } else {
                $_SESSION['error_message'] = "Une erreur est survenue lors de la mise à jour des informations.";
            }

            header("Location: /dashboard");
            exit();
        }
    }
}
?>