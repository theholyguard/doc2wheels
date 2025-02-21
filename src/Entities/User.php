<?php

namespace App\Entities;

use PDO;

class User {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createUser($name, $email, $password, $role = 'client') {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $verificationToken = bin2hex(random_bytes(16));
        
        $checkEmail = "SELECT id FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($checkEmail);
        $stmt->execute([':email' => $email]);
        
        if ($stmt->fetch()) {
            return "L'email est déjà utilisé.";
        }

        $sql = "INSERT INTO users (name, email, password, role, verification_token) VALUES (:name, :email, :password, :role, :verification_token)";
        $stmt = $this->pdo->prepare($sql);
        $success = $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role,
            ':verification_token' => $verificationToken
        ]);

        if ($success) {
            $this->sendVerificationEmail($email, $verificationToken);
            return true;
        } else {
            return "Erreur lors de l'inscription.";
        }
    }

    private function sendVerificationEmail($email, $token) {
        $mail = new Mail();
        $to = $email;
        $subject = "Validation de votre compte Doc2Wheels";
        $message = "Bonjour,\n\n" .
                   "Merci de vous être inscrit sur Doc2Wheels. Veuillez cliquer sur le lien ci-dessous pour valider votre compte :\n\n" .
                   "http://localhost/verify?token=$token\n\n" .
                   "Merci,\nDoc2Wheels";

        $mail->send($to, $subject, $message);
    }

    public function getUsers() {
        $sql = "SELECT * FROM users";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function loginUser($email, $password) {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            return "L'utilisateur avec cet email n'existe pas.";
        }

        if (!password_verify($password, $user['password'])) {
            return "Mot de passe incorrect.";
        }

        if (!$user['is_verified']) {
            return "Votre compte n'a pas encore été validé. Veuillez vérifier votre email.";
        }

        return $user;
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAddress($user_id, $address, $city, $postal_code) {
        $sql = "INSERT INTO addresses (user_id, address, city, postal_code) VALUES (:user_id, :address, :city, :postal_code)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':address' => $address,
            ':city' => $city,
            ':postal_code' => $postal_code
        ]);
    }

    public function getUserAddresses($user_id) {
        $sql = "SELECT * FROM addresses WHERE user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAddress($id) {
        $sql = "DELETE FROM addresses WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function updateUserInfo($id, $name, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':id' => $id
        ]);
    }

    public function verifyEmail($token) {
        $sql = "SELECT id FROM users WHERE verification_token = :token";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $sql = "UPDATE users SET is_verified = TRUE, verification_token = NULL WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([':id' => $user['id']]);
        } else {
            return "Token de validation invalide.";
        }
    }
}
?>
