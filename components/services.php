<section class="services-section" id="servicios">
    <div class="services-container">
        <div class="services-header">
            <h2 class="services-title">Nuestros Servicios</h2>
            <p class="services-subtitle">Soluciones integrales de marketing digital para impulsar tu marca</p>
        </div>

        <div class="services-grid">
            <!-- Growth + Ads -->
            <div class="service-card" data-service="growth">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2L3 14h8l-1 8 10-12h-8l1-8z" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="service-title">Growth + Ads</h3>
                <p class="service-description">
                    Gestión de campañas en Meta, Google, TikTok y LinkedIn enfocadas en resultados medibles (leads,
                    ventas, tráfico), optimizando constantemente CPL, CAC, ROAS y tasa de conversión.
                </p>
                <div class="service-metrics">
                    <span class="metric-badge">Meta Ads</span>
                    <span class="metric-badge">Google Ads</span>
                    <span class="metric-badge">TikTok Ads</span>
                    <span class="metric-badge">LinkedIn Ads</span>
                </div>
            </div>

            <!-- Content Marketing & Redes Sociales -->
            <div class="service-card" data-service="content">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="service-title">Content + UGC</h3>
                <p class="service-description">
                    Diseño de la estrategia de contenido, calendario mensual y gestión de redes (IG, FB, TikTok,
                    LinkedIn, YouTube) para construir comunidad, conseguir interacción y apoyar los objetivos
                    comerciales.
                </p>
                <div class="service-metrics">
                    <span class="metric-badge">Instagram</span>
                    <span class="metric-badge">TikTok</span>
                    <span class="metric-badge">LinkedIn</span>
                    <span class="metric-badge">YouTube</span>
                </div>
            </div>

            <!-- Brand Building -->
            <div class="service-card" data-service="brand">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="service-title">Brand Building</h3>
                <p class="service-description">
                    Desarrollo y fortalecimiento de la identidad visual de la marca (logo, sistema gráfico y piezas) y
                    creación de materiales gráficos para campañas, presentaciones, brochures y comunicación diaria.
                </p>
                <div class="service-metrics">
                    <span class="metric-badge">Identidad Visual</span>
                    <span class="metric-badge">Diseño Gráfico</span>
                    <span class="metric-badge">Branding</span>
                </div>
            </div>

            <!-- BTL -->
            <div class="service-card" data-service="btl">
                <div class="service-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <h3 class="service-title">BTL</h3>
                <p class="service-description">
                    Conceptualización, producción y ejecución de experiencias de marca en el mundo físico (stands,
                    activaciones en punto de venta, eventos, ferias y pop-ups), incluyendo coordinación de logística y
                    staff.
                </p>
                <div class="service-metrics">
                    <span class="metric-badge">Eventos</span>
                    <span class="metric-badge">Activaciones</span>
                    <span class="metric-badge">Stands</span>
                    <span class="metric-badge">Pop-ups</span>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .services-section {
        padding: 100px 20px;
        background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 100%);
        position: relative;
        overflow: hidden;
    }

    .services-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 30%, rgba(138, 43, 226, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 70%, rgba(0, 191, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .services-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .services-header {
        text-align: center;
        margin-bottom: 80px;
        opacity: 0;
        animation: fadeInUp 1s ease forwards;
    }

    .services-title {
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .services-subtitle {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.7);
        max-width: 600px;
        margin: 0 auto;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 60px;
    }

    .service-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px 30px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        opacity: 0;
        transform: translateY(50px);
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(138, 43, 226, 0.1) 0%, rgba(0, 191, 255, 0.1) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
    }

    .service-card:hover {
        transform: translateY(-10px);
        border-color: rgba(138, 43, 226, 0.5);
        box-shadow: 0 20px 60px rgba(138, 43, 226, 0.3);
    }

    .service-card:hover::before {
        opacity: 1;
    }

    .service-card[data-service="growth"] {
        animation: slideInLeft 0.8s ease forwards 0.2s;
    }

    .service-card[data-service="content"] {
        animation: slideInLeft 0.8s ease forwards 0.4s;
    }

    .service-card[data-service="brand"] {
        animation: slideInLeft 0.8s ease forwards 0.6s;
    }

    .service-card[data-service="btl"] {
        animation: slideInLeft 0.8s ease forwards 0.8s;
    }

    .service-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 25px;
        position: relative;
        z-index: 1;
        transition: transform 0.4s ease;
    }

    .service-card:hover .service-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .service-icon svg {
        width: 35px;
        height: 35px;
        color: white;
    }

    .service-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .service-description {
        font-size: 1rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 25px;
        position: relative;
        z-index: 1;
    }

    .service-metrics {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        position: relative;
        z-index: 1;
    }

    .metric-badge {
        padding: 8px 16px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 20px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
    }

    .service-card:hover .metric-badge {
        background: rgba(138, 43, 226, 0.3);
        border-color: rgba(138, 43, 226, 0.5);
        transform: translateY(-2px);
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Scroll animations */
    .service-card.scroll-visible {
        animation: slideInScale 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }

    @keyframes slideInScale {
        from {
            opacity: 0;
            transform: translateY(50px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .services-section {
            padding: 60px 20px;
        }

        .services-title {
            font-size: 2.5rem;
        }

        .services-subtitle {
            font-size: 1rem;
        }

        .services-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .service-card {
            padding: 30px 25px;
        }
    }
</style>

<script>
    // Scroll animations
    document.addEventListener('DOMContentLoaded', function () {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('scroll-visible');
                }
            });
        }, observerOptions);

        // Observe all service cards
        const serviceCards = document.querySelectorAll('.service-card');
        serviceCards.forEach(card => {
            observer.observe(card);
        });

        // Add parallax effect on scroll
        window.addEventListener('scroll', function () {
            const scrolled = window.pageYOffset;
            const servicesSection = document.querySelector('.services-section');

            if (servicesSection) {
                const sectionTop = servicesSection.offsetTop;
                const sectionHeight = servicesSection.offsetHeight;

                if (scrolled > sectionTop - window.innerHeight && scrolled < sectionTop + sectionHeight) {
                    const cards = document.querySelectorAll('.service-card');
                    cards.forEach((card, index) => {
                        const speed = 0.05 * (index + 1);
                        const yPos = -(scrolled - sectionTop) * speed;
                        card.style.transform = `translateY(${yPos}px)`;
                    });
                }
            }
        });

        // Add hover effect with mouse tracking
        const cards = document.querySelectorAll('.service-card');
        cards.forEach(card => {
            card.addEventListener('mousemove', function (e) {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                const centerX = rect.width / 2;
                const centerY = rect.height / 2;

                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px)`;
            });

            card.addEventListener('mouseleave', function () {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
            });
        });
    });
</script>