<?php
session_start();

// Si ya está logueado, redirigir al panel de administración
if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
  header('Location: clientes/index.php');
  exit;
}

// Si no está logueado, redirigir al login
header('Location: login.php');
exit;
