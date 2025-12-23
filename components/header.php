<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Landing MK</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- CSS -->
  <link rel="stylesheet" href="/landingPageMk/assets/css/styles.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
        <a href="<?= BASE_URL ?>">Landing<span>MK</span></a>
      </div>

      <!-- Navegación -->
      <nav class="nav">
        <a href="<?= BASE_URL ?>">Inicio</a>
        <a href="<?= BASE_URL ?>/proyectos.php">Clientes</a>
        <a href="<?= BASE_URL ?>/nosotros.php">Nosotros</a>

        <a href="#contacto" class="btn-nav">Contacto</a>
      </nav>

      <!-- Botón móvil -->
      <div class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
      </div>

    </div>
  </header>