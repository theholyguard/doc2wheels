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
}
?>
