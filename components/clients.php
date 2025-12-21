<?php
require_once __DIR__ . '/../app/config/app.php';

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Client.php';

$clientModel = new Client($pdo);
$clients = $clientModel->all();
?>

<section class="clients" id="clientes">

  <div class="clients-container">

    <!-- Tabs de filtrado -->
    <div class="clients-tabs">
      <button class="tab-btn active" data-tab="ultimos">Últimos</button>
      <button class="tab-btn" data-tab="servicios">Servicios</button>
      <button class="tab-btn" data-tab="industrias">Industrias</button>
    </div>

    <!-- Grid de proyectos -->
    <div class="clients-grid" id="clients-grid">
      <?php foreach ($clients as $client): ?>
        <a href="<?= BASE_URL ?>/clientes/index.php?slug=<?= urlencode($client['slug']) ?>" class="client-card">
          <div class="client-card-image">
            <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= htmlspecialchars($client['logo']) ?>" alt="<?= htmlspecialchars($client['name']) ?>">
            <div class="client-card-overlay"></div>
          </div>
          <div class="client-card-info">
            <h3><?= htmlspecialchars($client['name']) ?></h3>
            <p><?= htmlspecialchars($client['short_description'] ?? 'Proyecto de Marca') ?></p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

  </div>

</section>

<script>
  // Funcionalidad de tabs (por ahora solo visual, sin filtrado real)
  ( function () {
    const tabBtns = document.querySelectorAll( '.tab-btn' );

    tabBtns.forEach( btn => {
      btn.addEventListener( 'click', function () {
        // Remover active de todos
        tabBtns.forEach( b => b.classList.remove( 'active' ) );
        // Agregar active al clickeado
        this.classList.add( 'active' );

        // Aquí se podría agregar lógica de filtrado real cuando se tengan las columnas en la BD
      } );
    } );
  } )();
</script>
