<?php

if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use App\Controllers\OrderController;
use App\Controllers\SearchController;
use App\Controllers\InvoiceController;
use App\Controllers\AuthController;

header('Content-Type: application/json');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$orderController = new OrderController();
$searchController = new SearchController();
$invoiceController = new InvoiceController();
$authController = new AuthController();

if ($uri === '/' || $uri === '') {
    echo json_encode([
        'status' => 'online',
        'service' => 'TokoLaris Backend API',
        'version' => '1.0.0-legacy',
        'dashboard' => '/papan'
    ]);
    exit;
}

// Basic Legacy Routing
if ($uri === '/api/v1/orders/checkout' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $response = $orderController->checkout($input);
    http_response_code($response['code'] ?? 200);
    echo json_encode($response);
    exit;
}

if ($uri === '/api/v1/orders' && $method === 'GET') {
    $response = $orderController->listOrders();
    echo json_encode($response);
    exit;
}

if ($uri === '/api/v1/products/search' && $method === 'GET') {
    $keyword = $_GET['q'] ?? '';
    $response = $searchController->search($keyword);
    echo json_encode(['data' => $response]);
    exit;
}

if ($uri === '/api/v1/auth/login' && $method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    $response = $authController->login($input['email'] ?? '', $input['password'] ?? '');
    http_response_code($response['code'] ?? 200);
    echo json_encode($response);
    exit;
}

if (str_starts_with($uri, '/api/v1/invoices/') && $method === 'GET') {
    $parts = explode('/', trim($uri, '/'));
    $invoiceId = (int)($parts[3] ?? 0);
    $currentUserId = (int)($_SERVER['HTTP_X_USER_ID'] ?? 1);
    $response = $invoiceController->download($invoiceId, $currentUserId);
    http_response_code($response['code'] ?? 200);
    echo json_encode($response);
    exit;
}

http_response_code(404);
echo json_encode(['status' => 'error', 'message' => 'Endpoint tidak ditemukan']);
