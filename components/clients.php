<?php
require_once __DIR__ . '/../app/config/app.php';

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/models/Client.php';

$clientModel = new Client($pdo);
$clients = $clientModel->all();
?>

<section class="clients" id="clientes">

  <div class="clients-container">

    <!-- Header -->
    <div class="clients-header">
      <h2 class="clients-main-title">Todos los Proyectos</h2>
    </div>

    <!-- Stacking Cards -->
    <div class="stacking-cards-wrapper">
      <?php foreach ($clients as $index => $client): ?>
        <article class="client-card" data-card-index="<?= $index ?>">
          <a href="<?= BASE_URL ?>/clientes/index.php?slug=<?= urlencode($client['slug']) ?>" class="card-link">
            <div class="client-card-image">
              <img src="<?= BASE_URL ?>/storage/uploads/clients/<?= htmlspecialchars($client['logo']) ?>" alt="<?= htmlspecialchars($client['name']) ?>">
              <div class="client-card-overlay"></div>
            </div>
            <div class="client-card-info">
              <span class="card-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
              <h3><?= htmlspecialchars($client['name']) ?></h3>
              <p><?= htmlspecialchars($client['short_description'] ?? 'Proyecto de Marca') ?></p>
              <div class="card-arrow">
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>
          </a>
        </article>
      <?php endforeach; ?>
    </div>

  </div>

</section>

<style>
  /* Stacking Cards Effect */
  .clients {
    background: linear-gradient(135deg, #0a0a1e 0%, #1a0a2e 50%, #0f1a2e 100%);
    min-height: 300vh;
    position: relative;
    padding: 120px 0 100px;
    overflow: hidden;
  }

  .clients-container {
    max-width: 1800px;
    margin: auto;
  }

  /* Header */
  .clients-header {
    position: relative;
    text-align: center;
    padding: 0 20px 0px;
    margin-bottom: 60px;
  }

  .clients-main-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 80px;
    font-weight: 700;
    color: #ffffff;
    margin: 0 auto;
    letter-spacing: -2px;
    line-height: 1;
    position: relative;
    display: inline-block;
    animation: titleReveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    opacity: 0;
  }

  @keyframes titleReveal {
    0% {
      opacity: 0;
      transform: translateY(30px);
      filter: blur(10px);
    }

    100% {
      opacity: 1;
      transform: translateY(0);
      filter: blur(0);
    }
  }

  .clients-main-title::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, #667eea, #764ba2, transparent);
    animation: lineExpand 1s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
  }

  @keyframes lineExpand {
    0% {
      width: 0;
    }

    100% {
      width: 200px;
    }
  }

  /* Stacking Cards */
  .stacking-cards-wrapper {
    position: relative;
    padding: 0 40px;
    max-width: 2000px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .client-card {
    position: relative;
    width: 100%;
    max-width: 1100px;
    height: 65vh;
    min-height: 500px;
    margin-bottom: 60px;
    transform: translateX(400%);
    transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s ease;
    opacity: 0;
  }

  .client-card:nth-of-type(even) {
    transform: translateX(-400%);
  }

  .client-card.show {
    transform: translateX(0);
    opacity: 1;
  }

  .client-card.show {
    transform: translateX(0);
    opacity: 1;
  }

  .card-link {
    display: block;
    width: 100%;
    height: 100%;
    text-decoration: none;
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .card-link:hover {
    transform: translateY(-8px);
    box-shadow: 0 30px 80px rgba(102, 126, 234, 0.3);
  }

  .client-card-image {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background: #111;
  }

  .client-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
  }

  .card-link:hover .client-card-image img {
    transform: scale(1.05);
  }

  .client-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top,
        rgba(0, 0, 0, 0.9) 0%,
        rgba(0, 0, 0, 0.6) 40%,
        rgba(0, 0, 0, 0.2) 70%,
        transparent 100%);
    opacity: 1;
    transition: background 0.5s ease;
  }

  .card-link:hover .client-card-overlay {
    background: linear-gradient(to top,
        rgba(102, 126, 234, 0.95) 0%,
        rgba(118, 75, 162, 0.85) 40%,
        rgba(0, 0, 0, 0.4) 70%,
        transparent 100%);
  }

  .client-card-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 50px;
    z-index: 2;
    display: flex;
    flex-direction: column;
    gap: 12px;
    transform: translateY(0);
    transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .card-link:hover .client-card-info {
    transform: translateY(-8px);
  }

  .card-number {
    font-size: 14px;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.6);
    letter-spacing: 2px;
  }

  .client-card-info h3 {
    color: #fff;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 42px;
    font-weight: 700;
    margin: 0;
    letter-spacing: -1px;
    line-height: 1.1;
  }

  .client-card-info p {
    color: rgba(255, 255, 255, 0.85);
    font-size: 18px;
    margin: 0;
    line-height: 1.6;
    max-width: 600px;
  }

  .card-arrow {
    width: 70px;
    height: 70px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #667eea;
    font-size: 24px;
    opacity: 0;
    transform: scale(0.8) translateY(10px);
    transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
  }

  .card-link:hover .card-arrow {
    opacity: 1;
    transform: scale(1) translateY(0);
  }

  /* Responsive */
  @media (max-width: 1200px) {
    .clients-main-title {
      font-size: 64px;
    }

    .client-card-info h3 {
      font-size: 36px;
    }
  }

  @media (max-width: 768px) {
    .clients {
      padding: 100px 0 60px;
    }

    .clients-header {
      padding: 0 20px 20px;
      margin-bottom: 30px;
    }

    .clients-main-title {
      font-size: 42px;
    }

    .clients-main-title::after {
      width: 150px;
    }

    .stacking-cards-wrapper {
      padding: 20px 20px 0;
    }

    .client-card {
      top: 0;
      height: 60vh;
      min-height: 400px;
      margin-bottom: 30px;
    }

    .card-link {
      border-radius: 16px;
    }

    .client-card-info {
      padding: 35px;
    }

    .client-card-info h3 {
      font-size: 28px;
    }

    .client-card-info p {
      font-size: 16px;
    }

    .card-arrow {
      width: 60px;
      height: 60px;
      font-size: 20px;
    }
  }
</style>

<script>
  // Animación de entrada lateral al hacer scroll
  ( function () {
    const cards = document.querySelectorAll( '.client-card' );

    const checkCards = () => {
      const triggerBottom = ( window.innerHeight / 5 ) * 4;

      cards.forEach( ( card ) => {
        const cardTop = card.getBoundingClientRect().top;

        if ( cardTop < triggerBottom ) {
          card.classList.add( 'show' );
        } else {
          card.classList.remove( 'show' );
        }
      } );
    };

    checkCards();
    window.addEventListener( 'scroll', checkCards );
  } )();
</script>
