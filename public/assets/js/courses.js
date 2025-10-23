(() => {
  let currentCourseId = null;
  const root = document.querySelector('.container-wide');
  const placeholderImg = root?.dataset.placeholderImg || '/assets/imgs/course-placeholder.png';

  document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-modal-open="#modal-details"][data-course]');
    if (!btn) return;

    let data;
    try { data = JSON.parse(btn.getAttribute('data-course')); }
    catch (err) { console.warn('Erro ao parsear curso:', err); return; }

    currentCourseId = data.id;

    const modal = document.getElementById('modal-details');
    const content = modal.querySelector('.app-modal__content');

    const html = `
      <article class="course-details">
        <div class="course-details__media">
          <img src="${escapeHtml(data.imagem || placeholderImg)}"
               alt="Imagem do curso ${escapeHtml(data.nome || '')}"
               onerror="this.onerror=null; this.src='${placeholderImg}';">
        </div>
        <div class="course-details__body">
          <h4>${escapeHtml(data.nome || 'Curso')}</h4>
          <p class="muted">${escapeHtml(data.descricao || 'Sem descrição disponível.')}</p>
          <dl class="meta">
            <div><dt>Carga horária</dt><dd>${Number(data.carga_horaria || 0)}h</dd></div>
          </dl>
        </div>
      </article>
    `;
    content.innerHTML = html;
  }, true);

  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('#delete-course-btn');
    if (!btn) return;

    if (!currentCourseId) {
      alert('Curso inválido.');
      return;
    }

    if (!confirm('Tem certeza que deseja excluir este curso?')) return;

    try {
      const response = await fetch(`/cursos/delete/${currentCourseId}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      const text = await response.text();
      let result;
      try { result = JSON.parse(text); }
      catch { throw new Error('Resposta não-JSON: ' + text); }

      if (result.success) {
        document.querySelector(`.course-card[data-course-id="${currentCourseId}"]`)?.remove();

        const modal = btn.closest('.app-modal');
        if (modal) {
          modal.hidden = true;
          document.body.style.overflow = '';
        }

        alert('Curso excluído com sucesso.');
        currentCourseId = null;
      } else {
        alert('Erro: ' + (result.error || 'Falha ao excluir.'));
      }
    } catch (err) {
      alert('Erro de conexão: ' + err.message);
    }
  });

    // === ABRIR MODAL DE EDIÇÃO A PARTIR DO MODAL DE DETALHES ===
  // === ABRIR MODAL DE EDIÇÃO A PARTIR DO MODAL DE DETALHES ===
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('#edit-course-btn');
    if (!btn) return;

    if (!currentCourseId) {
      alert('Curso inválido.');
      return;
    }

    try {
      const res = await fetch(`/cursos/edit/${currentCourseId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      if (!res.ok) {
        const txt = await res.text();
        console.error('Falha ao carregar edição:', res.status, txt);
        alert('Não foi possível abrir o editor.');
        return;
      }

      const html = await res.text();

      // remove modal antigo (se existir)
      document.getElementById('modal-edit-course')?.remove();

      // injeta o HTML recebido
      const tmp = document.createElement('div');
      tmp.innerHTML = html;
      const modalEl = tmp.querySelector('#modal-edit-course');
      if (!modalEl) {
        console.error('Modal de edição não encontrado no HTML retornado.');
        alert('Erro ao abrir o editor.');
        return;
      }
      document.body.appendChild(modalEl);

      // fecha o modal de detalhes
      const detailsModal = btn.closest('.app-modal');
      if (detailsModal) {
        detailsModal.hidden = true;
        detailsModal.setAttribute('aria-hidden','true');
        detailsModal.style.display = 'none';
        document.body.style.overflow = '';
      }

      // abre o modal de edição, reaproveitando seu modal.js (data-modal-open)
      const fakeOpen = document.createElement('a');
      fakeOpen.href = '#';
      fakeOpen.setAttribute('data-modal-open', '#modal-edit-course');
      document.body.appendChild(fakeOpen);
      fakeOpen.click();
      fakeOpen.remove();

    } catch (err) {
      console.error('Erro ao abrir modal de edição:', err);
      alert('Erro de conexão ao abrir o editor.');
    }
  });

    // Intercepta submits dos modais para AJAX
  document.addEventListener('submit', async (e) => {
    const form = e.target.closest('.modal__form');
    if (!form) return;

    e.preventDefault();

    const action = form.getAttribute('action') || window.location.pathname;
    const method = (form.getAttribute('method') || 'post').toUpperCase();

    try {
      const res = await fetch(action, {
        method,
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });

      // Sucesso em JSON (store/update)
      const contentType = res.headers.get('content-type') || '';
      if (res.ok && contentType.includes('application/json')) {
        const data = await res.json();
        if (data.success && data.redirect) {
          window.location.assign(data.redirect);
          return;
        }
      }

      // 422 com HTML do modal (erros de validação)
      const html = await res.text();

      // Descobrir qual modal estamos
      const modalEl = form.closest('.app-modal');
      if (!modalEl) return;

      // Substituir o modal inteiro pelo HTML novo
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html;

      const newModal = wrapper.querySelector('.app-modal');
      if (newModal && newModal.id === modalEl.id) {
        modalEl.replaceWith(newModal);

        // Reabrir (caso o HTML venha fechado)
        newModal.hidden = false;
        newModal.setAttribute('aria-hidden','false');
        newModal.style.display = '';
        document.body.style.overflow = 'hidden';
        return;
      }

      // Se não veio um modal válido, logar para debug
      console.warn('Resposta não contém modal compatível. HTML:', html);

    } catch (err) {
      console.error('Falha na submissão do formulário:', err);
      alert('Erro inesperado. Tente novamente.');
    }
  });



  function escapeHtml(str) {
    return String(str)
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'",'&#039;');
  }
})();
