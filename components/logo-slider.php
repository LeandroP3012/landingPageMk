<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/ClientLogo.php';

$logoModel = new ClientLogo($pdo);
$logos = $logoModel->all();
?>


<section id="logo-slider" class="bg-[#1a1a2e] py-12 md:py-14">
  <div class="overflow-hidden relative px-4" style="height: 5.75rem; padding-top: 1.15rem; padding-bottom: 1.15rem;">
    <div class="marquee-track logo-slider-track">

      <?php foreach ($logos as $logo): ?>
          <div class="logo-item">
            <img src="<?= BASE_URL ?>/storage/uploads/clients/logos/<?= htmlspecialchars($logo['logo']) ?>"
              alt="Logo cliente" width="<?= (int) $logo['width'] ?>" height="<?= (int) $logo['height'] ?>" loading="lazy"
              class="object-contain object-center h-auto">
          </div>
      <?php endforeach; ?>


    </div>
  </div>
</section>