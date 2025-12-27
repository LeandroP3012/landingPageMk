<section class="contact-section">
    <div class="contact-container">

        <div class="contact-text" data-animate="left">
            <span class="contact-label">CONTACTO</span>
            <h2>Hablemos de tu<br>próximo proyecto</h2>
            <p>Cuéntanos qué tienes en mente y te responderemos lo antes posible.</p>

            <div class="contact-info">
                <div class="info-item" style="animation-delay: 0.2s">
                    <div class="info-icon">✉</div>
                    <span>contacto@empresa.com</span>
                </div>
                <div class="info-item" style="animation-delay: 0.3s">
                    <div class="info-icon">📍</div>
                    <span>Ciudad, País</span>
                </div>
            </div>
        </div>

        <form class="contact-form" id="contactForm" data-endpoint="<?= BASE_URL ?>/app/config/contact-send.php" data-animate="right">

            <div class="field" style="animation-delay: 0.1s">
                <input type="text" name="name" required autocomplete="name">
                <label>Nombre completo</label>
                <div class="field-line"></div>
            </div>

            <div class="field" style="animation-delay: 0.2s">
                <input type="email" name="email" required autocomplete="email">
                <label>Correo electrónico</label>
                <div class="field-line"></div>
            </div>

            <div class="field" style="animation-delay: 0.3s">
                <textarea name="message" rows="5" required></textarea>
                <label>Cuéntanos sobre tu proyecto</label>
                <div class="field-line"></div>
            </div>

            <button type="submit" class="submit-btn" style="animation-delay: 0.4s">
                <span class="btn-text">Enviar mensaje</span>
                <span class="btn-icon">→</span>
                <div class="btn-ripple"></div>
            </button>

            <p id="formStatus" class="form-status"></p>
        </form>

    </div>
</section>

<script>
    // Animaciones al hacer scroll
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver( ( entries ) => {
        entries.forEach( entry => {
            if ( entry.isIntersecting ) {
                entry.target.classList.add( 'animate-in' );
            }
        } );
    }, observerOptions );

    const animateElements = document.querySelectorAll( '[data-animate]' );
    animateElements.forEach( el => observer.observe( el ) );

    // Efecto ripple en el botón
    const submitBtn = document.querySelector( '.submit-btn' );
    if ( submitBtn ) {
        submitBtn.addEventListener( 'click', function ( e ) {
            const ripple = this.querySelector( '.btn-ripple' );
            ripple.style.left = e.offsetX + 'px';
            ripple.style.top = e.offsetY + 'px';
            ripple.classList.add( 'active' );

            setTimeout( () => {
                ripple.classList.remove( 'active' );
            }, 600 );
        } );
    }

    // Animación de los campos al hacer focus
    const fields = document.querySelectorAll( '.field' );
    fields.forEach( field => {
        const input = field.querySelector( 'input, textarea' );
        const line = field.querySelector( '.field-line' );

        input.addEventListener( 'focus', () => {
            field.classList.add( 'focused' );
        } );

        input.addEventListener( 'blur', () => {
            if ( !input.value ) {
                field.classList.remove( 'focused' );
            }
        } );
    } );
</script>
