<?php
require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once ROOT_PATH . '/app/models/Client.php';
require_once ROOT_PATH . '/app/models/ClientLogo.php';

session_start();

if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

$clientId = $_GET['client_id'] ?? null;

if (!$clientId) {
    die('ID de cliente no proporcionado');
}

$clientModel = new Client($pdo);
$logoModel = new ClientLogo($pdo);

$client = $clientModel->findById((int) $clientId);
$logos = $logoModel->getByClient((int) $clientId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['logo']['name'])) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('logo_') . '.' . $ext;

        move_uploaded_file(
            $_FILES['logo']['tmp_name'],
            ROOT_PATH . '/storage/uploads/clients/logos/' . $filename
        );

        $logoModel->create($clientId, $filename);
        header("Location: logos.php?client_id=$clientId");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logos de <?= htmlspecialchars($client['name']) ?> - Panel de Administración</title>
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
            background: #000000ff;
            min-height: 100vh;
            padding: 0;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
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

        .client-info {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            opacity: 0.95;
        }

        .client-info i {
            font-size: 18px;
        }

        .btn {
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
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .content {
            padding: 0;
        }

        .upload-section {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
        }

        .upload-section h2 {
            color: #ffffff;
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .upload-form {
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        .form-group label {
            display: block;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }

        .file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-input-label:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.5);
        }

        .file-name {
            color: #a0aec0;
            font-size: 13px;
            margin-top: 5px;
        }

        .logos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .logo-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .logo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .logo-preview {
            width: 100%;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
        }

        .logo-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .logo-actions {
            width: 100%;
            display: flex;
            gap: 8px;
        }

        .action-btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn-delete:hover {
            background: #d32f2f;
            color: white;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #ffffff;
        }

        .empty-state p {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .header-top {
                flex-direction: column;
                gap: 15px;
            }

            .upload-form {
                flex-direction: column;
            }

            .form-group {
                width: 100%;
            }

            .logos-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="header">
                <div class="header-top">
                    <h1>
                        <i class="fas fa-images"></i>
                        Gestión de Logos
                    </h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        Volver a Clientes
                    </a>
                </div>
                <div class="client-info">
                    <i class="fas fa-building"></i>
                    <strong><?= htmlspecialchars($client['name']) ?></strong>
                </div>
            </div>

            <div class="content">
                <div class="stats">
                    <div class="stat-card">
                        <h3>Total de Logos</h3>
                        <div class="number"><?= count($logos) ?></div>
                    </div>
                </div>

                <div class="upload-section">
                    <h2>
                        <i class="fas fa-cloud-upload-alt"></i>
                        Agregar Nuevo Logo
                    </h2>
                    <form method="POST" enctype="multipart/form-data" class="upload-form" id="uploadForm">
                        <div class="form-group">
                            <label for="logo">
                                <i class="fas fa-file-image"></i>
                                Seleccionar Logo
                            </label>
                            <div class="file-input-wrapper">
                                <input type="file" name="logo" id="logo" required accept="image/*">
                                <label for="logo" class="file-input-label">
                                    <i class="fas fa-paperclip"></i>
                                    <span id="fileName">Elegir archivo...</span>
                                </label>
                            </div>
                            <div class="file-name" id="fileInfo"></div>
                        </div>
                        <button type="submit" class="btn">
                            <i class="fas fa-plus-circle"></i>
                            Agregar al Carrusel
                        </button>
                    </form>
                </div>

                <?php if (count($logos) > 0): ?>
                    <div class="logos-grid">
                        <?php foreach ($logos as $logo): ?>
                            <div class="logo-card">
                                <div class="logo-preview">
                                    <img src="<?= BASE_URL ?>/storage/uploads/clients/logos/<?= $logo['logo'] ?>" alt="Logo" loading="lazy">
                                </div>
                                <div class="logo-actions">
                                    <a href="logo_delete.php?id=<?= $logo['id'] ?>&client_id=<?= $clientId ?>" class="action-btn btn-delete" data-confirm-delete data-confirm-title="¿Eliminar logo?" data-confirm-message="Esta acción eliminará permanentemente el logo del carrusel.">
                                        <i class="fas fa-trash"></i>
                                        Eliminar
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-images"></i>
                        <h3>No hay logos registrados</h3>
                        <p>Comienza agregando el primer logo para el carrusel del cliente</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>

    <script src="<?= BASE_URL ?>/admin/assets/js/confirm-modal.js"></script>
    <script>
        // Mostrar nombre del archivo seleccionado
        document.getElementById( 'logo' ).addEventListener( 'change', function ( e ) {
            const fileName = e.target.files[ 0 ]?.name || 'Elegir archivo...';
            const fileSize = e.target.files[ 0 ]?.size || 0;
            const fileSizeMB = ( fileSize / ( 1024 * 1024 ) ).toFixed( 2 );

            document.getElementById( 'fileName' ).textContent = fileName;
            document.getElementById( 'fileInfo' ).textContent = fileSize > 0 ? `Tamaño: ${fileSizeMB} MB` : '';
        } );
    </script>
</body>

</html>
