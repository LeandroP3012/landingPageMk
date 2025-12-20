<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/TeamCover.php';

$model = new TeamCover($pdo);
$cover = $model->get();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Si no hay fila, la creamos primero
    if (!$cover) {
        $model->create(null, null);
        $cover = $model->get();
    }

    $imageTop = $cover['image_top'];
    $imageBottom = $cover['image_bottom'];

    // Imagen superior
    if (isset($_FILES['image_top']) && $_FILES['image_top']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image_top']['name'], PATHINFO_EXTENSION);
        $imageTop = uniqid('top_') . '.' . $ext;

        move_uploaded_file(
            $_FILES['image_top']['tmp_name'],
            ROOT_PATH . '/storage/uploads/team/' . $imageTop
        );
    }

    // Imagen inferior
    if (isset($_FILES['image_bottom']) && $_FILES['image_bottom']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image_bottom']['name'], PATHINFO_EXTENSION);
        $imageBottom = uniqid('bottom_') . '.' . $ext;

        move_uploaded_file(
            $_FILES['image_bottom']['tmp_name'],
            ROOT_PATH . '/storage/uploads/team/' . $imageBottom
        );
    }

    $model->update($imageTop, $imageBottom);

    header('Location: cover.php');
    exit;
}

?>

<h1>Portada del Equipo</h1>

<form method="POST" enctype="multipart/form-data">

    <label>Imagen superior</label><br>
    <?php if (!empty($cover['image_top'])): ?>
        <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_top'] ?>" width="250"><br>
    <?php endif; ?>
    <input type="file" name="image_top"><br><br>

    <label>Imagen inferior</label><br>
    <?php if (!empty($cover['image_bottom'])): ?>
        <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_bottom'] ?>" width="250"><br>
    <?php endif; ?>
    <input type="file" name="image_bottom"><br><br>

    <button type="submit">Guardar portada</button>
</form>