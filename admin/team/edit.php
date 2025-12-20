<?php
/* session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
} */

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/Team.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

$teamModel = new Team($pdo);
$member = $teamModel->find($id);

if (!$member) {
    echo "Miembro no encontrado";
    exit;
}

/* =====================
   PROCESAR FORMULARIO
   ===================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $sort_order = (int) ($_POST['sort_order'] ?? 0);

    // Imagen actual
    $photo = $member['photo'];

    $uploadPath = ROOT_PATH . '/storage/uploads/team/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Subir nueva imagen si existe
    if (!empty($_FILES['photo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($ext, $allowed)) {
            // Borrar imagen anterior
            if (!empty($photo) && file_exists($uploadPath . $photo)) {
                unlink($uploadPath . $photo);
            }

            $photo = uniqid('team_') . '.' . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath . $photo);
        }
    }

    // Actualizar
    $teamModel->update($id, $name, $role, $photo, $sort_order);

    header('Location: index.php');
    exit;
}
?>

<h1>Editar Miembro del Equipo</h1>

<form method="POST" enctype="multipart/form-data">

    <label>Nombre</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($member['name']) ?>" required><br><br>

    <label>Cargo / Rol</label><br>
    <input type="text" name="role" value="<?= htmlspecialchars($member['role']) ?>" required><br><br>

    <label>Orden</label><br>
    <input type="number" name="sort_order" value="<?= (int) $member['sort_order'] ?>"><br><br>

    <label>Foto actual</label><br>
    <?php if (!empty($member['photo'])): ?>
        <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $member['photo'] ?>" width="120"
            style="display:block;margin-bottom:10px;">
    <?php endif; ?>

    <label>Nueva foto (opcional)</label><br>
    <input type="file" name="photo" accept="image/*"><br><br>

    <button type="submit">Guardar cambios</button>

</form>