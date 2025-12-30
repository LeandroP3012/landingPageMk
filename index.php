<?php
// bd global
require_once __DIR__ . '/app/config/database.php';

// modelos necesarios
require_once __DIR__ . '/app/models/Client.php';

require_once __DIR__ . '/app/config/app.php';

?>

<?php include 'components/header.php'; ?>
<?php include 'components/hero.php'; ?>
<?php include 'components/impact.php'; ?>
<?php include 'components/marquee.php'; ?>
<?php include 'components/recent-projects.php'; ?>
<?php include 'components/services.php'; ?>
<?php include 'components/contact-form.php'; ?>
<?php include 'components/footer.php'; ?>