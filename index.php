<?php
// bd global
require_once __DIR__ . '/app/config/database.php';

// modelos necesarios
require_once __DIR__ . '/app/models/Client.php';

require_once __DIR__ . '/app/config/app.php';

?>

<?php include 'components/header.php'; ?>
<?php include 'components/hero.php'; ?>
<?php include 'components/marquee.php'; ?>
<?php include 'components/clients.php'; ?>
<?php include 'components/team.php'; ?>
<?php include 'components/footer.php'; ?>