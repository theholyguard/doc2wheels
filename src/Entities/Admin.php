<?php

namespace App\Entities;

use PDO;

class Admin
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance()->getConnection();
    }

    //------------------Performance

    public function getTotalRepairs()
    {
        $sql = "SELECT COUNT(*) AS total_repairs FROM repairs";
        $exec = $this->pdo->query($sql);
        $result = $exec->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total_repairs'];
    }

    public function getTotalTechnicians()
    {
        $sql = "SELECT COUNT(*) AS total_technicians FROM users WHERE role = 'technician'";
        $exec = $this->pdo->query($sql);
        $result = $exec->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total_technicians'];
    }

    public function getTotalUsers()
    {
        $sql = "SELECT COUNT(*) AS total_users FROM users";
        $exec = $this->pdo->query($sql);
        $result = $exec->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total_users'];
    }

    //------------------Performance

    //------------------User

    public function getAllUsers()
    {
        $sql = "SELECT * FROM users";
        $exec = $this->pdo->query($sql);
        $users = $exec->fetchAll(PDO::FETCH_ASSOC);

        return $users;
    }

    public function updateUser($id, $name, $email, $role)
    {
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $exec = $this->pdo->prepare($sql);

        $exec->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':role' => $role
        ]);
    }

    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $exec = $this->pdo->prepare($sql);

        $exec->execute([
            ':id' => $id
        ]);
    }

    //------------------User

    //------------------Service

    public function getAllServices()
    {
        $sql = "SELECT * FROM services";

        $exec = $this->pdo->query($sql);
        return $exec->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createService($category, $price)
    {
        $sql = "INSERT INTO services (category, price) VALUES (:category, :price)";
        $exec = $this->pdo->prepare($sql);

        $exec->execute([
            ':category' => $category,
            ':price' => $price
        ]);
    }

    public function editService($id, $category, $price)
    {
        $sql = "UPDATE services SET category = :category, price = :price WHERE id = :id";
        $exec = $this->pdo->prepare($sql);

        $exec->execute([
            ':id' => $id,
            ':category' => $category,
            ':price' => $price
        ]);
    }

    public function deleteService($id)
    {
        $sql = "DELETE FROM services WHERE id = :id";
        $exec = $this->pdo->prepare($sql);

        $exec->execute([
            ':id' => $id
        ]);
    }

    //------------------Service

    //------------------History

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

        $exec = $this->pdo->query($sql);
        return $exec->fetchAll(PDO::FETCH_ASSOC);
    }

    //------------------History

    //------------------Review

    public function getAllReviews()
    {
        $sql = "SELECT r.id, r.repair_id, r.rating, r.comment, r.created_at, 
                    rp.type_service, u.name AS user_name 
                FROM reviews r
                JOIN repairs rp ON r.repair_id = rp.id
                JOIN users u ON rp.user_id = u.id
                ORDER BY r.created_at DESC";

        $exec = $this->pdo->query($sql);
        $reviews = $exec->fetchAll(PDO::FETCH_ASSOC);

        return $reviews;
    }

    public function deleteReview($id)
    {
        $sql = "DELETE FROM reviews WHERE id = :id";
        $exec = $this->pdo->prepare($sql);

        $exec->execute([
            ':id' => $id
        ]);
    }

    //------------------Review

}
?>