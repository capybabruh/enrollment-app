<?php
// public/index.php
session_start();

require __DIR__ . '/../app/Core/helpers.php';
require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Database.php';
require __DIR__ . '/../app/Core/DuplicateRecordException.php';

require __DIR__ . '/../app/Repositories/StudentRepository.php';
require __DIR__ . '/../app/Repositories/EnrollmentRepository.php';

require __DIR__ . '/../app/Controllers/HomeController.php';
require __DIR__ . '/../app/Controllers/HealthController.php';
require __DIR__ . '/../app/Controllers/StudentController.php';
require __DIR__ . '/../app/Controllers/EnrollmentController.php';

$router = new Router();

$router->get('/', [HomeController::class, 'index']);
$router->get('/health', [HealthController::class, 'index']);

$router->get('/students', [StudentController::class, 'index']);
$router->get('/students/create', [StudentController::class, 'create']);
$router->post('/students/store', [StudentController::class, 'store']);
$router->get('/students/edit', [StudentController::class, 'edit']);
$router->post('/students/update', [StudentController::class, 'update']);
$router->post('/students/delete', [StudentController::class, 'delete']);

$router->get('/enrollments', [EnrollmentController::class, 'index']);
$router->get('/enrollments/create', [EnrollmentController::class, 'create']);
$router->post('/enrollments/store', [EnrollmentController::class, 'store']);
$router->get('/enrollments/edit', [EnrollmentController::class, 'edit']);
$router->post('/enrollments/update', [EnrollmentController::class, 'update']);
$router->post('/enrollments/delete', [EnrollmentController::class, 'delete']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
