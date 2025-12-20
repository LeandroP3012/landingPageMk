<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}


require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once ROOT_PATH . '/app/models/Client.php';
require_once ROOT_PATH . '/app/models/ClientGallery.php';


$id = $_GET['id'] ?? null;
$clientModel = new Client($pdo);
$client = $clientModel->findById($id);

if (!$client) {
    echo "Cliente no encontrado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $short_description = $_POST['short_description'] ?? '';
    $description = $_POST['description'] ?? '';

    // Actualizar logo si se sube uno nuevo
    $logo = $client['logo'];
    if (!empty($_FILES['logo']['name'])) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/../../storage/uploads/clients/' . $logo);
    }

    $clientModel->update($id, $name, $slug, $short_description, $description, $logo);
    header('Location: index.php');
    exit;
}
$galleryModel = new ClientGallery($pdo);
$gallery = $galleryModel->getByClient($id);
?>

<h1>Editar Cliente</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" value="<?= htmlspecialchars($client['name']) ?>" required><br>
    <input type="text" name="slug" value="<?= htmlspecialchars($client['slug']) ?>" required><br>
    <textarea name="short_description"><?= htmlspecialchars($client['short_description']) ?></textarea><br>
    <textarea name="description"><?= htmlspecialchars($client['description']) ?></textarea><br>
    <input type="file" name="logo" accept="image/*"><br>
    <h3>Galería de imágenes</h3>
    <div class="client-gallery-edit">
        <?php foreach ($gallery as $item): ?>
            <div class="gallery-item">
                <img src="<?= BASE_URL ?>/storage/uploads/clients/gallery/<?= $item['image'] ?>" width="100" alt="">
                <input type="text" name="captions[<?= $item['id'] ?>]" value="<?= htmlspecialchars($item['caption']) ?>"
                    placeholder="Descripción">
                <a href="delete_gallery.php?id=<?= $item['id'] ?>&client_id=<?= $id ?>">Eliminar</a>
            </div>
        <?php endforeach; ?>
    </div>

    <h4>Agregar nuevas imágenes</h4>
    <input type="file" name="new_images[]" multiple>

    <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= $client['logo'] ?>" width="100"><br>
    <button type="submit">Guardar cambios</button>
</form>