// Modal de confirmación personalizada
class ConfirmModal {
  constructor() {
    this.createModal();
    this.bindEvents();
  }

  createModal() {
    const modalHTML = `
            <div id="confirmModal" class="confirm-modal">
                <div class="confirm-modal-overlay"></div>
                <div class="confirm-modal-content">
                    <div class="confirm-modal-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="confirm-modal-title">¿Estás seguro?</h3>
                    <p class="confirm-modal-message">Esta acción no se puede deshacer.</p>
                    <div class="confirm-modal-actions">
                        <button class="confirm-modal-btn confirm-modal-cancel">
                            <i class="fas fa-times"></i>
                            Cancelar
                        </button>
                        <button class="confirm-modal-btn confirm-modal-confirm">
                            <i class="fas fa-trash"></i>
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;

    document.body.insertAdjacentHTML( 'beforeend', modalHTML );
    this.modal = document.getElementById( 'confirmModal' );
    this.overlay = this.modal.querySelector( '.confirm-modal-overlay' );
    this.cancelBtn = this.modal.querySelector( '.confirm-modal-cancel' );
    this.confirmBtn = this.modal.querySelector( '.confirm-modal-confirm' );
    this.titleEl = this.modal.querySelector( '.confirm-modal-title' );
    this.messageEl = this.modal.querySelector( '.confirm-modal-message' );
  }

  bindEvents() {
    this.overlay.addEventListener( 'click', () => this.hide() );
    this.cancelBtn.addEventListener( 'click', () => this.hide() );
  }

  show( options = {} ) {
    return new Promise( ( resolve ) => {
      this.titleEl.textContent = options.title || '¿Estás seguro?';
      this.messageEl.textContent = options.message || 'Esta acción no se puede deshacer.';

      this.modal.classList.add( 'show' );
      document.body.style.overflow = 'hidden';

      const confirmHandler = () => {
        this.hide();
        this.confirmBtn.removeEventListener( 'click', confirmHandler );
        resolve( true );
      };

      this.confirmBtn.addEventListener( 'click', confirmHandler );

      // Si se cierra sin confirmar
      const closeHandler = () => {
        this.confirmBtn.removeEventListener( 'click', confirmHandler );
        this.modal.removeEventListener( 'transitionend', closeHandler );
        resolve( false );
      };

      this.overlay.addEventListener( 'click', closeHandler, { once: true } );
      this.cancelBtn.addEventListener( 'click', closeHandler, { once: true } );
    } );
  }

  hide() {
    this.modal.classList.remove( 'show' );
    document.body.style.overflow = '';
  }
}

// Inicializar modal cuando el DOM esté listo
let confirmModal;
document.addEventListener( 'DOMContentLoaded', () => {
  confirmModal = new ConfirmModal();

  // Agregar listeners a todos los enlaces de eliminación
  setupDeleteLinks();
} );

function setupDeleteLinks() {
  document.querySelectorAll( '[data-confirm-delete]' ).forEach( link => {
    link.addEventListener( 'click', async ( e ) => {
      e.preventDefault();

      const title = link.getAttribute( 'data-confirm-title' ) || '¿Estás seguro?';
      const message = link.getAttribute( 'data-confirm-message' ) || 'Esta acción no se puede deshacer.';

      if ( !confirmModal ) {
        confirmModal = new ConfirmModal();
      }

      const confirmed = await confirmModal.show( { title, message } );

      if ( confirmed ) {
        // Redirigir al enlace de eliminación
        window.location.href = link.href;
      }
    } );
  } );
}
