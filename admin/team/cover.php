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

// Para detectar la página activa en el sidebar
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portada del Equipo - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/components.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #000;
            color: #fff;
            min-height: 100vh;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-back {
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .cover-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .cover-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .section-title i {
            color: #667eea;
        }

        .image-preview {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-empty {
            color: rgba(255, 255, 255, 0.3);
            text-align: center;
            padding: 20px;
        }

        .image-preview-empty i {
            font-size: 48px;
            margin-bottom: 10px;
            display: block;
        }

        .file-upload {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            font-weight: 500;
        }

        .file-upload-label:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .file-upload-label i {
            font-size: 18px;
        }

        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            padding: 8px 12px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
            display: none;
        }

        .file-name.active {
            display: block;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .info-box {
            background: rgba(102, 126, 234, 0.1);
            border-left: 4px solid #667eea;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .info-box i {
            color: #667eea;
            margin-right: 10px;
        }

        .info-box p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .cover-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <main class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-image"></i> Portada del Equipo</h1>
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Configure las imágenes que se mostrarán en la portada de la sección de equipo. Estas imágenes aparecerán en la parte superior e inferior de la página.</p>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <div class="cover-grid">
                <!-- Imagen Superior -->
                <div class="cover-section">
                    <div class="section-title">
                        <i class="fas fa-arrow-up"></i>
                        Imagen Superior
                    </div>

                    <div class="image-preview">
                        <?php if (!empty($cover['image_top'])): ?>
                            <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_top'] ?>" alt="Imagen Superior" id="previewTop">
                        <?php else: ?>
                            <div class="image-preview-empty">
                                <i class="fas fa-image"></i>
                                <p>No hay imagen configurada</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="file-upload">
                        <input type="file" name="image_top" id="image_top" accept="image/*">
                        <label for="image_top" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Seleccionar imagen superior</span>
                        </label>
                    </div>
                    <div class="file-name" id="fileNameTop"></div>
                </div>

                <!-- Imagen Inferior -->
                <div class="cover-section">
                    <div class="section-title">
                        <i class="fas fa-arrow-down"></i>
                        Imagen Inferior
                    </div>

                    <div class="image-preview">
                        <?php if (!empty($cover['image_bottom'])): ?>
                            <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_bottom'] ?>" alt="Imagen Inferior" id="previewBottom">
                        <?php else: ?>
                            <div class="image-preview-empty">
                                <i class="fas fa-image"></i>
                                <p>No hay imagen configurada</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="file-upload">
                        <input type="file" name="image_bottom" id="image_bottom" accept="image/*">
                        <label for="image_bottom" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Seleccionar imagen inferior</span>
                        </label>
                    </div>
                    <div class="file-name" id="fileNameBottom"></div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </main>

    <script>
        // Preview y nombre para imagen superior
        document.getElementById( 'image_top' ).addEventListener( 'change', function ( e ) {
            const file = e.target.files[ 0 ];
            const fileNameDiv = document.getElementById( 'fileNameTop' );

            if ( file ) {
                fileNameDiv.innerHTML = '<i class="fas fa-file-image"></i> ' + file.name;
                fileNameDiv.classList.add( 'active' );

                // Preview
                const reader = new FileReader();
                reader.onload = function ( e ) {
                    const preview = document.querySelector( '#previewTop' );
                    if ( preview ) {
                        preview.src = e.target.result;
                    } else {
                        const container = document.querySelector( '.image-preview' );
                        container.innerHTML = '<img src="' + e.target.result + '" alt="Preview" id="previewTop">';
                    }
                }
                reader.readAsDataURL( file );
            }
        } );

        // Preview y nombre para imagen inferior
        document.getElementById( 'image_bottom' ).addEventListener( 'change', function ( e ) {
            const file = e.target.files[ 0 ];
            const fileNameDiv = document.getElementById( 'fileNameBottom' );

            if ( file ) {
                fileNameDiv.innerHTML = '<i class="fas fa-file-image"></i> ' + file.name;
                fileNameDiv.classList.add( 'active' );

                // Preview
                const reader = new FileReader();
                reader.onload = function ( e ) {
                    const preview = document.querySelector( '#previewBottom' );
                    if ( preview ) {
                        preview.src = e.target.result;
                    } else {
                        const containers = document.querySelectorAll( '.image-preview' );
                        containers[ 1 ].innerHTML = '<img src="' + e.target.result + '" alt="Preview" id="previewBottom">';
                    }
                }
                reader.readAsDataURL( file );
            }
        } );
    </script>
</body>

</html>
