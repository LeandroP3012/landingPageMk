<?php
// Determinar la página actual para resaltar en el menú
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<aside class="sidebar">
  <div class="sidebar-header">
    <a href="../index.php" class="sidebar-logo">
      <i class="fas fa-shield-alt"></i>
      <span>Admin Panel</span>
    </a>
  </div>

  <nav>
    <ul class="sidebar-nav">
      <li class="nav-section">Principal</li>

      <li class="nav-item">
        <a href="<?= BASE_URL ?>/admin/index.php" class="nav-link <?= ($current_page == 'index.php' && $current_dir == 'admin') ? 'active' : '' ?>">
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="nav-section">Gestión</li>

      <li class="nav-item">
        <a href="<?= BASE_URL ?>/admin/clientes/index.php" class="nav-link <?= ($current_dir == 'clientes') ? 'active' : '' ?>">
          <i class="fas fa-users"></i>
          <span>Gestión de Clientes</span>
        </a>
      </li>

      <li class="nav-item">
        <a href="<?= BASE_URL ?>/admin/team/index.php" class="nav-link <?= ($current_dir == 'team') ? 'active' : '' ?>">
          <i class="fas fa-user-friends"></i>
          <span>Gestión de Equipo</span>
        </a>
      </li>

      <!-- Aquí puedes agregar más opciones en el futuro -->
      <!--
            <li class="nav-item">
                <a href="<?= BASE_URL ?>/admin/proyectos/index.php" class="nav-link">
                    <i class="fas fa-project-diagram"></i>
                    <span>Proyectos</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= BASE_URL ?>/admin/usuarios/index.php" class="nav-link">
                    <i class="fas fa-user-shield"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            -->

      <li class="nav-section">Configuración</li>

      <li class="nav-item">
        <a href="<?= BASE_URL ?>/admin/configuracion.php" class="nav-link <?= ($current_page == 'configuracion.php') ? 'active' : '' ?>">
          <i class="fas fa-cog"></i>
          <span>Configuración</span>
        </a>
      </li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <div class="user-info">
      <div class="user-avatar">
        <i class="fas fa-user"></i>
      </div>
      <div class="user-details">
        <div class="user-name">Administrador</div>
        <div class="user-role">Super Admin</div>
      </div>
    </div>
    <a href="<?= BASE_URL ?>/admin/login.php" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i>
      <span>Cerrar Sesión</span>
    </a>
  </div>
</aside>
