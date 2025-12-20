<?php
session_start();
if (!isset($_SESSION['admin_logged'])) {
  header('Location: login.php');
  exit;
}

require_once __DIR__ . '/../app/config/app.php';
require_once __DIR__ . '/../app/config/database.php';

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $current_password = $_POST['current_password'] ?? '';
  $new_password = $_POST['new_password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';

  // Validaciones
  if (empty($current_password)) {
    $errors[] = "La contraseña actual es requerida.";
  }

  if (empty($new_password)) {
    $errors[] = "La nueva contraseña es requerida.";
  } elseif (strlen($new_password) < 6) {
    $errors[] = "La nueva contraseña debe tener al menos 6 caracteres.";
  }

  if ($new_password !== $confirm_password) {
    $errors[] = "Las contraseñas no coinciden.";
  }

  if (empty($errors)) {
    // Obtener el usuario actual (asumimos que hay solo un admin con ID 1)
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE id = 1");
    $stmt->execute();
    $admin = $stmt->fetch();

    if ($admin && password_verify($current_password, $admin['password'])) {
      // La contraseña actual es correcta, actualizar
      $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE id = 1");

      if ($stmt->execute([$new_password_hash])) {
        $success = "Contraseña actualizada correctamente.";
        // Limpiar los campos
        $_POST = [];
      } else {
        $errors[] = "Error al actualizar la contraseña.";
      }
    } else {
      $errors[] = "La contraseña actual es incorrecta.";
    }
  }
}

