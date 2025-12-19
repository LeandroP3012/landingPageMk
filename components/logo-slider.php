<?php
// Array de logos - puedes agregar o quitar logos aquí
$logos = [
  ['url' => 'assets/img/logos/logo-1.jpg', 'width' => '30', 'height' => '35'],
  ['url' => 'assets/img/logos/logo-2.svg', 'width' => '65', 'height' => '49'],
  ['url' => 'assets/img/logos/logo-3.svg', 'width' => '120', 'height' => '28'],
  ['url' => 'assets/img/logos/logo-4.svg', 'width' => '42', 'height' => '41'],
  ['url' => 'assets/img/logos/logo-5.svg', 'width' => '108', 'height' => '30'],
  ['url' => 'assets/img/logos/logo-6.svg', 'width' => '39', 'height' => '46'],
  ['url' => 'assets/img/logos/logo-7.svg', 'width' => '75', 'height' => '30'],
  ['url' => 'assets/img/logos/logo-8.svg', 'width' => '36', 'height' => '36'],
  ['url' => 'assets/img/logos/logo-9.svg', 'width' => '77', 'height' => '26'],
  ['url' => 'assets/img/logos/logo-10.svg', 'width' => '131', 'height' => '38'],
  ['url' => 'assets/img/logos/logo-11.svg', 'width' => '45', 'height' => '42'],
  ['url' => 'assets/img/logos/logo-12.svg', 'width' => '27', 'height' => '51'],
  ['url' => 'assets/img/logos/logo-13.svg', 'width' => '106', 'height' => '19'],
  ['url' => 'assets/img/logos/logo-14.svg', 'width' => '60', 'height' => '36'],
  ['url' => 'assets/img/logos/logo-15.svg', 'width' => '30', 'height' => '35'],
  ['url' => 'assets/img/logos/logo-16.svg', 'width' => '55', 'height' => '31'],
  ['url' => 'assets/img/logos/logo-17.svg', 'width' => '22', 'height' => '44'],
  ['url' => 'assets/img/logos/logo-18.svg', 'width' => '60', 'height' => '32'],
  ['url' => 'assets/img/logos/logo-19.svg', 'width' => '33', 'height' => '35'],
  ['url' => 'assets/img/logos/logo-20.svg', 'width' => '26', 'height' => '40'],
  ['url' => 'assets/img/logos/logo-21.svg', 'width' => '33', 'height' => '40'],
];
?>

<section id="logo-slider" class="bg-white py-32 md:py-14">
  <div class="overflow-hidden relative px-4" style="height: 5.75rem; padding-top: 1.15rem; padding-bottom: 1.15rem;">
    <div class="marquee-track logo-slider-track">

      <?php foreach ($logos as $logo): ?>
        <div class="logo-item">
          <img alt="Logo" loading="lazy" width="<?php echo $logo['width']; ?>" height="<?php echo $logo['height']; ?>" decoding="async" class="object-contain object-center h-auto" src="<?php echo $logo['url']; ?>" style="color: transparent;">
        </div>
      <?php endforeach; ?>

      <!-- Duplicado para loop infinito -->
      <?php foreach ($logos as $logo): ?>
        <div class="logo-item">
          <img alt="Logo" loading="lazy" width="<?php echo $logo['width']; ?>" height="<?php echo $logo['height']; ?>" decoding="async" class="object-contain object-center h-auto" src="<?php echo $logo['url']; ?>" style="color: transparent;">
        </div>
      <?php endforeach; ?>

    </div>
  </div>
</section>
