(() => {
  const KEY = 'revvo.onboarding.shown.v1'; 

  function openWithTrigger() {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.setAttribute('data-modal-open', '#modal-onboarding');
    document.body.appendChild(btn);
    btn.click();
    btn.remove();
  }

  function forceOpen(modal) {
    if (!modal) return;
    modal.hidden = false;
    modal.setAttribute('aria-hidden', 'false');
    modal.style.display = '';
    document.body.style.overflow = 'hidden';
    const first = modal.querySelector('input, textarea, button, [tabindex]:not([tabindex="-1"])');
    if (first) { try { first.focus(); } catch (_) {} }
  }

  function paramIsTrue(name) {
    return new URLSearchParams(location.search).get(name) === '1';
  }

  window.addEventListener('load', () => {
    const modal = document.getElementById('modal-onboarding');
    if (!modal) return;

    const forceParam = paramIsTrue('onboarding');
    const alreadyShown = !!localStorage.getItem(KEY);
    if (alreadyShown && !forceParam) return;

    openWithTrigger();

    setTimeout(() => {
      const isClosed = modal.hasAttribute('hidden') || modal.style.display === 'none';
      if (isClosed) forceOpen(modal);

      if (!forceParam) {
        try { localStorage.setItem(KEY, '1'); } catch (_) {}
      }
    }, 0);
  });
})();
