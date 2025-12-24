<?php
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/ClientLogo.php';

session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

$id = (int) $_GET['id'];
$clientId = (int) $_GET['client_id'];

$model = new ClientLogo($pdo);
$model->delete($id);

header("Location: logos.php?client_id=$clientId");
exit;
