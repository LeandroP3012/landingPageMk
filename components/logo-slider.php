<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/ClientLogo.php';

$logoModel = new ClientLogo($pdo);
$logos = $logoModel->all();
?>


<section id="logo-slider" class="bg-[#000000] py-12 md:py-14">
  <div class="overflow-hidden relative px-4" style="height: 5.75rem; padding-top: 1.15rem; padding-bottom: 1.15rem;">
    <div class="marquee-track logo-slider-track" id="marquee-track">

      <?php foreach ($logos as $logo): ?>
        <div class="logo-item">
          <img src="<?= BASE_URL ?>/storage/uploads/clients/logos/<?= htmlspecialchars($logo['logo']) ?>" alt="Logo cliente" width="<?= (int) $logo['width'] ?>" height="<?= (int) $logo['height'] ?>" loading="lazy" class="object-contain object-center h-auto">
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>

<script>
  // Carrusel infinito sin cortes usando CSS animation
  ( function () {
    const track = document.getElementById( 'marquee-track' );
    if ( !track ) return;

    const originalItems = Array.from( track.children );

    // Clonar suficientes veces para crear un loop perfecto
    // Con 15 clones, tendremos suficiente contenido para la animación continua
    for ( let i = 0; i < 15; i++ ) {
      originalItems.forEach( item => {
        const clone = item.cloneNode( true );
        track.appendChild( clone );
      } );
    }

    // Calcular el ancho total de un set completo (logos originales)
    let totalWidth = 0;
    originalItems.forEach( item => {
      totalWidth += item.offsetWidth;
    } );

    // Agregar gaps
    const gap = parseFloat( getComputedStyle( track ).gap ) || 0;
    totalWidth += gap * originalItems.length;

    // Crear animación CSS dinámica
    const styleSheet = document.createElement( 'style' );
    styleSheet.textContent = `
    @keyframes infiniteScroll {
      0% {
        transform: translateX(0);
      }
      100% {
        transform: translateX(-${totalWidth}px);
      }
    }
    
    #marquee-track {
      animation: infiniteScroll 5s linear infinite;
    }
    
    #marquee-track:hover {
      animation-play-state: paused;
    }
  `;
    document.head.appendChild( styleSheet );
  } )();
</script>
