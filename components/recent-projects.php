<?php
require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Client.php';

$clientModel = new Client($pdo);
// Obtener solo los últimos 3 proyectos
$recentClients = $clientModel->getLatest(3);
?>

<section class="recent-projects" id="proyectos-recientes">

  <div class="recent-projects-header">
    <div class="header-content">
      <span class="header-label"></span>
      <h2 class="recent-projects-title">Últimos Proyectos</h2>
    </div>
    <div class="scroll-hint">
      <span>Scroll</span>
      <div class="scroll-line"></div>
    </div>
  </div>

  <div class="horizontal-scroll-section">
    <div class="projects-track" id="projectsTrack">
      <?php foreach ($recentClients as $index => $client): ?>
        <div class="project-item" data-index="<?= $index ?>">
          <a href="<?= BASE_URL ?>/clientes/index.php?slug=<?= urlencode($client['slug']) ?>" class="project-link">
            <div class="project-image-wrapper">
              <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= htmlspecialchars($client['logo']) ?>" alt="<?= htmlspecialchars($client['name']) ?>" class="project-image" loading="lazy">
              <div class="project-overlay">
                <div class="overlay-content">
                  <span class="project-badge">Nuevo</span>
                  <div class="project-info">
                    <h3 class="project-name"><?= htmlspecialchars($client['name']) ?></h3>
                    <p class="project-description"><?= htmlspecialchars($client['short_description'] ?? 'Proyecto de Marca') ?></p>
                  </div>
                  <div class="project-arrow">
                    <i class="fas fa-arrow-right"></i>
                  </div>
                </div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="recent-projects-footer">
    <a href="<?= BASE_URL ?>/proyectos.php" class="view-all-btn">
      <span>Ver todos los proyectos</span>
      <i class="fas fa-arrow-right"></i>
    </a>
  </div>

</section>

