<?php
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

            // Supprimer les services précédemment enregistrés
            $sqlDelete = "DELETE FROM technician_services WHERE technician_id = :technician_id";
            $stmt = $this->pdo->prepare($sqlDelete);
            $stmt->execute([':technician_id' => $technician_id]);

            // Ajouter les nouveaux services sélectionnés
            if (!empty($selected_services)) {
                $sqlInsert = "INSERT INTO technician_services (technician_id, service_id) VALUES (:technician_id, :service_id)";
                $stmt = $this->pdo->prepare($sqlInsert);
                foreach ($selected_services as $service_id) {
                    $stmt->execute([':technician_id' => $technician_id, ':service_id' => $service_id]);
                }
            }

            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log("Erreur lors de la mise à jour des services du technicien: " . $e->getMessage());
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
        $sql = "SELECT category, id, name FROM services ORDER BY category, name";
        $stmt = $this->pdo->query($sql);
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $groupedServices = [];

        foreach ($services as $service) {
            // S'assurer que la catégorie existe, sinon assigner "Autre"
            $category = $service['category'] ?? 'Autre';
            $groupedServices[$category][] = $service;
        }

        return $groupedServices;
    }

    public function searchServices($query, $location) {
        $sql = "SELECT s.id, s.name, u.location 
                FROM technician_services ts
                JOIN services s ON ts.service_id = s.id
                JOIN users u ON ts.technician_id = u.id
                WHERE LOWER(s.name) LIKE LOWER(:query) 
                AND LOWER(u.location) LIKE LOWER(:location)
                ORDER BY u.location";
    
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
    
}
?>