// Obtener información del usuario
$stmt = $pdo->prepare("SELECT username, created_at FROM admins WHERE id = 1");
$stmt->execute();
$admin = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración - Panel de Administración</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/admin/assets/css/components.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #000000;
      min-height: 100vh;
    }

    .container {
      width: 100%;
      padding: 20px;
      max-width: 900px;
    }

    .header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 15px;
      margin-bottom: 30px;
      box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    }

    .header h1 {
      font-size: 28px;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .header h1 i {
      font-size: 32px;
    }

    .config-grid {
      display: grid;
      gap: 25px;
    }

    .config-card {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
    }

    .card-title {
      color: white;
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      padding-bottom: 15px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-title i {
      color: #667eea;
      font-size: 24px;
    }

    .alert {
      padding: 15px 20px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .alert-success {
      background: rgba(76, 175, 80, 0.1);
      border: 1px solid rgba(76, 175, 80, 0.3);
      color: #4caf50;
    }

    .alert-error {
      background: rgba(211, 47, 47, 0.1);
      border: 1px solid rgba(211, 47, 47, 0.3);
      color: #ff6b6b;
    }

    .alert i {
      font-size: 20px;
    }

    .info-grid {
      display: grid;
      gap: 20px;
    }

    .info-item {
      display: flex;
      align-items: center;
      gap: 15px;
      padding: 15px;
      background: rgba(255, 255, 255, 0.03);
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .info-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 24px;
    }

    .info-content {
      flex: 1;
    }

    .info-label {
      color: rgba(255, 255, 255, 0.6);
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 5px;
    }

    .info-value {
      color: white;
      font-size: 16px;
      font-weight: 600;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      color: rgba(255, 255, 255, 0.9);
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .form-group label i {
      margin-right: 6px;
      color: #667eea;
    }

    .password-input-wrapper {
      position: relative;
    }

    .form-control {
      width: 100%;
      padding: 12px 45px 12px 16px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 10px;
      color: white;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      outline: none;
      border-color: #667eea;
      background: rgba(255, 255, 255, 0.12);
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.4);
    }

    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: rgba(255, 255, 255, 0.5);
      cursor: pointer;
      padding: 8px;
      transition: color 0.3s ease;
    }

    .toggle-password:hover {
      color: #667eea;
    }

    .password-strength {
      margin-top: 8px;
      height: 4px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 2px;
      overflow: hidden;
    }

    .password-strength-bar {
      height: 100%;
      width: 0;
      transition: all 0.3s ease;
      border-radius: 2px;
    }

    .strength-weak {
      width: 33%;
      background: #ff6b6b;
    }

    .strength-medium {
      width: 66%;
      background: #ffa726;
    }

    .strength-strong {
      width: 100%;
      background: #4caf50;
    }

    .password-hint {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.5);
      margin-top: 5px;
    }

    .btn-submit {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 14px 32px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
      width: 100%;
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
    }

    @media (max-width: 768px) {
      .config-card {
        padding: 20px;
      }

      .header {
        padding: 20px;
      }

      .header h1 {
        font-size: 22px;
      }
    }
  </style>
</head>

<body>
  <?php include __DIR__ . '/components/sidebar.php'; ?>

  <div class="main-content">
    <div class="container">
      <div class="header">
        <h1>
          <i class="fas fa-cog"></i>
          Configuración del Sistema
        </h1>
      </div>

      <div class="config-grid">
        <!-- Información del Usuario -->
        <div class="config-card">
          <h2 class="card-title">
            <i class="fas fa-user-circle"></i>
            Información del Administrador
          </h2>

          <div class="info-grid">
            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-user"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Usuario</div>
                <div class="info-value"><?= htmlspecialchars($admin['username']) ?></div>
              </div>
            </div>

            <div class="info-item">
              <div class="info-icon">
                <i class="fas fa-calendar-alt"></i>
              </div>
              <div class="info-content">
                <div class="info-label">Cuenta Creada</div>
                <div class="info-value"><?= date('d/m/Y H:i', strtotime($admin['created_at'])) ?></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Cambiar Contraseña -->
        <div class="config-card">
          <h2 class="card-title">
            <i class="fas fa-lock"></i>
            Cambiar Contraseña
          </h2>

          <?php if ($success): ?>
            <div class="alert alert-success">
              <i class="fas fa-check-circle"></i>
              <span><?= htmlspecialchars($success) ?></span>
            </div>
          <?php endif; ?>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
              <i class="fas fa-exclamation-triangle"></i>
              <div>
                <?php foreach ($errors as $error): ?>
                  <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <form method="POST">
            <div class="form-group">
              <label for="current_password">
                <i class="fas fa-key"></i>
                Contraseña Actual
              </label>
              <div class="password-input-wrapper">
                <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Ingresa tu contraseña actual" required>
                <button type="button" class="toggle-password" onclick="togglePassword('current_password')">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <div class="form-group">
              <label for="new_password">
                <i class="fas fa-lock"></i>
                Nueva Contraseña
              </label>
              <div class="password-input-wrapper">
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Ingresa la nueva contraseña" required>
                <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
              <div class="password-strength">
                <div class="password-strength-bar" id="strength-bar"></div>
              </div>
              <div class="password-hint">La contraseña debe tener al menos 6 caracteres</div>
            </div>

            <div class="form-group">
              <label for="confirm_password">
                <i class="fas fa-lock"></i>
                Confirmar Nueva Contraseña
              </label>
              <div class="password-input-wrapper">
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirma la nueva contraseña" required>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn-submit">
              <i class="fas fa-save"></i>
              Actualizar Contraseña
            </button>
          </form>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/components/footer.php'; ?>
  </div>

  <script>
    function togglePassword( inputId ) {
      const input = document.getElementById( inputId );
      const button = input.nextElementSibling;
      const icon = button.querySelector( 'i' );

      if ( input.type === 'password' ) {
        input.type = 'text';
        icon.classList.remove( 'fa-eye' );
        icon.classList.add( 'fa-eye-slash' );
      } else {
        input.type = 'password';
        icon.classList.remove( 'fa-eye-slash' );
        icon.classList.add( 'fa-eye' );
      }
    }

    // Password strength indicator
    document.getElementById( 'new_password' ).addEventListener( 'input', function ( e ) {
      const password = e.target.value;
      const strengthBar = document.getElementById( 'strength-bar' );

      let strength = 0;
      if ( password.length >= 6 ) strength++;
      if ( password.length >= 10 ) strength++;
      if ( /[A-Z]/.test( password ) ) strength++;
      if ( /[0-9]/.test( password ) ) strength++;
      if ( /[^A-Za-z0-9]/.test( password ) ) strength++;

      strengthBar.className = 'password-strength-bar';

      if ( strength <= 2 ) {
        strengthBar.classList.add( 'strength-weak' );
      } else if ( strength <= 4 ) {
        strengthBar.classList.add( 'strength-medium' );
      } else {
        strengthBar.classList.add( 'strength-strong' );
      }
    } );
  </script>
</body>

</html>
