<?php
require_once 'app/config/database.php';
require_once 'app/models/Team.php';
require_once 'app/models/TeamCover.php';

$teamModel = new Team($pdo);
$team = $teamModel->all();

$coverModel = new TeamCover($pdo);
$cover = $coverModel->get();
?>

<section class="team" id="equipo">
  <div class="team-container">

    <div class="team-header">
      <div class="team-category">
        <span class="category-top">Gestión</span>
        <span class="category-bottom">& Dirección</span>
      </div>
      <h1 class="team-main-title">HUB (NOSOTROS)</h1>
    </div>

    <?php if ($cover && ($cover['image_top'] || $cover['image_bottom'])): ?>
      <div class="team-cover" id="teamSlider">
        <div class="team-cover-inner">

          <!-- Imagen inferior -->
          <?php if (!empty($cover['image_bottom'])): ?>
            <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_bottom'] ?>" class="cover-image" alt="Equipo – imagen base">
          <?php endif; ?>

          <!-- Imagen superior -->
          <?php if (!empty($cover['image_top'])): ?>
            <div class="cover-top" id="coverTop">
              <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $cover['image_top'] ?>" alt="Equipo – imagen superior">
            </div>
          <?php endif; ?>

          <div class="cover-divider" id="coverDivider"></div>

        </div>
      </div>
    <?php endif; ?>

    <div class="team-content">
      <div class="team-brand">
        <h2 class="brand-title">Equipo</h2>
      </div>

      <div class="team-grid">
        <?php foreach ($team as $member): ?>
          <article class="team-member">
            <div class="team-photo">
              <img src="<?= BASE_URL ?>/storage/uploads/team/<?= $member['photo'] ?>" alt="<?= htmlspecialchars($member['name']) ?>">
            </div>
            <div class="team-info">
              <h3><?= htmlspecialchars($member['name']) ?></h3>
              <p><?= htmlspecialchars($member['role']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="team-footer">
      <div class="team-category">
        <span class="category-top">HUB</span>
        <span class="category-bottom">Creativo</span>
      </div>
    </div>

  </div>
</section>
