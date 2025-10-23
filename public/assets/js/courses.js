(() => {
  let currentCourseId = null;
  const root = document.querySelector('.container-wide');
  const placeholderImg =
    root?.dataset.placeholderImg || '/assets/imgs/course-placeholder.png';

  // === Função utilitária para escapar HTML ===
  function escapeHtml(str = '') {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  // === Renderiza o conteúdo do modal de detalhes ===
  function renderCourseDetails(course) {
    const img = course.imagem || placeholderImg;
    const hasLink = !!(course.link && /^https?:\/\//i.test(course.link));

    const html = `
      <section class="course-details">
        <div class="course-details__media">
          <img src="${escapeHtml(img)}"
               alt="Imagem do curso ${escapeHtml(course.nome || 'Curso')}"
               onerror="this.onerror=null; this.src='${placeholderImg}';">
        </div>

        <div class="course-details__body">
          <h4>${escapeHtml(course.nome || 'Curso')}</h4>
          <p class="muted">${escapeHtml(course.descricao || 'Sem descrição disponível.')}</p>

          <dl class="meta">
            <dt>Carga horária</dt>
            <dd>${course.carga_horaria ? `${escapeHtml(course.carga_horaria)}h` : '—'}</dd>

            <dt>Slideshow</dt>
            <dd>
              ${
                hasLink
                  ? `<a href="${escapeHtml(course.link)}" target="_blank" rel="noopener">abrir</a>`
                  : '<span class="muted">—</span>'
              }
            </dd>
          </dl>
        </div>
      </section>
    `;

    const target = document.querySelector('#modal-details .js-details-content');
    if (target) target.innerHTML = html;
  }

  // === ABRIR MODAL DE DETALHES ===
  document.addEventListener(
    'click',
    (e) => {
      const btn = e.target.closest('[data-modal-open="#modal-details"][data-course]');
      if (!btn) return;

      let course;
      try {
        course = JSON.parse(btn.getAttribute('data-course'));
      } catch (err) {
        console.warn('Erro ao parsear curso:', err);
        return;
      }

      currentCourseId = course.id;

      // Guarda o JSON no modal
      const modal = document.getElementById('modal-details');
      if (modal) modal.dataset.course = JSON.stringify(course);

      // Preenche conteúdo ANTES de abrir
      renderCourseDetails(course);

      // Abre o modal
      openModal('#modal-details');
    },
    true
  );

  // === EXCLUIR CURSO ===
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
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });

      const text = await response.text();
      let result;
      try {
        result = JSON.parse(text);
      } catch {
        throw new Error('Resposta não-JSON: ' + text);
      }

      if (result.success) {
        document
          .querySelector(`.course-card[data-course-id="${currentCourseId}"]`)
          ?.remove();

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

  // === ABRIR EDIÇÃO A PARTIR DO DETALHES ===
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('#edit-course-btn');
    if (!btn) return;

    if (!currentCourseId) {
      alert('Curso inválido.');
      return;
    }

    try {
      const res = await fetch(`/cursos/edit/${currentCourseId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
      });

      if (!res.ok) {
        const txt = await res.text();
        console.error('Falha ao carregar edição:', res.status, txt);
        alert('Não foi possível abrir o editor.');
        return;
      }

      const html = await res.text();

      // Remove modal antigo (se existir)
      document.getElementById('modal-edit-course')?.remove();

      // Injeta o HTML recebido
      const tmp = document.createElement('div');
      tmp.innerHTML = html;
      const modalEl = tmp.querySelector('#modal-edit-course');
      if (!modalEl) {
        console.error('Modal de edição não encontrado no HTML retornado.');
        alert('Erro ao abrir o editor.');
        return;
      }
      document.body.appendChild(modalEl);

      // Fecha o modal de detalhes
      const detailsModal = btn.closest('.app-modal');
      if (detailsModal) {
        detailsModal.hidden = true;
        detailsModal.setAttribute('aria-hidden', 'true');
        detailsModal.style.display = 'none';
        document.body.style.overflow = '';
      }

      // Abre o modal de edição, reaproveitando modal.js
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

  // === INTERCEPTA SUBMITS (CREATE/UPDATE AJAX) ===
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
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
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

      // Descobre qual modal estamos
      const modalEl = form.closest('.app-modal');
      if (!modalEl) return;

      // Substitui o modal inteiro pelo HTML novo
      const wrapper = document.createElement('div');
      wrapper.innerHTML = html;

      const newModal = wrapper.querySelector('.app-modal');
      if (newModal && newModal.id === modalEl.id) {
        modalEl.replaceWith(newModal);

        // Reabre o modal se vier fechado
        newModal.hidden = false;
        newModal.setAttribute('aria-hidden', 'false');
        newModal.style.display = '';
        document.body.style.overflow = 'hidden';
        return;
      }

      console.warn('Resposta não contém modal compatível. HTML:', html);
    } catch (err) {
      console.error('Falha na submissão do formulário:', err);
      alert('Erro inesperado. Tente novamente.');
    }
  });
})();