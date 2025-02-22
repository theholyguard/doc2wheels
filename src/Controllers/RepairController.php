<?php

namespace App\Controllers;

use App\Entities\Repair;
use App\Entities\Service;
use App\Entities\Auth;
use App\Entities\Database;
use App\Entities\User;
use App\Entities\Mail;

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

            $technicianData = [];
            foreach ($technicians as $technician) {
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
                $technician['average_rating'] = $repair->getTechnicianAverageRating($technician['technician_id']);
                $technicianData[] = $technician;
                error_log("Technician Data: " . print_r($technician, true));
            }

            $selectedValues = [
                'selectedCategory' => $selectedCategory,
                'selectedAddressId' => $selectedAddressId,
                'selectedVehicleCategoryId' => $selectedVehicleCategoryId,
                'message' => $message,
                'technicians' => $technicianData
            ];
            error_log("Selected Values: " . print_r($selectedValues, true));
        }

        if (isset($_GET['technician_id']) && isset($_GET['category']) && isset($_GET['address_id']) && isset($_GET['vehicle_category_id']) && isset($_GET['message']) && isset($_GET['price'])) {
            $technician_id = $_GET['technician_id'];
            $category = $_GET['category'];
            $address_id = $_GET['address_id'];
            $vehicle_category_id = $_GET['vehicle_category_id'];
            $message = $_GET['message'];
            $price = $_GET['price'];

            if ($repair->createRepair($user_id, $category, $address_id, $technician_id, $vehicle_category_id, $price, $message)) {
                $this->sendRepairCreatedEmail($user_id, $technician_id);
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $repair_id = $_POST['repair_id'];
            $status = $_POST['status'];

            $repair = new Repair();
            $repair->updateRepairStatus($repair_id, $status);

            $repairDetails = $repair->getRepairById($repair_id);
            if ($status === 'annulé' || $status === 'terminé') {
                $this->sendStatusUpdateEmailToTechnician($repairDetails);
            } else if ($status === 'en cours' || $status === 'refusé') {
                $this->sendStatusUpdateEmailToClient($repairDetails);
            }

            header("Location: /dashboard");
            exit();
        }
    }

    public function addReview()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $repair_id = $_POST['repair_id'];
            $rating = $_POST['rating'];
            $comment = $_POST['comment'];

            $repair = new Repair();
            if ($repair->addReview($repair_id, $rating, $comment)) {
                $repairDetails = $repair->getRepairById($repair_id);
                $this->sendReviewEmailToTechnician($repairDetails);
                $_SESSION['success_message'] = "Votre avis a été ajouté avec succès !";
            } else {
                $_SESSION['error_message'] = "Une erreur est survenue lors de l'ajout de l'avis.";
            }

            header("Location: /dashboard");
            exit();
        }
    }

    private function sendRepairCreatedEmail($user_id, $technician_id) {
        $user = new User();
        $client = $user->getUserById($user_id);
        $technician = $user->getUserById($technician_id);

        $mail = new Mail();
        $to = $technician['email'];
        $subject = "Nouvelle demande de réparation";
        $message = "Bonjour " . $technician['name'] . ",\n\n" .
                   "Une nouvelle demande de réparation a été créée par " . $client['name'] . ".\n\n" .
                   "Merci,\nDoc2Wheels";

        $mail->send($to, $subject, $message);
    }

    private function sendStatusUpdateEmailToTechnician($repairDetails) {
        $user = new User();
        $technician = $user->getUserById($repairDetails['technician_id']);

        $mail = new Mail();
        $to = $technician['email'];
        $subject = "Mise à jour du statut de la réparation";
        $message = "Bonjour " . $technician['name'] . ",\n\n" .
                   "Le statut de la réparation pour " . $repairDetails['type_service'] . " a été mis à jour en " . $repairDetails['status'] . ".\n\n" .
                   "Merci,\nDoc2Wheels";

        $mail->send($to, $subject, $message);
    }

    private function sendStatusUpdateEmailToClient($repairDetails) {
        $user = new User();
        $client = $user->getUserById($repairDetails['user_id']);

        $mail = new Mail();
        $to = $client['email'];
        $subject = "Mise à jour du statut de la réparation";
        $message = "Bonjour " . $client['name'] . ",\n\n" .
                   "Le statut de votre réparation pour " . $repairDetails['type_service'] . " a été mis à jour en " . $repairDetails['status'] . ".\n\n" .
                   "Merci,\nDoc2Wheels";

        $mail->send($to, $subject, $message);
    }

    private function sendReviewEmailToTechnician($repairDetails) {
        $user = new User();
        $technician = $user->getUserById($repairDetails['technician_id']);

        $mail = new Mail();
        $to = $technician['email'];
        $subject = "Nouvel avis sur une réparation";
        $message = "Bonjour " . $technician['name'] . ",\n\n" .
                   "Un nouvel avis a été laissé pour la réparation " . $repairDetails['type_service'] . ".\n\n" .
                   "Merci,\nDoc2Wheels";

        $mail->send($to, $subject, $message);
    }
}
?>