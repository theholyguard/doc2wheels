<?php

use App\Http\Request;
use App\Http\Router;
use App\Http\Response;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\RepairController;
use App\Controllers\DashboardController;
use App\Controllers\ServiceController;
use App\Controllers\SearchController;
use App\Controllers\AdminController;

require_once __DIR__ . '/../vendor/autoload.php';

$request = new Request();

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/request_repair', [RepairController::class, 'requestRepair']);
$router->post('/request_repair', [RepairController::class, 'requestRepair']);
$router->post('/update_repair', [RepairController::class, 'updateRepair']);
$router->post('/add_review', [RepairController::class, 'addReview']); // Ajout de la route pour ajouter un avis
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->post('/update_services', [ServiceController::class, 'updateServices']);
$router->post('/add_service', [ServiceController::class, 'addService']);
$router->get('/search_results', [SearchController::class, 'searchResults']);
$router->post('/add_address', [DashboardController::class, 'addAddress']);
$router->post('/delete_address', [DashboardController::class, 'deleteAddress']);
$router->post('/update_user_info', [DashboardController::class, 'updateUserInfo']); 

$router->get('/admin/performance', [AdminController::class, 'viewStatistics']);

$response = $router->route($request);

$response->send();