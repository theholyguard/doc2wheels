<?php
require_once 'Database.php';

class Repair {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ✅ CREATE : Ajouter une demande de réparation
    public function createRepair($user_id, $type_service, $location) {
        $sql = "INSERT INTO repairs (user_id, type_service, location) VALUES (:user_id, :type_service, :location)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':type_service' => $type_service,
            ':location' => $location
        ]);
    }

    // ✅ READ : Récupérer toutes les demandes de réparation
    public function getRepairs() {
        $sql = "SELECT repairs.*, users.name AS client_name 
                FROM repairs 
                JOIN users ON repairs.user_id = users.id";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ UPDATE : Modifier le statut d'une réparation
    public function updateRepairStatus($id, $status) {
        $sql = "UPDATE repairs SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }

    // ✅ DELETE : Supprimer une demande de réparation
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
    
    // ✅ Récupérer les demandes de réparation d'un utilisateur spécifique
public function getUserRepairs($user_id) {
    $sql = "SELECT repairs.*, users.name AS client_name 
            FROM repairs 
            JOIN users ON repairs.user_id = users.id
            WHERE repairs.user_id = :user_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
