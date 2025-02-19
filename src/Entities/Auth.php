<?php

namespace App\Entities;

class Auth
{
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function isTechnician()
    {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'technician';
    }

    public static function redirectIfNotLoggedIn()
    {
        if (!self::isLoggedIn()) {
            header('Location: /login');
            exit();
        }
    }
}
?>