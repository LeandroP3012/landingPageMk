<?php
require_once 'app/config/database.php';
require_once 'app/models/Team.php';
require_once 'app/models/TeamCover.php';

$teamModel = new Team($pdo);
$team = $teamModel->all();

$coverModel = new TeamCover($pdo);
$cover = $coverModel->get();
?>

<section class="team" id="equipo">
  <div class="team-container">

    <div class="team-header">
      <div class="team-category">
        <span class="category-top">Gestión</span>
        <span class="category-bottom">& Dirección</span>
      </div>
      <h1 class="team-main-title" data-split-by="letter">HUB (NOSOTROS)</h1>
    </div>

    <?php if ($cover && ($cover['image_top'] || $cover['image_bottom'])): ?>
      <div class="team-cover" id="teamSlider">
        <div class="team-cover-inner">

          <!-- Imagen inferior -->
          <?php if (!empty($cover['image_bottom'])): ?>
            <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_bottom'] ?>" class="cover-image" alt="Equipo – imagen base">
          <?php endif; ?>

          <!-- Imagen superior -->
          <?php if (!empty($cover['image_top'])): ?>
            <div class="cover-top" id="coverTop">
              <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_top'] ?>" alt="Equipo – imagen superior">
            </div>
          <?php endif; ?>

          <div class="cover-divider" id="coverDivider"></div>

        </div>
      </div>
    <?php endif; ?>

    <div class="team-content">
      <div class="team-brand">
        <h2 class="brand-title">Equipo</h2>
      </div>

      <div class="team-grid">
        <?php foreach ($team as $member): ?>
          <article class="team-member">
            <div class="team-photo">
              <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $member['photo'] ?>" alt="<?= htmlspecialchars($member['name']) ?>">
            </div>
            <div class="team-info">
              <h3><?= htmlspecialchars($member['name']) ?></h3>
              <p><?= htmlspecialchars($member['role']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="team-footer">
      <div class="team-category">
        <span class="category-top">HUB</span>
        <span class="category-bottom">Creativo</span>
      </div>
    </div>

  </div>
</section>

<script>
  // Slider interactivo de imágenes
  ( function () {
    const slider = document.getElementById( 'teamSlider' );
    const coverTop = document.getElementById( 'coverTop' );
    const divider = document.getElementById( 'coverDivider' );

    if ( !slider || !coverTop || !divider ) return;

    let isDragging = false;

    const updateSlider = ( x ) => {
      const rect = slider.getBoundingClientRect();
      let percentage = ( ( x - rect.left ) / rect.width ) * 100;
      percentage = Math.max( 0, Math.min( 100, percentage ) );

      // Actualizar el ancho del contenedor de la imagen superior
      // Esto controla cuánto de la imagen superior se ve
      coverTop.style.width = percentage + '%';
      divider.style.left = percentage + '%';
    };

    const onMove = ( e ) => {
      if ( !isDragging ) return;
      const x = e.type.includes( 'touch' ) ? e.touches[ 0 ].clientX : e.clientX;
      updateSlider( x );
    };

    const startDrag = () => {
      isDragging = true;
      document.body.style.cursor = 'ew-resize';
    };

    const stopDrag = () => {
      isDragging = false;
      document.body.style.cursor = '';
    };

    // Eventos del divider
    divider.addEventListener( 'mousedown', startDrag );
    divider.addEventListener( 'touchstart', startDrag );

    // Eventos del contenedor
    slider.addEventListener( 'mousedown', ( e ) => {
      startDrag();
      updateSlider( e.clientX );
    } );

    slider.addEventListener( 'touchstart', ( e ) => {
      startDrag();
      updateSlider( e.touches[ 0 ].clientX );
    } );

    // Eventos globales
    document.addEventListener( 'mousemove', onMove );
    document.addEventListener( 'touchmove', onMove );
    document.addEventListener( 'mouseup', stopDrag );
    document.addEventListener( 'touchend', stopDrag );
  } )();

  // Animación de letras para el título
  ( function () {
    const { matches: motionOK } = window.matchMedia( '(prefers-reduced-motion: no-preference)' );

    if ( !motionOK ) return;

    const title = document.querySelector( '[data-split-by]' );
    if ( !title ) return;

    const text = title.textContent;
    const letters = [ ...text ].map( ( char, index ) => {
      const span = document.createElement( 'span' );
      span.textContent = char;
      span.style.setProperty( '--index', index );
      span.className = 'letter-animate';
      return span;
    } );

    title.textContent = '';
    title.append( ...letters );
  } )();
</script>
