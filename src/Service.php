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
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Récupérer les services sélectionnés par un technicien
    public function getTechnicianServices($technician_id) {
        $sql = "SELECT service_id FROM technician_services WHERE technician_id = :technician_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':technician_id' => $technician_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Mettre à jour les services proposés par un technicien
    public function updateTechnicianServices($technician_id, $selected_services) {
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
}
?>
