<?php

namespace App\Entities;

use PDO;

class Admin {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getTotalRepairs() {
        $sql = "SELECT COUNT(*) AS total_repairs FROM repairs";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total_repairs'];
    }

    public function getTotalTechnicians() {
        $sql = "SELECT COUNT(*) AS total_technicians FROM users WHERE role = 'technician'";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total_technicians'];
    }

    public function getTotalUsers() {
        $sql = "SELECT COUNT(*) AS total_users FROM users";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total_users'];
    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $users;
    }

    public function getAllRepairs()
    {
        $sql = "SELECT r.*, u.name AS client_name, t.name AS technician_name, v.name AS vehicle_name 
                FROM repairs r
                JOIN users u ON r.user_id = u.id
                JOIN users t ON r.technician_id = t.id
                JOIN vehicle_categories v ON r.vehicle_category_id = v.id
                ORDER BY r.created_at DESC"; 

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistory()
    {
        $sql = "SELECT r.*, 
                       u.name AS client_name, 
                       t.name AS technician_name, 
                       v.name AS vehicle_name 
                FROM repairs r
                JOIN users u ON r.user_id = u.id
                JOIN users t ON r.technician_id = t.id
                JOIN vehicle_categories v ON r.vehicle_category_id = v.id
                ORDER BY r.created_at DESC"; 
    
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getAllReviews()
    {
        $sql = "SELECT r.id, r.repair_id, r.rating, r.comment, r.created_at, 
                    rp.type_service, u.name AS user_name 
                FROM reviews r
                JOIN repairs rp ON r.repair_id = rp.id
                JOIN users u ON rp.user_id = u.id
                ORDER BY r.created_at DESC";
        
        $stmt = $this->pdo->query($sql);
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $reviews;
    }

    public function updateUser($id, $name, $email, $role) {    
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':role' => $role
        ]);
    }
    
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
    
        $stmt->execute([
            ':id' => $id
        ]);
    }
    
    

}
?>
