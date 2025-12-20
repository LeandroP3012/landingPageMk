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

<h1><?= htmlspecialchars($client['name']) ?></h1>
<img src="<?= BASE_URL ?>/storage/uploads/clients/<?= $client['logo'] ?>" width="200">
<p><?= htmlspecialchars($client['short_description']) ?></p>
<div><?= nl2br(htmlspecialchars($client['description'])) ?></div>

<h2>Galería</h2>
<?php if (!empty($gallery)): ?>
    <?php foreach ($gallery as $item): ?>
        <img src="<?= BASE_URL ?>/storage/uploads/clients/gallery/<?= $item['image'] ?>" width="150">
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay imágenes</p>
<?php endif; ?>