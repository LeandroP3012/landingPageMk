<?php
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Client.php';
require_once __DIR__ . '/../app/models/ClientGallery.php';

$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: ' . BASE_URL);
    exit;
}

$clientModel = new Client($pdo);
$client = $clientModel->findBySlug($slug);

if (!$client) {
    http_response_code(404);
    echo 'Cliente no encontrado';
    exit;
}

/* 👉 AQUÍ ESTABA LO QUE FALTABA */
$galleryModel = new ClientGallery($pdo);
$gallery = $galleryModel->getByClient($client['id']);
?>

<section class="client-page">

    <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= htmlspecialchars($client['logo']) ?>"
        alt="<?= htmlspecialchars($client['name']) ?>">

    <h1><?= htmlspecialchars($client['name']) ?></h1>

    <p><?= htmlspecialchars($client['short_description']) ?></p>

    <div class="client-content">
        <?= nl2br(htmlspecialchars($client['description'])) ?>
    </div>

    <?php if (!empty($gallery)): ?>
        <section class="client-gallery">
            <h2>Trabajos realizados</h2>

            <div class="gallery-grid">
                <?php foreach ($gallery as $item): ?>
                    <figure>
                        <img src="<?= BASE_URL ?>/storage/uploads/clients/gallery/<?= htmlspecialchars($item['image']) ?>"
                            alt="">
                        <?php if (!empty($item['caption'])): ?>
                            <figcaption><?= htmlspecialchars($item['caption']) ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

</section>