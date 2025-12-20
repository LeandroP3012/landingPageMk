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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/components.css">
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

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(211, 47, 47, 0.1);
            border: 1px solid rgba(211, 47, 47, 0.3);
            color: #ff6b6b;
        }

        .alert i {
            font-size: 20px;
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

        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
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

        .gallery-section {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-top: 10px;
        }

        .gallery-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            align-items: start;
        }

        .gallery-item-full {
            grid-column: 1 / -1;
        }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.4);
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-add:hover {
            background: rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
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

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }

            .gallery-item {
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
                    <i class="fas fa-plus-circle"></i>
                    Crear Nuevo Cliente
                </h1>
                <a href="index.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Volver
                </a>
            </div>

            <div class="form-card">
                <?php if (!empty($errors)): ?>
                    <div class="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <?php foreach ($errors as $err): ?>
                                <p><?= htmlspecialchars($err) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

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
                            <input type="text" id="name" name="name" class="form-control" placeholder="Ej: Empresa XYZ" required>
                        </div>

                        <div class="form-group">
                            <label for="slug">
                                <i class="fas fa-link"></i>
                                Slug (URL amigable)
                            </label>
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="Ej: empresa-xyz" required>
                        </div>

                        <div class="form-group">
                            <label for="short_description">
                                <i class="fas fa-align-left"></i>
                                Descripción Corta
                            </label>
                            <textarea id="short_description" name="short_description" class="form-control" placeholder="Breve descripción del cliente..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-file-alt"></i>
                                Descripción Completa
                            </label>
                            <textarea id="description" name="description" class="form-control" placeholder="Descripción detallada del cliente, proyectos realizados, etc..."></textarea>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-image"></i>
                            Imagen de Portada
                        </h3>

                        <div class="form-group">
                            <label for="logo">
                                <i class="fas fa-upload"></i>
                                Logo/Portada del Cliente
                            </label>
                            <input type="file" id="logo" name="logo" accept="image/*" class="file-input">
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="form-section-title">
                            <i class="fas fa-images"></i>
                            Galería de Imágenes
                        </h3>

                        <div class="gallery-section">
                            <div id="gallery-wrapper">
                                <div class="gallery-item">
                                    <div class="gallery-item-full">
                                        <label><i class="fas fa-camera"></i> Imagen</label>
                                        <input type="file" name="gallery[]" accept="image/*" class="file-input">
                                    </div>
                                    <div class="gallery-item-full">
                                        <label><i class="fas fa-comment"></i> Descripción</label>
                                        <input type="text" name="gallery_caption[]" class="form-control" placeholder="Descripción de la imagen">
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add-image" class="btn-add">
                                <i class="fas fa-plus"></i>
                                Agregar Otra Imagen
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        Crear Cliente
                    </button>
                </form>
            </div>
        </div>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>

    <script>
        // Añadir más inputs para galería dinámicamente
        document.getElementById( 'add-image' ).addEventListener( 'click', () => {
            const wrapper = document.getElementById( 'gallery-wrapper' );
            const div = document.createElement( 'div' );
            div.className = 'gallery-item';
            div.innerHTML = `
                <div class="gallery-item-full">
                    <label><i class="fas fa-camera"></i> Imagen</label>
                    <input type="file" name="gallery[]" accept="image/*" class="file-input">
                </div>
                <div class="gallery-item-full">
                    <label><i class="fas fa-comment"></i> Descripción</label>
                    <input type="text" name="gallery_caption[]" class="form-control" placeholder="Descripción de la imagen">
                </div>
            `;
            wrapper.appendChild( div );
        } );

        // Auto-generar slug desde el nombre
        document.getElementById( 'name' ).addEventListener( 'input', function ( e ) {
            const slugInput = document.getElementById( 'slug' );
            if ( slugInput.value === '' || slugInput.getAttribute( 'data-manual' ) !== 'true' ) {
                slugInput.value = e.target.value
                    .toLowerCase()
                    .normalize( 'NFD' )
                    .replace( /[\u0300-\u036f]/g, '' )
                    .replace( /[^a-z0-9\s-]/g, '' )
                    .replace( /\s+/g, '-' )
                    .replace( /-+/g, '-' )
                    .trim();
            }
        } );

        document.getElementById( 'slug' ).addEventListener( 'input', function () {
            this.setAttribute( 'data-manual', 'true' );
        } );
    </script>
</body>

</html>
