<?php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/models/Client.php';

// Construir la URL base de producción dinámicamente
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'];
$baseUrl  = $protocol . '://' . $host;

// En localhost, recortar el subdirectorio del BASE_URL
if ($host === 'localhost') {
    $baseUrl .= '/landingPageMk';
}

// Obtener todos los clientes para sus slugs
$clientModel = new Client($pdo);
$allClients  = $clientModel->all();

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <!-- Inicio -->
  <url>
    <loc><?= $baseUrl ?>/</loc>
    <lastmod><?= date('Y-m-d') ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>

  <!-- Proyectos -->
  <url>
    <loc><?= $baseUrl ?>/proyectos.php</loc>
    <lastmod><?= date('Y-m-d') ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>

  <!-- Nosotros -->
  <url>
    <loc><?= $baseUrl ?>/nosotros.php</loc>
    <lastmod><?= date('Y-m-d') ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>

  <?php foreach ($allClients as $client): ?>
  <!-- Cliente: <?= htmlspecialchars($client['name']) ?> -->
  <url>
    <loc><?= $baseUrl ?>/clientes/index.php?slug=<?= urlencode($client['slug']) ?></loc>
    <lastmod><?= date('Y-m-d') ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
  <?php endforeach; ?>

</urlset>
