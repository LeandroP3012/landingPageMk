<section class="contact-section">
    <div class="contact-container">

        <div class="contact-text">
            <span class="contact-label">CONTACTO</span>
            <h2>Hablemos de tu<br>próximo proyecto</h2>
            <p>Cuéntanos qué tienes en mente y te responderemos lo antes posible.</p>
        </div>

        <form class="contact-form" id="contactForm" data-endpoint="<?= BASE_URL ?>/app/config/contact-send.php">

            <div class="field">
                <input type="text" name="name" required>
                <label>Nombre</label>
            </div>

            <div class="field">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>

            <div class="field">
                <textarea name="message" rows="4" required></textarea>
                <label>Mensaje</label>
            </div>

            <button type="submit">
                <span>Enviar mensaje</span>
            </button>

            <p id="formStatus" class="form-status"></p>
        </form>

    </div>
</section>