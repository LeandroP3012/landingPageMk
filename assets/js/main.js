const menuToggle = document.getElementById( 'menuToggle' );
const nav = document.querySelector( '.nav' );

// Toggle del menú móvil
if ( menuToggle && nav ) {
  menuToggle.addEventListener( 'click', () => {
    nav.classList.toggle( 'active' );
    menuToggle.classList.toggle( 'active' );
  } );

  // Cerrar menú al hacer clic en un enlace
  const navLinks = document.querySelectorAll( '.nav a' );
  navLinks.forEach( link => {
    link.addEventListener( 'click', () => {
      nav.classList.remove( 'active' );
      menuToggle.classList.remove( 'active' );
    } );
  } );

  // Cerrar menú al hacer clic en el overlay
  nav.addEventListener( 'click', ( e ) => {
    if ( e.target === nav ) {
      nav.classList.remove( 'active' );
      menuToggle.classList.remove( 'active' );
    }
  } );
}
window.addEventListener( 'load', () => {
  const heroContent = document.querySelector( '.hero-content' );
  heroContent.classList.add( 'show' );
} );
document.querySelectorAll( '.clients-grid img' ).forEach( ( img, index ) => {
  img.style.transitionDelay = `${index * 80}ms`;
} );
const teamMembers = document.querySelectorAll( '.team-member' );

teamMembers.forEach( ( member, index ) => {
  member.style.transitionDelay = `${index * 100}ms`;
} );

// Typing effect for impact section
const typingElements = document.querySelectorAll( '[data-typing]' );
const revealElements = document.querySelectorAll( '.reveal-on-scroll' );
const transitionOverlay = document.querySelector( '.section-transition' );
const observedSections = document.querySelectorAll( 'section[id]' );

const typeText = ( element ) => {
  if ( element.dataset.typed === 'true' ) return;

  const text = element.dataset.typing || element.textContent;
  const speed = parseInt( element.dataset.typingSpeed, 10 ) || 80;
  element.textContent = '';
  element.dataset.typed = 'true';

  let index = 0;

  const tick = () => {
    if ( index <= text.length ) {
      element.textContent = text.slice( 0, index );
      index += 1;
      setTimeout( tick, speed );
    }
  };

  tick();
};

const impactSection = document.querySelector( '.impact-section' );

if ( impactSection ) {
  const observer = new IntersectionObserver( ( entries ) => {
    entries.forEach( ( entry ) => {
      if ( entry.isIntersecting ) {
        typingElements.forEach( ( el ) => typeText( el ) );
        observer.disconnect();
      }
    } );
  }, { threshold: 0.6 } );

  observer.observe( impactSection );
}

// Animación reveal-on-scroll (necesaria para impact y otros elementos)
if ( revealElements.length ) {
  const revealObserver = new IntersectionObserver( ( entries, obs ) => {
    entries.forEach( ( entry ) => {
      if ( entry.isIntersecting ) {
        entry.target.classList.add( 'is-visible' );
        obs.unobserve( entry.target );
      }
    } );
  }, { threshold: 0.35 } );

  revealElements.forEach( ( element ) => revealObserver.observe( element ) );
}


// ANIMACIÓN DE TRANSICIÓN ENTRE SECCIONES DESACTIVADA
/* if ( transitionOverlay && observedSections.length ) {
  let transitionTimeout;

  const applyTransition = ( section ) => {
    const { transitionColor, transitionAccent } = section.dataset;

    if ( transitionColor ) {
      transitionOverlay.style.setProperty( '--transition-color', transitionColor );
    }

    if ( transitionAccent ) {
      transitionOverlay.style.setProperty( '--transition-accent', transitionAccent );
    }

    transitionOverlay.classList.add( 'is-active' );

    if ( transitionTimeout ) {
      clearTimeout( transitionTimeout );
    }

    transitionTimeout = setTimeout( () => {
      transitionOverlay.classList.remove( 'is-active' );
    }, 650 );
  };

  const sectionObserver = new IntersectionObserver( ( entries ) => {
    entries.forEach( ( entry ) => {
      if ( entry.isIntersecting ) {
        applyTransition( entry.target );
      }
    } );
  }, { threshold: 0.55 } );

  observedSections.forEach( ( section ) => sectionObserver.observe( section ) );
} */
/* ===============================
 CONTACT FORM BACKEND
================================ */
const contactForm = document.getElementById( 'contactForm' );
const formStatus = document.getElementById( 'formStatus' );

if ( contactForm ) {
  const endpoint = contactForm.dataset.endpoint;

  contactForm.addEventListener( 'submit', async ( e ) => {
    e.preventDefault();

    formStatus.textContent = 'Enviando...';

    const formData = new FormData( contactForm );

    try {
      const response = await fetch( endpoint, {
        method: 'POST',
        body: formData
      } );

      const result = await response.text();

      if ( result.trim() === 'OK' ) {
        formStatus.textContent = 'Mensaje enviado correctamente ✔';
        contactForm.reset();
      } else {
        throw new Error( result );
      }
    } catch ( err ) {
      formStatus.textContent = 'Error al enviar el mensaje';
      console.error( err );
    }
  } );
}
