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
        $vehicleCategories = $this->getVehicleCategories();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $selectedCategory = $_POST['category'];
            $selectedAddressId = $_POST['address_id'];
            $selectedVehicleCategoryId = $_POST['vehicle_category_id'];
            $message = $_POST['message'];
            $technicians = $service->findTechniciansByCategory($selectedCategory);

            foreach ($technicians as &$technician) {
                if (!isset($technician['address_id'])) {
                    error_log("Undefined address_id for technician ID: " . $technician['technician_id']);
                    continue;
                }
                $technicianAddress = $this->getAddressById($technician['address_id']);
                if (!$technicianAddress) {
                    error_log("Failed to retrieve address for technician ID: " . $technician['technician_id']);
                    continue;
                }
                $technician['address'] = $technicianAddress['address'];
                $technician['city'] = $technicianAddress['city'];
                $technician['postal_code'] = $technicianAddress['postal_code'];
                $priceDetails = $this->calculatePrice($selectedCategory, $selectedVehicleCategoryId, $selectedAddressId, $technician['address_id']);
                $technician['price'] = $priceDetails['totalPrice'];
                $technician['discount'] = $priceDetails['discount'];
            }

            // Pass the selected values to the view
            $selectedValues = [
                'selectedCategory' => $selectedCategory,
                'selectedAddressId' => $selectedAddressId,
                'selectedVehicleCategoryId' => $selectedVehicleCategoryId,
                'message' => $message,
                'technicians' => $technicians
            ];
        }

        if (isset($_GET['technician_id']) && isset($_GET['category']) && isset($_GET['address_id']) && isset($_GET['vehicle_category_id']) && isset($_GET['message'])) {
            $technician_id = $_GET['technician_id'];
            $category = $_GET['category'];
            $address_id = $_GET['address_id'];
            $vehicle_category_id = $_GET['vehicle_category_id'];
            $message = $_GET['message'];

            $price = $this->calculatePrice($category, $vehicle_category_id, $address_id, $technician_id);

            if ($repair->createRepair($user_id, $category, $address_id, $technician_id, $vehicle_category_id, $price, $message)) {
                $_SESSION['success_message'] = "Votre demande a bien été envoyée !";
                header("Location: /dashboard");
                exit();
            } else {
                $error = "Une erreur est survenue lors de l'envoi de la demande.";
            }
        }

        include __DIR__ . '/../views/request_repair.php';
    }

    private function getVehicleCategories() {
        $sql = "SELECT * FROM vehicle_categories";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function calculatePrice($serviceCategory, $vehicleCategoryId, $userAddressId, $technicianAddressId) {
        $servicePrice = $this->getServicePrice($serviceCategory);
        $vehiclePrice = $this->getVehiclePrice($vehicleCategoryId);
        $discount = $this->getDiscount($userAddressId, $technicianAddressId);

        $totalPrice = $servicePrice + $vehiclePrice;
        $totalPrice -= $totalPrice * $discount;

        return [
            'totalPrice' => $totalPrice,
            'discount' => $discount
        ];
    }

    private function getServicePrice($category) {
        $sql = "SELECT price FROM services WHERE category = :category";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':category' => $category]);
        $service = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $service ? $service['price'] : 0;
    }

    private function getVehiclePrice($vehicleCategoryId) {
        $sql = "SELECT price FROM vehicle_categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $vehicleCategoryId]);
        $vehicle = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $vehicle ? $vehicle['price'] : 0;
    }

    private function getDiscount($userAddressId, $technicianAddressId) {
        $userAddress = $this->getAddressById($userAddressId);
        $technicianAddress = $this->getAddressById($technicianAddressId);

        if (!$userAddress || !$technicianAddress) {
            error_log("Failed to retrieve addresses for discount calculation.");
            return 0;
        }

        // Debugging output
        error_log("User Address: " . print_r($userAddress, true));
        error_log("Technician Address: " . print_r($technicianAddress, true));

        if ($userAddress['city'] === $technicianAddress['city']) {
            return 0.30;
        } elseif (substr($userAddress['postal_code'], 0, 2) === substr($technicianAddress['postal_code'], 0, 2)) {
            return 0.15;
        } else {
            return 0;
        }
    }

    private function getAddressById($addressId) {
        $sql = "SELECT * FROM addresses WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $addressId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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