<?php
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Client.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: ' . BASE_URL);
    exit;
}

$clientModel = new Client($pdo);
$client = $clientModel->findBySlug($slug);

if (!$client || !$client['is_active']) {
    http_response_code(404);
    echo "Cliente no encontrado";
    exit;
}

require __DIR__ . '/view.php';
