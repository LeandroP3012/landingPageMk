<?php
// Subir dos niveles para llegar a la carpeta raíz del proyecto
require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';

// Modelos
require_once ROOT_PATH . '/app/models/Client.php';
require_once __DIR__ . '/../../app/models/ClientLogo.php';


session_start();
if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;
}


// Crear instancia del modelo
$clientModel = new Client($pdo);
$clients = $clientModel->all();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - Panel de Administración</title>
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
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
            background: #f8f9fa;
        }

        .content {
            padding: 0;
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

        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.01);
        }

        td {
            padding: 18px;
            font-size: 14px;
            color: #ffffff;
        }

        .id-badge {
            display: inline-block;
            background: #e8eaf6;
            color: #667eea;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .client-name {
            font-weight: 600;
            color: #ffffff;
        }

        .slug {
            color: #a0aec0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-view {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-view:hover {
            background: #1976d2;
            color: white;
        }

        .btn-edit {
            background: #fff3e0;
            color: #f57c00;
        }

        .btn-edit:hover {
            background: #f57c00;
            color: white;
        }

        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn-delete:hover {
            background: #d32f2f;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.3;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .content {
                padding: 20px;
            }

            .actions {
                flex-direction: column;
            }

            .action-btn {
                width: 100%;
                justify-content: center;
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
                    <i class="fas fa-users"></i>
                    Gestión de Clientes
                </h1>
                <a href="create.php" class="btn">
                    <i class="fas fa-plus-circle"></i>
                    Agregar Cliente
                </a>

            </div>

            <div class="content">
                <div class="stats">
                    <div class="stat-card">
                        <h3>Total de Clientes</h3>
                        <div class="number"><?= count($clients) ?></div>
                    </div>
                </div>

                <div class="table-container">
                    <?php if (count($clients) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-building"></i> Nombre</th>
                                    <th><i class="fas fa-link"></i> Slug</th>
                                    <th><i class="fas fa-cog"></i> Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><span class="id-badge">#<?= $client['id'] ?></span></td>
                                        <td><span class="client-name"><?= htmlspecialchars($client['name']) ?></span></td>
                                        <td><span class="slug"><?= htmlspecialchars($client['slug']) ?></span></td>
                                        <td>
                                            <div class="actions">
                                                <a href="view.php?id=<?= $client['id'] ?>" class="action-btn btn-view">
                                                    <i class="fas fa-eye"></i> Ver
                                                </a>

                                                <a href="edit.php?id=<?= $client['id'] ?>" class="action-btn btn-edit">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>

                                                <a href="logos.php?client_id=<?= $client['id'] ?>" class="action-btn btn-view">
                                                    <i class="fas fa-images"></i> Logos
                                                </a>


                                                <a href="delete.php?id=<?= $client['id'] ?>" class="action-btn btn-delete"
                                                    data-confirm-delete data-confirm-title="¿Eliminar cliente?"
                                                    data-confirm-message="Esta acción eliminará permanentemente el cliente.">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h3>No hay clientes registrados</h3>
                            <p>Comienza agregando tu primer cliente usando el botón de arriba</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </div>

    <script src="<?= BASE_URL ?>/admin/assets/js/confirm-modal.js"></script>
</body>

</html>