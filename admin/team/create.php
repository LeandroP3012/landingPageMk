<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/Team.php';

$teamModel = new Team($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $role = $_POST['role'];
    $order = $_POST['sort_order'] ?? 0;

    $photo = '';
    if (!empty($_FILES['photo']['name'])) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = uniqid() . '.' . $ext;
        move_uploaded_file(
            $_FILES['photo']['tmp_name'],
            __DIR__ . '/../../storage/uploads/team/' . $photo
        );
    }

    $teamModel->create($name, $role, $photo, $order);
    header('Location: index.php');
    exit;
}
?>

<h1>Agregar miembro</h1>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Nombre" required><br>
    <input type="text" name="role" placeholder="Cargo" required><br>
    <input type="number" name="sort_order" placeholder="Orden" value="0"><br>
    <input type="file" name="photo" accept="image/*" required><br>
    <button type="submit">Guardar</button>
</form>