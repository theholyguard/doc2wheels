<?php

namespace App\Controllers;

use App\Entities\Admin;
use App\Entities\Auth;

class AdminController
{
    private $performances;

    public function __construct() {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit();
        }

        $this->performances = new Admin();
    }

    private function getStatistics()
    {
        return [
            'totalRepairs' => $this->performances->getTotalRepairs(),
            'totalTechnicians' => $this->performances->getTotalTechnicians(),
            'totalUsers' => $this->performances->getTotalUsers(),
        ];
    }

    private function getUsers()
    {
        return $this->performances->getAllUsers();
    }

    private function getRepairs()
    {
        return $this->performances->getAllRepairs();
    }

    public function getHistory()
    {
        return $this->performances->getHistory();
    }

    public function getAllReviews()
    {
        return $this->performances->getAllReviews();
    }

    public function viewStatisticsPerformance()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_performance.php';
    }

    public function viewStatisticsUser()
    {
        $users = $this->getUsers();
        include __DIR__ . '/../views/admin_user.php';
    }
    

    public function viewStatisticsRepair()
    {
        $repairs = $this->getRepairs();
        include __DIR__ . '/../views/admin_repair.php';
    }


    public function viewStatisticsHistory()
    {
        $history = $this->performances->getHistory();
        include __DIR__ . '/../views/admin_history.php';
    }
        

    public function viewStatisticsReview()
    {
        $reviews = $this->performances->getAllReviews();
        include __DIR__ . '/../views/admin_review.php';
    }

    public function editUser($id) {
        $user = $this->performances->getUserById($id);
    
        // Traiter la soumission du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
    
            try {
                // Mettre à jour l'utilisateur
                $this->performances->updateUser($id, $name, $email, $role);
                header("Location: /admin/user"); // Rediriger vers la liste des utilisateurs
                exit();
            } catch (\Exception $e) {
                // Gérer l'exception et afficher un message d'erreur
                $errorMessage = $e->getMessage();
            }
        }
    
        include __DIR__ . '/../views/admin_edit_user.php';
    }
    
    
    public function viewDeleteUser($id) {
        $user = $this->performances->getUserById($id);
        if (!$user) {
            die("Erreur : utilisateur non trouvé.");
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->performances->deleteUser($id);
            header("Location: /users");
            exit();
        }
    
        include __DIR__ . '/../views/admin_delete_user.php';
    }
    
    
    
}
?>
