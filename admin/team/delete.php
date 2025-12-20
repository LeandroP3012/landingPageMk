<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/Team.php';
require_once __DIR__ . '/../../app/config/app.php';

$id = $_GET['id'] ?? null;
if (!$id)
    exit;

$teamModel = new Team($pdo);
$teamModel->delete($id);

header('Location: index.php');
exit;
