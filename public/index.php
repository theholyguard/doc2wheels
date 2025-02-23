<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
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

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

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
$router->post('/add_review', [RepairController::class, 'addReview']);
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->post('/update_services', [ServiceController::class, 'updateServices']);
$router->post('/add_service', [ServiceController::class, 'addService']);
$router->post('/add_address', [DashboardController::class, 'addAddress']);
$router->post('/delete_address', [DashboardController::class, 'deleteAddress']);
$router->post('/update_user_info', [DashboardController::class, 'updateUserInfo']);
$router->get('/admin/', [AdminController::class, 'viewStatisticsPerformance']);
$router->get('/admin/performance', [AdminController::class, 'viewStatisticsPerformance']);
$router->get('/admin/user', [AdminController::class, 'viewStatisticsUser']);
$router->post('/admin/user', [AdminController::class, 'editUser']);
$router->post('/admin/user/delete', [AdminController::class, 'deleteUser']);



$router->get('/admin/service', [AdminController::class, 'viewStatisticsService']);
$router->post('/admin/service', [AdminController::class, 'editService']);
$router->get('/admin/service/create', [AdminController::class, 'createService']);
$router->post('/admin/service/create', [AdminController::class, 'createService']);
$router->post('/admin/service/delete', [AdminController::class, 'deleteService']);


$router->get('/admin/history', [AdminController::class, 'viewStatisticsHistory']);
$router->get('/admin/review', [AdminController::class, 'viewStatisticsReview']);
$router->get('/verify', [AuthController::class, 'verifyEmail']);

$response = $router->route($request);

$response->send();