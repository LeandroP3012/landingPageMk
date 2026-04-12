<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Landing MK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/storage/uploads/components/ICO.JPG">
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="/landingPageMk/assets/css/styles.css">

  <!-- Font Awesome -->
  <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap"
    rel="stylesheet">
</head>

<body>

  <div class="section-transition" aria-hidden="true"></div>

  <header class="site-header">
    <div class="header-container">

      <!-- Logo -->
      <div class="logo">
        <a href="<?= BASE_URL ?>">
          <img src="<?= BASE_URL ?>/storage/uploads/components/logo.png" alt="Logo LandingMK" class="logo-img" style="  height: 70px;   /* ajusta el tamaño */
  width: auto;
  display: block;">
        </a>
      </div>


      <!-- Navegación -->
      <nav class="nav">
        <a href="<?= BASE_URL ?>">Inicio</a>
        <a href="<?= BASE_URL ?>/proyectos.php">Proyectos</a>
        <a href="<?= BASE_URL ?>/nosotros.php">Nosotros</a>

        <a href="#contact-form" class="btn-nav">Contacto</a>
      </nav>

      <!-- Botón móvil -->
      <div class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
      </div>

    </div>
  </header>

  <script>
    // Efecto blur en navbar al hacer scroll
    const header = document.querySelector('.site-header');
    const heroSection = document.querySelector('.hero-section');

    function handleHeaderScroll() {
      const scrollPosition = window.scrollY;

      // Si existe la sección hero, usar el 80% de su altura
      // Si no existe, activar después de 300px de scroll
      let triggerPoint = 300;

      if (heroSection) {
        const heroHeight = heroSection.offsetHeight;
        triggerPoint = heroHeight * 0.8;
        console.log('Hero height:', heroHeight, 'Trigger at:', triggerPoint, 'Current scroll:', scrollPosition);
      }

      // Agregar clase scrolled cuando se pase el punto de activación
      if (scrollPosition > triggerPoint) {
        header.classList.add('scrolled');
        console.log('SCROLLED CLASS ADDED');
      } else {
        header.classList.remove('scrolled');
        console.log('SCROLLED CLASS REMOVED');
      }
    }

    // Ejecutar al cargar y al hacer scroll
    window.addEventListener('scroll', handleHeaderScroll);
    handleHeaderScroll();

    // Debug: Verificar si el header tiene la clase
    setInterval(() => {
      if (header.classList.contains('scrolled')) {
        console.log('Header tiene clase scrolled');
      }
    }, 2000);

    // Smooth scroll para enlaces de anclaje
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');

        // Verificar que el href no sea solo '#'
        if (href && href !== '#') {
          e.preventDefault();

          const targetId = href.substring(1);
          const targetElement = document.getElementById(targetId);

          if (targetElement) {
            // Calcular posición con offset para el header
            const headerHeight = header.offsetHeight;
            const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - headerHeight - 20;

            // Scroll suave
            window.scrollTo({
              top: targetPosition,
              behavior: 'smooth'
            });
          }
        }
      });
    });
  </script>
