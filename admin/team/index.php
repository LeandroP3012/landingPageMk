<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once ROOT_PATH . '/app/models/Team.php';

$teamModel = new Team($pdo);
$team = $teamModel->all();

// Para detectar la página activa en el sidebar
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión del Equipo - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/confirm-modal.css">
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
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-header h1 {
            font-size: 32px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
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
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .team-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 24px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(102, 126, 234, 0.3);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .member-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 16px;
            border: 3px solid rgba(102, 126, 234, 0.5);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .member-photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            font-size: 48px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .member-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #fff;
        }

        .member-role {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 8px;
        }

        .member-order {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 16px;
            padding: 4px 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
        }

        .card-actions {
            display: flex;
            gap: 10px;
            width: 100%;
            margin-top: auto;
        }

        .btn-icon {
            padding: 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            font-size: 14px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-icon:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .btn-icon.edit {
            color: #3b82f6;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .btn-icon.edit:hover {
            background: rgba(59, 130, 246, 0.2);
        }

        .btn-icon.delete {
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }

        .btn-icon.delete:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            backdrop-filter: blur(10px);
            border: 1px dashed rgba(255, 255, 255, 0.2);
        }

        .empty-state i {
            font-size: 64px;
            color: rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.7);
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .team-grid {
                grid-template-columns: 1fr;
            }

            .header-actions {
                width: 100%;
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
            <h1><i class="fas fa-users"></i> Gestión del Equipo</h1>
            <div class="header-actions">
                <a href="cover.php" class="btn btn-secondary">
                    <i class="fas fa-image"></i> Portada
                </a>
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Miembro
                </a>
            </div>
        </div>

        <?php if (empty($team)): ?>
            <div class="empty-state">
                <i class="fas fa-user-friends"></i>
                <h3>No hay miembros del equipo</h3>
                <p>Comienza agregando tu primer miembro al equipo</p>
                <a href="create.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Agregar Miembro
                </a>
            </div>
        <?php else: ?>
            <div class="team-grid">
                <?php foreach ($team as $member): ?>
                    <div class="team-card">
                        <?php if (!empty($member['photo'])): ?>
                            <img src="<?= BASE_URL ?>/storage/uploads/team/<?= htmlspecialchars($member['photo']) ?>" alt="<?= htmlspecialchars($member['name']) ?>" class="member-photo">
                        <?php else: ?>
                            <div class="member-photo-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>

                        <div class="member-name"><?= htmlspecialchars($member['name']) ?></div>
                        <div class="member-role"><?= htmlspecialchars($member['role']) ?></div>
                        <div class="member-order">Orden: <?= htmlspecialchars($member['sort_order']) ?></div>

                        <div class="card-actions">
                            <a href="edit.php?id=<?= $member['id'] ?>" class="btn-icon edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id=<?= $member['id'] ?>" class="btn-icon delete" data-confirm-delete data-confirm-title="Eliminar Miembro" data-confirm-message="¿Estás seguro de eliminar a <?= htmlspecialchars($member['name']) ?>?" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </main>

    <script src="<?= BASE_URL ?>/admin/assets/js/confirm-modal.js"></script>
</body>

</html>
