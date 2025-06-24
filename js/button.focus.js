function bindModalButtonBlurHandlers() {
  document.querySelectorAll('button[data-toggle="modal"]').forEach(btn => {
    if (btn.dataset.bound) return; // чтобы не привязать дважды
    btn.dataset.bound = 'true';

    btn.addEventListener('click', () => {
      setTimeout(() => btn.blur(), 150);
    });
  });

  let lastFocusedBtn = null;

  // Отслеживание открытия модалки
  document.querySelectorAll('button[data-toggle="modal"]').forEach(btn => {
    if (btn.dataset.focusBound) return;
    btn.dataset.focusBound = 'true';

    btn.addEventListener('click', () => {
      lastFocusedBtn = btn;
    });
  });

  // Убираем фокус после закрытия любой модалки
  $('.modal').on('hidden.bs.modal', function () {
    setTimeout(() => {
      if (lastFocusedBtn) {
        lastFocusedBtn.blur();
        lastFocusedBtn = null;
      }
    }, 10);
  });
}
