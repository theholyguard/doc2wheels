<?php
namespace App\Entities;

use PDO;

require_once 'Database.php';

class Service {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function getAllServices() {
        $sql = "SELECT * FROM services ORDER BY category";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTechnicianServices($technician_id) {
        $sql = "SELECT ts.service_id, s.category FROM technician_services ts 
                JOIN services s ON ts.service_id = s.id
                WHERE ts.technician_id = :technician_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':technician_id' => $technician_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTechnicianServices($technician_id, $selected_services) {
        try {
            $this->pdo->beginTransaction();

            $sqlDelete = "DELETE FROM technician_services WHERE technician_id = :technician_id";
            $stmt = $this->pdo->prepare($sqlDelete);
            $stmt->execute([':technician_id' => $technician_id]);

            if (!empty($selected_services)) {
                $sqlInsert = "INSERT INTO technician_services (technician_id, service_id) VALUES (:technician_id, :service_id)";
                $stmt = $this->pdo->prepare($sqlInsert);
                foreach ($selected_services as $service_id) {
                    $stmt->execute([':technician_id' => $technician_id, ':service_id' => $service_id]);
                }
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findTechniciansByService($service_id) {
        $sql = "SELECT u.id AS technician_id, u.name AS technician_name, a.address, a.city, a.postal_code
                FROM technician_services ts
                JOIN users u ON ts.technician_id = u.id
                JOIN addresses a ON u.id = a.user_id
                WHERE ts.service_id = :service_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':service_id' => $service_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllServicesGroupedByCategory() {
        $sql = "SELECT * FROM services";
        $stmt = $this->pdo->query($sql);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedServices = [];
        foreach ($services as $service) {
            $category = $service['category'] ?? 'Autre';
            $groupedServices[$category][] = $service;
        }

        return $groupedServices;
    }

    public function searchServices($query, $location) {
        $sql = "SELECT * FROM services WHERE category LIKE :query AND location LIKE :location";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':query' => '%' . $query . '%',
            ':location' => '%' . $location . '%'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchServiceSuggestions($query) {
        $sql = "SELECT DISTINCT category FROM services WHERE LOWER(category) LIKE LOWER(:query) LIMIT 10";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addService($user_id, $type_service, $location) {
        $sql = "INSERT INTO services (user_id, type_service, location) VALUES (:user_id, :type_service, :location)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':type_service' => $type_service,
            ':location' => $location
        ]);
    }

    public function findTechniciansByCategory($category) {
        $sql = "SELECT u.id AS technician_id, u.name AS technician_name, a.address, a.city, a.postal_code
                FROM technician_services ts
                JOIN services s ON ts.service_id = s.id
                JOIN users u ON ts.technician_id = u.id
                JOIN addresses a ON u.id = a.user_id
                WHERE s.category = :category";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':category' => $category]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
