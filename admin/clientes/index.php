<?php
// Subir dos niveles para llegar a la carpeta raíz del proyecto
require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';

// Modelos
require_once ROOT_PATH . '/app/models/Client.php';

session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}


// Crear instancia del modelo
$clientModel = new Client($pdo);
$clients = $clientModel->all();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clientes - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <h1>Listado de Clientes</h1>
    <a href="create.php">Agregar Cliente</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= $client['id'] ?></td>
                    <td><?= htmlspecialchars($client['name']) ?></td>
                    <td><?= htmlspecialchars($client['slug']) ?></td>
                    <td>
                        <a href="view.php?id=<?= $client['id'] ?>">Ver</a>
                        <a href="edit.php?id=<?= $client['id'] ?>">Editar</a>
                        <a href="delete.php?id=<?= $client['id'] ?>"
                            onclick="return confirm('¿Eliminar este cliente?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>