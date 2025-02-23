<?php

namespace App\Controllers;

use App\Entities\Admin;
use App\Entities\Auth;

class AdminController
{
    private $performances;

    public function __construct()
    {
        session_start();
        Auth::redirectIfNotLoggedIn();

        if ($_SESSION['role'] !== 'admin') {
            header("Location: /dashboard");
            exit();
        }

        $this->performances = new Admin();
    }

    //Performance

    private function getStatistics()
    {
        return [
            'totalRepairs' => $this->performances->getTotalRepairs(),
            'totalTechnicians' => $this->performances->getTotalTechnicians(),
            'totalUsers' => $this->performances->getTotalUsers(),
        ];
    }

    public function viewStatisticsPerformance()
    {
        $stats = $this->getStatistics();
        extract($stats);
        include __DIR__ . '/../views/admin_performance.php';
    }

    //Performance

    //User

    private function getUsers()
    {
        return $this->performances->getAllUsers();
    }

    public function viewStatisticsUser()
    {
        $users = $this->getUsers();
        include __DIR__ . '/../views/admin_user.php';
    }

    public function editUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $this->performances->updateUser($id, $name, $email, $role);
            header("Location: /admin/user");
            exit();
        }

    }

    public function deleteUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->performances->deleteUser($id);
            header("Location: /admin/user");
            exit();
        }
    }

    //Service

    private function getServices()
    {
        return $this->performances->getAllServices();
    }

    public function viewStatisticsService()
    {
        $services = $this->getServices();
        include __DIR__ . '/../views/admin_service.php';
    }

    public function createService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $category = $_POST['category'];
            $price = $_POST['price'];
            $this->performances->createService($category, $price);
            header("Location: /admin/service");
            exit();
        }
        include __DIR__ . '/../views/admin_service_create.php';
    }

    public function editService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            try {
                $this->performances->editService($id, $category, $price);
                header("Location: /admin/service");
                exit();
            } catch (\Exception $e) {
                $error = $e->getMessage();
                include __DIR__ . '/../views/admin_service_edit.php';
            }
        }
    }

    public function deleteService()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->performances->deleteService($id);
            header("Location: /admin/service");
            exit();
        }
    }

    //Service

    //History

    public function getHistory()
    {
        return $this->performances->getHistory();
    }

    public function viewStatisticsHistory()
    {
        $history = $this->performances->getHistory();
        include __DIR__ . '/../views/admin_history.php';
    }

    //History

    //Avisclient

    public function getAllReviews()
    {
        return $this->performances->getAllReviews();
    }

    public function viewStatisticsReview()
    {
        $reviews = $this->performances->getAllReviews();
        include __DIR__ . '/../views/admin_review.php';
    }

    public function editReview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            $this->performances->updateUser($id, $name, $email, $role);
            header("Location: /admin/user");
            exit();
        }

    }

    public function deleteReview()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $this->performances->deleteUser($id);
            header("Location: /admin/user");
            exit();
        }
    }

    //Avisclient

}
?>