<style>
  /* ================= RECENT PROJECTS SECTION (Full Height Horizontal Scroll) ================= */

  .recent-projects {
    position: relative;
    background: linear-gradient(135deg, #0a0a1e 0%, #1a0a2e 50%, #0f1a2e 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .recent-projects::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.08) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    pointer-events: none;
  }

  /* Header */
  .recent-projects-header {
    position: relative;
    z-index: 10;
    padding: 120px 80px 40px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
  }

  .header-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .header-label {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 3px;
    color: #667eea;
    opacity: 0.8;
  }

  .recent-projects-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 72px;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    letter-spacing: -2px;
    line-height: 1;
  }

  /* Scroll Hint */
  .scroll-hint {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
    color: rgba(255, 255, 255, 0.5);
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
  }

  .scroll-line {
    width: 1px;
    height: 60px;
    background: linear-gradient(to bottom, rgba(102, 126, 234, 0.8), transparent);
    animation: scrollLineMove 2s ease infinite;
  }

  @keyframes scrollLineMove {

    0%,
    100% {
      transform: translateY(0);
      opacity: 1;
    }

    50% {
      transform: translateY(20px);
      opacity: 0.3;
    }
  }

  /* Horizontal Scroll Section */
  .horizontal-scroll-section {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding: 0;
  }

  .projects-track {
    display: flex;
    gap: 40px;
    padding: 0 80px;
    will-change: transform;
    transition: transform 0.1s ease-out;
  }

  .project-item {
    flex: 0 0 70vw;
    height: 65vh;
    min-height: 500px;
    position: relative;
  }

  .project-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .project-link:hover {
    transform: scale(0.98);
  }

  .project-image-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #000;
  }

  .project-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .project-link:hover .project-image {
    transform: scale(1.05);
  }

  .project-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top,
        rgba(0, 0, 0, 0.95) 0%,
        rgba(0, 0, 0, 0.7) 30%,
        rgba(0, 0, 0, 0.3) 60%,
        transparent 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 60px;
    transition: background 0.6s ease;
  }

  .project-link:hover .project-overlay {
    background: linear-gradient(to top,
        rgba(102, 126, 234, 0.95) 0%,
        rgba(118, 75, 162, 0.85) 40%,
        rgba(0, 0, 0, 0.4) 70%,
        transparent 100%);
  }

  .overlay-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
    transform: translateY(0);
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .project-link:hover .overlay-content {
    transform: translateY(-10px);
  }

  .project-badge {
    display: inline-block;
    width: fit-content;
    padding: 8px 20px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    color: white;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 2px;
    border-radius: 30px;
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  .project-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .project-name {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 48px;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    letter-spacing: -1px;
    line-height: 1.1;
  }

  .project-description {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
    line-height: 1.6;
    max-width: 600px;
  }

  .project-arrow {
    width: 70px;
    height: 70px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 24px;
    opacity: 0;
    transform: scale(0.8) rotate(-45deg);
    transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .project-link:hover .project-arrow {
    opacity: 1;
    transform: scale(1) rotate(0deg);
  }

  /* Footer */
  .recent-projects-footer {
    position: relative;
    z-index: 10;
    padding: 40px 80px 80px;
    display: flex;
    justify-content: center;
  }

  .view-all-btn {
    display: inline-flex;
    align-items: center;
    gap: 15px;
    padding: 20px 50px;
    background: transparent;
    color: white;
    text-decoration: none;
    border-radius: 60px;
    font-size: 16px;
    font-weight: 600;
    border: 2px solid rgba(255, 255, 255, 0.2);
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
  }

  .view-all-btn::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0;
    transition: opacity 0.5s ease;
  }

  .view-all-btn:hover::before {
    opacity: 1;
  }

  .view-all-btn span,
  .view-all-btn i {
    position: relative;
    z-index: 1;
  }

  .view-all-btn:hover {
    border-color: transparent;
    transform: translateY(-3px);
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
  }

  .view-all-btn i {
    transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .view-all-btn:hover i {
    transform: translateX(8px);
  }

  /* Responsive */
  @media (max-width: 1200px) {
    .recent-projects-title {
      font-size: 56px;
    }

    .project-item {
      flex: 0 0 75vw;
    }

    .project-name {
      font-size: 40px;
    }
  }

  @media (max-width: 768px) {
    .recent-projects-header {
      padding: 100px 30px 20px;
      flex-direction: column;
      gap: 30px;
    }

    .recent-projects-title {
      font-size: 40px;
    }

    .scroll-hint {
      display: none;
    }

    .horizontal-scroll-section {
      padding: 20px 0;
    }

    .projects-track {
      padding: 0 30px;
      gap: 20px;
    }

    .project-item {
      flex: 0 0 85vw;
      height: 70vh;
      min-height: 450px;
    }

    .project-link {
      border-radius: 16px;
    }

    .project-overlay {
      padding: 40px 30px;
    }

    .project-name {
      font-size: 32px;
    }

    .project-description {
      font-size: 16px;
    }

    .project-arrow {
      width: 60px;
      height: 60px;
      font-size: 20px;
    }

    .recent-projects-footer {
      padding: 30px 30px 60px;
    }

    .view-all-btn {
      padding: 16px 40px;
      font-size: 14px;
    }
  }
</style>

<script>
  // Horizontal scroll controlado por scroll vertical - Full viewport
  ( function () {
    const section = document.querySelector( '.recent-projects' );
    const track = document.getElementById( 'projectsTrack' );
    const scrollHint = document.querySelector( '.scroll-hint' );

    if ( !section || !track ) return;

    let isScrolling = false;
    let targetScroll = 0;
    let currentScroll = 0;

    // Calcular ancho máximo de scroll
    function getMaxScroll() {
      const trackWidth = track.scrollWidth;
      const containerWidth = track.parentElement.clientWidth;
      return Math.max( 0, trackWidth - containerWidth );
    }

    // Smooth scroll con requestAnimationFrame
    function animate() {
      const diff = targetScroll - currentScroll;

      if ( Math.abs( diff ) > 0.1 ) {
        currentScroll += diff * 0.08; // Factor de suavizado
        track.style.transform = `translateX(-${currentScroll}px)`;
        requestAnimationFrame( animate );
      } else {
        currentScroll = targetScroll;
        track.style.transform = `translateX(-${targetScroll}px)`;
        isScrolling = false;
      }
    }

    // Intersection Observer para activar cuando la sección es visible
    const observer = new IntersectionObserver(
      ( entries ) => {
        entries.forEach( ( entry ) => {
          if ( entry.isIntersecting ) {
            window.addEventListener( 'scroll', handleScroll, { passive: true } );
            window.addEventListener( 'wheel', handleWheel, { passive: false } );
          } else {
            window.removeEventListener( 'scroll', handleScroll );
            window.removeEventListener( 'wheel', handleWheel );
          }
        } );
      },
      {
        threshold: 0.2,
        rootMargin: '-10% 0px -10% 0px'
      }
    );

    observer.observe( section );

    // Manejar scroll vertical
    function handleScroll() {
      const rect = section.getBoundingClientRect();
      const windowHeight = window.innerHeight;
      const sectionHeight = rect.height;

      // Calcular progreso cuando la sección está en viewport
      if ( rect.top < windowHeight && rect.bottom > 0 ) {
        // Progreso basado en posición de la sección
        const progress = Math.max(
          0,
          Math.min(
            1,
            ( windowHeight - rect.top ) / ( windowHeight + sectionHeight * 0.6 )
          )
        );

        const maxScroll = getMaxScroll();
        targetScroll = progress * maxScroll;

        // Animar hint de scroll
        if ( scrollHint ) {
          scrollHint.style.opacity = Math.max( 0, 1 - progress * 2 );
        }

        // Iniciar animación si no está en curso
        if ( !isScrolling ) {
          isScrolling = true;
          requestAnimationFrame( animate );
        }
      }
    }

    // Prevenir scroll de página mientras se hace scroll horizontal
    function handleWheel( e ) {
      const rect = section.getBoundingClientRect();
      const windowHeight = window.innerHeight;
      const maxScroll = getMaxScroll();

      // Solo prevenir scroll si la sección está en viewport y hay espacio para scroll horizontal
      if ( rect.top <= 100 && rect.bottom >= windowHeight - 100 ) {

        // Scroll hacia abajo
        if ( e.deltaY > 0 ) {
          // Si no hemos llegado al final del scroll horizontal, prevenir scroll vertical
          if ( currentScroll < maxScroll - 10 ) {
            e.preventDefault();
            targetScroll = Math.min( maxScroll, targetScroll + e.deltaY * 2 );

            if ( !isScrolling ) {
              isScrolling = true;
              requestAnimationFrame( animate );
            }
          }
          // Si ya llegamos al final, permitir scroll vertical normal
        }
        // Scroll hacia arriba
        else if ( e.deltaY < 0 ) {
          // Si no estamos al inicio del scroll horizontal, prevenir scroll vertical
          if ( currentScroll > 10 ) {
            e.preventDefault();
            targetScroll = Math.max( 0, targetScroll + e.deltaY * 2 );

            if ( !isScrolling ) {
              isScrolling = true;
              requestAnimationFrame( animate );
            }
          }
          // Si ya estamos al inicio, permitir scroll vertical normal
        }
      }
    }

    // Soporte táctil para móviles
    let touchStartX = 0;
    let touchStartScroll = 0;

    track.addEventListener( 'touchstart', ( e ) => {
      touchStartX = e.touches[ 0 ].clientX;
      touchStartScroll = currentScroll;
    }, { passive: true } );

    track.addEventListener( 'touchmove', ( e ) => {
      const touchX = e.touches[ 0 ].clientX;
      const diff = touchStartX - touchX;
      const maxScroll = getMaxScroll();

      targetScroll = Math.max( 0, Math.min( maxScroll, touchStartScroll + diff ) );

      if ( !isScrolling ) {
        isScrolling = true;
        requestAnimationFrame( animate );
      }
    }, { passive: true } );

    // Recalcular en resize
    let resizeTimeout;
    window.addEventListener( 'resize', () => {
      clearTimeout( resizeTimeout );
      resizeTimeout = setTimeout( () => {
        const maxScroll = getMaxScroll();
        if ( targetScroll > maxScroll ) {
          targetScroll = maxScroll;
          if ( !isScrolling ) {
            isScrolling = true;
            requestAnimationFrame( animate );
          }
        }
      }, 250 );
    } );

    // Animación de entrada para los proyectos
    const projectItems = document.querySelectorAll( '.project-item' );
    const itemObserver = new IntersectionObserver(
      ( entries ) => {
        entries.forEach( ( entry, index ) => {
          if ( entry.isIntersecting ) {
            setTimeout( () => {
              entry.target.style.opacity = '1';
              entry.target.style.transform = 'translateY(0)';
            }, index * 100 );
          }
        } );
      },
      { threshold: 0.3 }
    );

    projectItems.forEach( ( item ) => {
      item.style.opacity = '0';
      item.style.transform = 'translateY(30px)';
      item.style.transition = 'all 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
      itemObserver.observe( item );
    } );
  } )();
</script>
