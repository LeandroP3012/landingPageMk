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

$clientModel = new Client($pdo);
$galleryModel = new ClientGallery($pdo);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $slug = $_POST['slug'] ?? '';
    $short_description = $_POST['short_description'] ?? '';
    $description = $_POST['description'] ?? '';

    // Validar slug único
    if ($clientModel->findBySlug($slug)) {
        $errors[] = "El slug '$slug' ya existe, elige otro.";
    }

    // Guardar logo
    $logo = '';
    if (!empty($_FILES['logo']['name'])) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $logo = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['logo']['tmp_name'], __DIR__ . '/../../storage/uploads/clients/' . $logo);
    }

    if (empty($errors)) {
        // Crear cliente y obtener el ID insertado
        $clientId = $clientModel->create($name, $slug, $short_description, $description, $logo);

        // Subir imágenes adicionales para la galería con captions
        $captions = $_POST['gallery_caption'] ?? [];
        if (!empty($_FILES['gallery']['name'][0])) {
            foreach ($_FILES['gallery']['name'] as $key => $imageName) {
                if (empty($imageName))
                    continue;

                $tmpName = $_FILES['gallery']['tmp_name'][$key];
                $ext = pathinfo($imageName, PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                move_uploaded_file($tmpName, __DIR__ . '/../../storage/uploads/clients/gallery/' . $filename);

                $caption = $captions[$key] ?? '';

                // Insertar en client_gallery
                $stmt = $pdo->prepare(
                    "INSERT INTO client_gallery (client_id, image, caption, sort_order, created_at) VALUES (?, ?, ?, 0, NOW())"
                );
                $stmt->execute([$clientId, $filename, $caption]);
            }
        }

        header('Location: index.php');
        exit;
    }
}
?>

<h1>Crear Cliente</h1>

<?php if (!empty($errors)): ?>
    <div style="color:red;">
        <?php foreach ($errors as $err)
            echo "<p>$err</p>"; ?>
    </div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Nombre" required><br>
    <input type="text" name="slug" placeholder="Slug" required><br>
    <textarea name="short_description" placeholder="Descripción corta"></textarea><br>
    <textarea name="description" placeholder="Descripción completa"></textarea><br>

    <label>Portada:</label>
    <input type="file" name="logo" accept="image/*"><br>

    <label>Galería de imágenes:</label>
    <div id="gallery-wrapper">
        <div class="gallery-item">
            <input type="file" name="gallery[]" accept="image/*">
            <input type="text" name="gallery_caption[]" placeholder="Descripción de la imagen">
        </div>
    </div>
    <button type="button" id="add-image">Agregar otra imagen</button><br><br>

    <button type="submit">Crear</button>
</form>

<script>
    // Añadir más inputs para galería dinámicamente
    document.getElementById('add-image').addEventListener('click', () => {
        const wrapper = document.getElementById('gallery-wrapper');
        const div = document.createElement('div');
        div.className = 'gallery-item';
        div.innerHTML = `
        <input type="file" name="gallery[]" accept="image/*">
        <input type="text" name="gallery_caption[]" placeholder="Descripción de la imagen">
    `;
        wrapper.appendChild(div);
    });
</script>