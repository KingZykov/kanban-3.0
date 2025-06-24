document.addEventListener("DOMContentLoaded", () => {
  //  Запретить перетаскивание на вложенных элементах
  document.querySelectorAll('form, button, input, textarea, i, svg').forEach(el => {
    el.setAttribute('draggable', 'false');
  });

  //  DragStart с защитой
  document.querySelectorAll('.list-group-item').forEach(task => {
    task.addEventListener('dragstart', function (e) {
      // Блокируем drag при активной модалке
      if (document.querySelector('.modal.show')) {
        console.log('⛔ Drag отменён: активна модалка');
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        return;
      }

      // Блокируем drag, если начат с интерактивных элементов
      const disallowed = ['form', 'button', 'input', 'textarea', 'svg', 'i'];
      for (const selector of disallowed) {
        if (e.target.closest(selector)) {
          console.log(' Drag отменён: источник —', selector);
          e.preventDefault();
          e.stopPropagation();
          e.stopImmediatePropagation();
          return;
        }
      }

      // Разрешаем перетаскивание
      const taskId = this.getAttribute('data-task-id');
      e.dataTransfer.setData('text/plain', taskId);
      console.log(' Drag Start - Task ID:', taskId);
    });
  });

  //  DragOver
  document.querySelectorAll('.drop-zone').forEach(zone => {
    zone.addEventListener('dragover', e => {
      e.preventDefault();
    });

    zone.addEventListener('drop', function (e) {
      e.preventDefault();
      const taskId = e.dataTransfer.getData('text/plain');
      const taskEl = document.querySelector(`[data-task-id="${taskId}"]`);
      const newStatus = this.getAttribute('task-status');

      if (!taskEl || this.contains(taskEl)) return;

      this.appendChild(taskEl);

      // 1. Обновление в PHP (в БД)
      fetch('projects.php', {
        method: 'POST',
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: new URLSearchParams({
          taskId: taskId,
          newStatus: newStatus
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          console.log(' Статус задачи сохранён в БД');
        } else {
          console.error(' Не удалось сохранить статус в БД');
        }
      })
      .catch(err => {
        console.error(' Ошибка запроса к PHP:', err);
      });

      // 2. Отправка через WebSocket другим клиентам
      taskSocket.send(JSON.stringify({
        type: 'task-status-change',
        taskId: taskId,
        task_status: newStatus
      }));

      console.log(' WebSocket отправил статус другим клиентам');
    });



    
  });
});
