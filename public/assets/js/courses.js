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

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&','&amp;')
      .replaceAll('<','&lt;')
      .replaceAll('>','&gt;')
      .replaceAll('"','&quot;')
      .replaceAll("'",'&#039;');
  }
})();
