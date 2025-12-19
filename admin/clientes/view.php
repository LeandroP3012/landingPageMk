<section class="client-page">

    <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= $client['logo'] ?>"
        alt="<?= htmlspecialchars($client['name']) ?>">

    <h1><?= htmlspecialchars($client['name']) ?></h1>

    <p><?= htmlspecialchars($client['short_description']) ?></p>

    <div class="client-content">
        <?= nl2br(htmlspecialchars($client['description'])) ?>
    </div>

</section>