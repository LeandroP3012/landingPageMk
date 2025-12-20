<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}


require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/Client.php';

$id = $_GET['id'] ?? null;
$clientModel = new Client($pdo);
$clientModel->delete($id);

header('Location: index.php');
exit;
