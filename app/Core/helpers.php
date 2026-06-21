<?php
// app/Core/helpers.php

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function query_string(array $params = []): string
{
    $current = $_GET;
    foreach ($params as $key => $value) {
        $current[$key] = $value;
    }
    return http_build_query($current);
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function view(string $path, array $data = []): void
{
    extract($data);
    require __DIR__ . '/../Views/' . $path . '.php';
}

// Ghi log lỗi DB/Exception vào storage/logs/app.log thay vì hiển thị cho user
function log_error(string $message, ?Throwable $e = null): void
{
    $logFile = __DIR__ . '/../../storage/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $line = "[{$timestamp}] ERROR: {$message}";
    if ($e !== null) {
        $line .= ' | ' . get_class($e) . ': ' . $e->getMessage();
        $line .= ' in ' . $e->getFile() . ':' . $e->getLine();
    }
    $line .= PHP_EOL;
    @file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

// Sinh CSRF token và lưu vào session
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Render hidden input chứa CSRF token để nhúng vào form
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// Kiểm tra CSRF token gửi lên từ form có khớp với session không
function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
