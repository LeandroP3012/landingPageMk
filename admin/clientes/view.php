<?php

session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}


require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once __DIR__ . '/../../app/models/Client.php';
require_once __DIR__ . '/../../app/models/ClientGallery.php';

$id = $_GET['id'] ?? null;
$clientModel = new Client($pdo);
$client = $clientModel->findById($id);

if (!$client) {
    echo "Cliente no encontrado";
    exit;
}

$galleryModel = new ClientGallery($pdo);
$gallery = $galleryModel->getByClient($client['id']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($client['name']) ?> - Panel de Administración</title>
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

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
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

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-back {
            background: white;
            color: #667eea;
        }

        .btn-edit {
            background: rgba(245, 124, 0, 0.2);
            color: #f57c00;
            border: 1px solid rgba(245, 124, 0, 0.4);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .client-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .client-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
        }

        .client-logo {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .client-info {
            color: white;
        }

        .info-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .info-label i {
            color: #667eea;
        }

        .info-value {
            color: white;
            font-size: 15px;
            line-height: 1.6;
        }

        .slug-badge {
            display: inline-block;
            background: rgba(102, 126, 234, 0.2);
            color: #667eea;
            padding: 6px 14px;
            border-radius: 20px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        .gallery-section {
            grid-column: 1 / -1;
        }

        .section-title {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: #667eea;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-caption {
            padding: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            min-height: 60px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 1024px) {
            .client-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .header-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .gallery-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../components/sidebar.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="header">
                <div class="header-left">
                    <h1>
                        <i class="fas fa-eye"></i>
                        <?= htmlspecialchars($client['name']) ?>
                    </h1>
                </div>
                <div class="header-actions">
                    <a href="edit.php?id=<?= $client['id'] ?>" class="btn btn-edit">
                        <i class="fas fa-edit"></i>
                        Editar
                    </a>
                    <a href="index.php" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Volver
                    </a>
                </div>
            </div>

            <div class="client-content">
                <div class="client-card">
                    <?php if (!empty($client['logo'])): ?>
                        <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= $client['logo'] ?>" alt="<?= htmlspecialchars($client['name']) ?>" class="client-logo">
                    <?php endif; ?>

                    <div class="client-info">
                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-link"></i>
                                Slug
                            </div>
                            <div class="info-value">
                                <span class="slug-badge"><?= htmlspecialchars($client['slug']) ?></span>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">
                                <i class="fas fa-calendar-plus"></i>
                                Fecha de Creación
                            </div>
                            <div class="info-value">
                                <?= date('d/m/Y H:i', strtotime($client['created_at'])) ?>
                            </div>
                        </div>

                        <?php if (!empty($client['updated_at'])): ?>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check"></i>
                                    Última Actualización
                                </div>
                                <div class="info-value">
                                    <?= date('d/m/Y H:i', strtotime($client['updated_at'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="client-card">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-align-left"></i>
                            Descripción Corta
                        </div>
                        <div class="info-value">
                            <?= !empty($client['short_description']) ? nl2br(htmlspecialchars($client['short_description'])) : '<em style="color: rgba(255,255,255,0.4);">Sin descripción corta</em>' ?>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-file-alt"></i>
                            Descripción Completa
                        </div>
                        <div class="info-value">
                            <?= !empty($client['description']) ? nl2br(htmlspecialchars($client['description'])) : '<em style="color: rgba(255,255,255,0.4);">Sin descripción completa</em>' ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="client-card gallery-section">
                <h2 class="section-title">
                    <i class="fas fa-images"></i>
                    Galería de Imágenes
                </h2>

                <?php if (!empty($gallery)): ?>
                    <div class="gallery-grid">
                        <?php foreach ($gallery as $item): ?>
                            <div class="gallery-item">
                                <img src="<?= BASE_URL ?>/storage/uploads/clients/gallery/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['caption']) ?>" class="gallery-image">
                                <?php if (!empty($item['caption'])): ?>
                                    <div class="gallery-caption">
                                        <?= htmlspecialchars($item['caption']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-images"></i>
                        <h3>No hay imágenes en la galería</h3>
                        <p>Este cliente aún no tiene imágenes agregadas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>
</body>

</html>
