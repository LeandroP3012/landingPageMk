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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar <?= htmlspecialchars($client['name']) ?> - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/confirm-modal.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #000000;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header h1 i {
            font-size: 32px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group label i {
            margin-right: 6px;
            color: #667eea;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        .file-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(102, 126, 234, 0.1);
            border: 2px dashed rgba(102, 126, 234, 0.4);
            border-radius: 10px;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input:hover {
            background: rgba(102, 126, 234, 0.2);
            border-color: #667eea;
        }

        .file-input::file-selector-button {
            padding: 8px 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-right: 12px;
            transition: all 0.3s ease;
        }

        .file-input::file-selector-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .current-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            margin-top: 10px;
        }

        .current-logo img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .current-logo-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
        }

        .form-section {
            margin-bottom: 35px;
            padding-bottom: 35px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section-title {
            color: white;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-title i {
            color: #667eea;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .gallery-edit-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .gallery-edit-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .gallery-edit-content {
            padding: 12px;
        }

        .gallery-edit-content input {
            width: 100%;
            margin-bottom: 10px;
        }

        .btn-delete-gallery {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(211, 47, 47, 0.2);
            color: #ff6b6b;
            text-decoration: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-delete-gallery:hover {
            background: rgba(211, 47, 47, 0.3);
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .empty-gallery {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            margin-top: 15px;
        }

        .empty-gallery i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="header">
                <h1>
                    <i class="fas fa-edit"></i>
                    Editar Cliente
                </h1>
                <a href="view.php?id=<?= $id ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>

            <div class="form-card">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-info-circle"></i>
                            Información Básica
                        </h3>

                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-building"></i>
                                Nombre del Cliente
                            </label>
                            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($client['name']) ?>" placeholder="Ej: Empresa XYZ" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">
                                <i class="fas fa-link"></i>
                                Slug (URL amigable)
                            </label>
                            <input type="text" id="slug" name="slug" class="form-control" value="<?= htmlspecialchars($client['slug']) ?>" placeholder="Ej: empresa-xyz" required>
                        </div>

                        <div class="form-group">
                            <label for="short_description">
                                <i class="fas fa-align-left"></i>
                                Descripción Corta
                            </label>
                            <textarea id="short_description" name="short_description" class="form-control" placeholder="Breve descripción del cliente..."><?= htmlspecialchars($client['short_description']) ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-file-alt"></i>
                                Descripción Completa
                            </label>
                            <textarea id="description" name="description" class="form-control" placeholder="Descripción detallada del cliente, proyectos realizados, etc..."><?= htmlspecialchars($client['description']) ?></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-image"></i>
                            Imagen de Portada
                        </h3>

                        <?php if (!empty($client['logo'])): ?>
                            <div class="current-logo">
                                <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= $client['logo'] ?>" alt="Logo actual">
                                <div class="current-logo-text">
                                    <i class="fas fa-check-circle"></i>
                                    Logo actual - Sube un archivo nuevo si deseas reemplazarlo
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="logo">
                                <i class="fas fa-upload"></i>
                                <?= !empty($client['logo']) ? 'Reemplazar Logo/Portada' : 'Logo/Portada del Cliente' ?>
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/*" class="file-input">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-images"></i>
                            Galería de Imágenes Existentes
                        </h3>

                        <?php if (!empty($gallery)): ?>
                            <div class="gallery-grid">
                                <?php foreach ($gallery as $item): ?>
                                    <div class="gallery-edit-item">
                                        <img src="<?= BASE_URL ?>/storage/uploads/clients/gallery/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['caption']) ?>">
                                        <div class="gallery-edit-content">
                                            <input type="text" name="captions[<?= $item['id'] ?>]" value="<?= htmlspecialchars($item['caption']) ?>" placeholder="Descripción de la imagen" class="form-control">
                                            <a href="delete_gallery.php?id=<?= $item['id'] ?>&client_id=<?= $id ?>" class="btn-delete-gallery" data-confirm-delete data-confirm-title="¿Eliminar imagen?" data-confirm-message="Esta imagen se eliminará permanentemente de la galería.">
                                                <i class="fas fa-trash"></i>
                                                Eliminar
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-gallery">
                                <i class="fas fa-images"></i>
                                <p>No hay imágenes en la galería</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-plus-circle"></i>
                            Agregar Nuevas Imágenes a la Galería
                        </h3>

                        <div class="form-group">
                            <label for="new_images">
                                <i class="fas fa-cloud-upload-alt"></i>
                                Seleccionar nuevas imágenes (puedes seleccionar múltiples)
                            </label>
                            <input type="file" id="new_images" name="new_images[]" accept="image/*" multiple class="file-input">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>

    <script src="<?= BASE_URL ?>/admin/assets/js/confirm-modal.js"></script>
</body>

</html>
