<?php
// app/Controllers/HealthController.php

class HealthController
{
    public function index(): void
    {
        header('Content-Type: application/json');
        try {
            $config = require __DIR__ . '/../../config/database.php';
            $pdo = (new Database($config))->getConnection();
            $pdo->query('SELECT 1');
            echo json_encode([
                'status' => 'ok',
                'database' => 'connected',
                'app' => 'Mini Course Enrollment DB App',
            ]);
        } catch (Exception $e) {
            log_error('HealthController DB check failed', $e);
            http_response_code(500);
            echo json_encode(['status' => 'error', 'database' => 'failed']);
        }
    }
}
