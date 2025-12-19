<?php
require_once __DIR__ . '/../app/config/app.php';

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Client.php';

$clientModel = new Client($pdo);
$clients = $clientModel->all();
?>

<section class="clients" id="clientes">

  <div class="clients-container">

    <div class="clients-intro">
      <p class="clients-label">CLIENTES</p>
      <h2>Trabajamos con marcas que buscan crecer de verdad</h2>
    </div>

    <div class="clients-grid">
      <?php foreach ($clients as $client): ?>
        <a href="<?= BASE_URL ?>/clientes/index.php?slug=<?= urlencode($client['slug']) ?>">
          <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= htmlspecialchars($client['logo']) ?>"
            alt="<?= htmlspecialchars($client['name']) ?>">
        </a>
      <?php endforeach; ?>
    </div>

    <div class="clients-divider">
      <p>Branding · Estrategia · Diseño · Comunicación · Contenido</p>
    </div>

    <div class="clients-cta">
      <a href="#contacto">Ver cómo trabajamos →</a>
    </div>

  </div>

</section>
