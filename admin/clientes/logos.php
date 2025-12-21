<?php
require_once __DIR__ . '/../../app/config/app.php';
require_once __DIR__ . '/../../app/config/database.php';
require_once ROOT_PATH . '/app/models/Client.php';
require_once ROOT_PATH . '/app/models/ClientLogo.php';

session_start();


if (!isset($_SESSION['admin_logged'])) {
    header('Location: ../login.php');
    exit;

}

$clientId = $_GET['client_id'] ?? null;

if (!$clientId) {
    die('ID de cliente no proporcionado');
}


$clientModel = new Client($pdo);
$logoModel = new ClientLogo($pdo);

$client = $clientModel->findById((int) $clientId);
$logos = $logoModel->getByClient((int) $clientId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_FILES['logo']['name'])) {
        $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('logo_') . '.' . $ext;

        move_uploaded_file(
            $_FILES['logo']['tmp_name'],
            ROOT_PATH . '/storage/uploads/clients/logos/' . $filename
        );

        $logoModel->create($clientId, $filename);
        header("Location: logos.php?client_id=$clientId");
        exit;
    }
}
?>

<h1>Logos de <?= htmlspecialchars($client['name']) ?></h1>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="logo" required>
    <button type="submit">Agregar al carrusel</button>
</form>

<hr>

<?php foreach ($logos as $logo): ?>
    <div style="margin:10px 0">
        <img src="<?= BASE_URL ?>/storage/uploads/clients/logos/<?= $logo['logo'] ?>" width="120">
        <a href="logo_delete.php?id=<?= $logo['id'] ?>&client_id=<?= $clientId ?>"
            onclick="return confirm('¿Eliminar logo?')">Eliminar</a>
    </div>
<?php endforeach; ?>