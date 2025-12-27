<footer class="site-footer" id="contacto">
  <div class="footer-container">
    <h2 class="footer-title">
      <span id="typewriter-text"></span><span class="cursor-blink">.</span>
    </h2>
    <a href="https://wa.me/51946689126?text=Hola,%20me%20gustar%C3%ADa%20solicitar%20un%20diagn%C3%B3stico"
      target="_blank" class="footer-btn">
      Solicita un diagnóstico
    </a>
    <div class="footer-social">
      <a href="https://wa.me/51946689126" target="_blank" class="social-icon whatsapp" title="WhatsApp: 946689126">
        <i class="fab fa-whatsapp"></i>
      </a>
      <a href="mailto:comercial@weare.pe" class="social-icon gmail" title="Email: comercial@weare.pe">
        <i class="fas fa-envelope"></i>
      </a>
    </div>
  </div>
</footer>

<script src="/landingPageMk/assets/js/main.js"></script>
<script>
  // Animación de escritura letra por letra en el footer
  document.addEventListener('DOMContentLoaded', function () {
    const footerText = "Aceleramos ventas con\ncreatividad y data";
    const typewriterElement = document.getElementById('typewriter-text');
    let hasAnimated = false;

    function typeWriter(text, element, speed = 50) {
      let i = 0;
      element.innerHTML = '';

      function type() {
        if (i < text.length) {
          if (text.charAt(i) === '\n') {
            element.innerHTML += '<br>';
          } else {
            element.innerHTML += text.charAt(i);
          }
          i++;
          setTimeout(type, speed);
        }
      }

      type();
    }

    // Intersection Observer para detectar cuando el footer es visible
    const footerObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && !hasAnimated) {
          hasAnimated = true;
          setTimeout(() => {
            typeWriter(footerText, typewriterElement, 80);
          }, 200);
        }
      });
    }, {
      threshold: 0.5,
      rootMargin: '-100px 0px -100px 0px'
    });

    const footer = document.querySelector('.site-footer');
    if (footer) {
      footerObserver.observe(footer);
    }
  });
</script>
</body>

</html>