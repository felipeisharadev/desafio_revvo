(() => {
  const body = document.body;
  let lastFocused = null;
  let trapHandler = null;

// === ABRIR MODAL ===
function openModal(sel) {
  const modal = document.querySelector(sel);
  if (!modal) return;
  lastFocused = document.activeElement;

  // abrir visualmente
  modal.hidden = false;
  modal.setAttribute('aria-hidden', 'false');
  modal.style.display = ''; // <-- limpa o display:none inline

  body.style.overflow = 'hidden';

  const firstField = modal.querySelector('input, textarea, button, [tabindex]:not([tabindex="-1"])');
  if (firstField) firstField.focus();

  trapFocus(modal);
}

// === FECHAR MODAL (botão X ou backdrop) ===
function closeModal(modal) {
  if (!modal) return;

  // fechar visualmente
  modal.hidden = true;
  modal.setAttribute('aria-hidden', 'true');
  modal.style.display = 'none'; // <-- recoloca o display:none inline

  body.style.overflow = '';
  releaseTrap();
  if (lastFocused) lastFocused.focus();
}


  // === ABRIR MODAL ===
  document.addEventListener('click', (e) => {
    const openBtn = e.target.closest('[data-modal-open]');
    if (openBtn) {
      e.preventDefault();
      openModal(openBtn.getAttribute('data-modal-open')); // id do modal (#modal-add-course)
    }
  });

  // === FECHAR MODAL (botão X ou backdrop) ===
  document.addEventListener('click', (e) => {
    const closeEl = e.target.closest('[data-modal-close]');
    if (closeEl) {
      const modal = closeEl.closest('.app-modal');
      closeModal(modal);
    }
  });

  // === FECHAR COM ESC ===
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const modal = document.querySelector('.app-modal:not([hidden])');
      if (modal) closeModal(modal);
    }
  });

  // foco preso dentro do modal
  function trapFocus(container) {
    trapHandler = function(e) {
      if (e.key !== 'Tab') return;
      const foci = container.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])');
      const els = Array.from(foci).filter(el => !el.disabled && el.offsetParent !== null);
      if (!els.length) return;
      const first = els[0];
      const last  = els[els.length - 1];

      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault(); last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault(); first.focus();
      }
    };
    document.addEventListener('keydown', trapHandler, true);
  }
  function releaseTrap() {
    if (trapHandler) document.removeEventListener('keydown', trapHandler, true);
    trapHandler = null;
  }
})();
