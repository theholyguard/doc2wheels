<?php
require_once 'Database.php';

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // ✅ CREATE : Ajouter un utilisateur avec gestion du rôle
    public function createUser($name, $email, $password, $role = 'client') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Vérifier si l'email existe déjà
        $checkEmail = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($checkEmail);
        $stmt->execute([':email' => $email]);
        
        if ($stmt->fetch()) {
            return "L'email est déjà utilisé.";
        }

        // Insérer le nouvel utilisateur
        $sql = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);

        return $success ? true : "Erreur lors de l'inscription.";
    }

    // ✅ READ : Récupérer tous les utilisateurs
    public function getUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ UPDATE : Modifier un utilisateur (nom, email, rôle)
    public function updateUser($id, $name, $email, $role) {
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':id' => $id
        ]);
    }

    // ✅ DELETE : Supprimer un utilisateur
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // ✅ AUTH : Connexion utilisateur
    public function loginUser($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            return "L'utilisateur avec cet email n'existe pas.";
        }

        if (password_verify($password, $user['password'])) {
            return $user;
        } else {
            return "Mot de passe incorrect.";
        }
    }
}
?>
