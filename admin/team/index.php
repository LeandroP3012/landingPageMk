<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once ROOT_PATH . '/app/models/Team.php';

$teamModel = new Team($pdo);
$team = $teamModel->all();
?>

<h1>Equipo</h1>

<a href="cover.php" style="margin-right:15px;">⚙️ Portada del equipo</a>
<a href="create.php">+ Agregar miembro</a>

<table border="1" cellpadding="10">
    <tr>
        <th>Foto</th>
        <th>Nombre</th>
        <th>Cargo</th>
        <th>Orden</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($team as $member): ?>
        <tr>
            <td>
                <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $member['photo'] ?>" width="80">
            </td>
            <td><?= htmlspecialchars($member['name']) ?></td>
            <td><?= htmlspecialchars($member['role']) ?></td>
            <td><?= $member['sort_order'] ?></td>
            <td>
                <a href="edit.php?id=<?= $member['id'] ?>">Editar</a> |
                <a href="delete.php?id=<?= $member['id'] ?>" onclick="return confirm('¿Eliminar miembro?')">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>