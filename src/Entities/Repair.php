<?php

namespace App\Entities;

use PDO;

class Repair {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createRepair($user_id, $type_service, $address_id, $technician_id) {
        $sql = "INSERT INTO repairs (user_id, type_service, address_id, technician_id) VALUES (:user_id, :type_service, :address_id, :technician_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':type_service' => $type_service,
            ':address_id' => $address_id,
            ':technician_id' => $technician_id
        ]);
    }

    public function getRepairs() {
        $sql = "SELECT repairs.*, users.name AS client_name, addresses.address, addresses.city, addresses.postal_code 
                FROM repairs 
                JOIN users ON repairs.user_id = users.id
                JOIN addresses ON repairs.address_id = addresses.id";
        $stmt = $this->pdo->query($sql);
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
    
    public function getUserRepairs($user_id) {
        $sql = "SELECT repairs.*, users.name AS client_name, addresses.address, addresses.city, addresses.postal_code 
                FROM repairs 
                JOIN users ON repairs.user_id = users.id
                JOIN addresses ON repairs.address_id = addresses.id
                WHERE repairs.user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
