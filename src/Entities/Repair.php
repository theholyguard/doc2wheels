<?php

namespace App\Entities;

use PDO;

class Repair {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createRepair($user_id, $type_service, $address_id, $technician_id, $vehicle_category_id, $price, $message) {
        $sql = "INSERT INTO repairs (user_id, type_service, address_id, technician_id, vehicle_category_id, price, message) 
                VALUES (:user_id, :type_service, :address_id, :technician_id, :vehicle_category_id, :price, :message)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':type_service' => $type_service,
            ':address_id' => $address_id,
            ':technician_id' => $technician_id,
            ':vehicle_category_id' => $vehicle_category_id,
            ':price' => $price,
            ':message' => $message
        ]);
    }

    public function getRepairs() {
        $sql = "SELECT r.*, u.name AS client_name, a.address, a.city, a.postal_code, v.name AS vehicle_category 
                FROM repairs r
                JOIN users u ON r.user_id = u.id
                JOIN addresses a ON r.address_id = a.id
                JOIN vehicle_categories v ON r.vehicle_category_id = v.id
                WHERE r.technician_id = :technician_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':technician_id' => $_SESSION['user_id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserRepairs($user_id) {
        $sql = "SELECT r.*, u.name AS technician_name, a.address, a.city, a.postal_code, v.name AS vehicle_category 
                FROM repairs r
                JOIN users u ON r.technician_id = u.id
                JOIN addresses a ON r.address_id = a.id
                JOIN vehicle_categories v ON r.vehicle_category_id = v.id
                WHERE r.user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRepairStatus($repair_id, $status) {
        $sql = "UPDATE repairs SET status = :status WHERE id = :repair_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':repair_id' => $repair_id
        ]);
    }

    public function deleteRepair($id) {
        $sql = "DELETE FROM repairs WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function addService($user_id, $type_service, $location) {
        $sql = "INSERT INTO repairs (user_id, type_service, location, status) VALUES (:user_id, :type_service, :location, 'en attente')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':type_service' => $type_service,
            ':location' => $location
        ]);
    }

    public function addReview($repair_id, $rating, $comment) {
        $sql = "INSERT INTO reviews (repair_id, rating, comment) VALUES (:repair_id, :rating, :comment)";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute([
            ':repair_id' => $repair_id,
            ':rating' => $rating,
            ':comment' => $comment
        ]);

        if ($result) {
            $sql = "UPDATE repairs SET reviewed = TRUE WHERE id = :repair_id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':repair_id' => $repair_id]);
        }

        return false;
    }

    public function getTechnicianAverageRating($technician_id) {
        $sql = "SELECT AVG(r.rating) as average_rating
                FROM reviews r
                JOIN repairs rp ON r.repair_id = rp.id
                WHERE rp.technician_id = :technician_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':technician_id' => $technician_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['average_rating'] : null;
    }

    public function getTechnicianReviews($technician_id) {
        $sql = "SELECT r.rating, r.comment, u.name AS client_name
                FROM reviews r
                JOIN repairs rp ON r.repair_id = rp.id
                JOIN users u ON rp.user_id = u.id
                WHERE rp.technician_id = :technician_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':technician_id' => $technician_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRepairReviews($repair_id) {
        $sql = "SELECT r.rating, r.comment, u.name AS client_name
                FROM reviews r
                JOIN repairs rp ON r.repair_id = rp.id
                JOIN users u ON rp.user_id = u.id
                WHERE r.repair_id = :repair_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':repair_id' => $repair_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRepairById($repair_id) {
        $sql = "SELECT r.*, u.name AS client_name, u.email AS client_email, t.name AS technician_name, t.email AS technician_email
                FROM repairs r
                JOIN users u ON r.user_id = u.id
                JOIN users t ON r.technician_id = t.id
                WHERE r.id = :repair_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':repair_id' => $repair_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
