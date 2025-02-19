<?php
namespace App\Entities;

use PDO;

require_once 'Database.php';

class Service {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ✅ Récupérer tous les services disponibles
    public function getAllServices() {
        $sql = "SELECT * FROM services ORDER BY name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Récupérer les services sélectionnés par un technicien
    public function getTechnicianServices($technician_id) {
        $sql = "SELECT ts.service_id, s.name FROM technician_services ts 
                JOIN services s ON ts.service_id = s.id
                WHERE ts.technician_id = :technician_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':technician_id' => $technician_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Mettre à jour les services proposés par un technicien
    public function updateTechnicianServices($technician_id, $selected_services) {
        try {
            $this->pdo->beginTransaction();

            // Supprimer les services existants
            $sql = "DELETE FROM technician_services WHERE technician_id = :technician_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':technician_id' => $technician_id]);

            // Ajouter les nouveaux services
            $sql = "INSERT INTO technician_services (technician_id, service_id) VALUES (:technician_id, :service_id)";
            $stmt = $this->pdo->prepare($sql);
            foreach ($selected_services as $service_id) {
                $stmt->execute([':technician_id' => $technician_id, ':service_id' => $service_id]);
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // ✅ Trouver les techniciens proposant un service spécifique
    public function findTechniciansByService($service_id) {
        $sql = "SELECT u.id AS technician_id, u.name AS technician_name, u.location
                FROM technician_services ts
                JOIN users u ON ts.technician_id = u.id
                WHERE ts.service_id = :service_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':service_id' => $service_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Récupérer tous les services groupés par catégorie
    public function getAllServicesGroupedByCategory() {
        $sql = "SELECT * FROM services";
        $stmt = $this->pdo->query($sql);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedServices = [];
        foreach ($services as $service) {
            $groupedServices[$service['category']][] = $service;
        }

        return $groupedServices;
    }

    public function searchServices($query, $location) {
        $sql = "SELECT * FROM services WHERE name LIKE :query AND location LIKE :location";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':query' => '%' . $query . '%',
            ':location' => '%' . $location . '%'
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchServiceSuggestions($query) {
        $sql = "SELECT DISTINCT name FROM services WHERE LOWER(name) LIKE LOWER(:query) LIMIT 10";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Ajouter un service
    public function addService($user_id, $type_service, $location) {
        $sql = "INSERT INTO services (user_id, type_service, location) VALUES (:user_id, :type_service, :location)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':type_service' => $type_service,
            ':location' => $location
        ]);
    }
}
?>
