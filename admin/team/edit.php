<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

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

// Para detectar la página activa en el sidebar
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Miembro - Admin</title>
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

        .form-container {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 30px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 30px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .form-group input[type="text"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .current-photo {
            margin-bottom: 20px;
            text-align: center;
        }

        .current-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(102, 126, 234, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .current-photo-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
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
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.6);
        }

        .file-upload-label:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .file-upload-label i {
            font-size: 24px;
        }

        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn {
            padding: 12px 24px;
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

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
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
            <h1><i class="fas fa-user-edit"></i> Editar Miembro del Equipo</h1>
            <a href="index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <form method="POST" enctype="multipart/form-data" class="form-container">
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Información del Miembro
                </div>

                <div class="form-group">
                    <label for="name">
                        <i class="fas fa-user"></i> Nombre Completo *
                    </label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($member['name']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="role">
                        <i class="fas fa-briefcase"></i> Cargo/Rol *
                    </label>
                    <input type="text" id="role" name="role" value="<?= htmlspecialchars($member['role']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="sort_order">
                        <i class="fas fa-sort-numeric-down"></i> Orden de Visualización
                    </label>
                    <input type="number" id="sort_order" name="sort_order" value="<?= (int) $member['sort_order'] ?>" min="0">
                </div>
            </div>

            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-image"></i>
                    Fotografía
                </div>

                <?php if (!empty($member['photo'])): ?>
                    <div class="current-photo">
                        <span class="current-photo-label">Fotografía Actual</span>
                        <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $member['photo'] ?>" alt="<?= htmlspecialchars($member['name']) ?>">
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label><i class="fas fa-cloud-upload-alt"></i> Nueva Fotografía (opcional)</label>
                    <div class="file-upload">
                        <input type="file" name="photo" id="photo" accept="image/*">
                        <label for="photo" class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Seleccionar nueva fotografía</span>
                        </label>
                    </div>
                    <div class="file-name" id="fileName"></div>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
            </div>
        </form>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </main>

    <script>
        // Mostrar nombre del archivo seleccionado
        document.getElementById( 'photo' ).addEventListener( 'change', function ( e ) {
            const fileName = e.target.files[ 0 ]?.name || '';
            const fileNameDiv = document.getElementById( 'fileName' );

            if ( fileName ) {
                fileNameDiv.innerHTML = '<i class="fas fa-file-image"></i> ' + fileName;
                fileNameDiv.style.color = '#667eea';
            } else {
                fileNameDiv.innerHTML = '';
            }
        } );
    </script>
</body>

</html>
