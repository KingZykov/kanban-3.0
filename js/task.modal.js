  function bindTaskModals() {
  document.querySelectorAll(".edit-task-form").forEach(form => {
    if (!form.dataset.bound) {
      form.dataset.bound = "true";
      form.addEventListener("submit", handleEditTask);
    }
  });

  document.querySelectorAll(".delete-task-form").forEach(form => {
    if (!form.dataset.bound) {
      form.dataset.bound = "true";
      form.addEventListener("submit", handleDeleteTask);
    }
  });
}

let taskSocket = new WebSocket('ws://localhost:8080');

taskSocket.onmessage = (e) => {

  try {
    const data = JSON.parse(e.data);
    console.log("📩 Получены данные через WebSocket:", data);
      console.log("🔥 onmessage отработал", data.id);

    if (data.type === 'edit-task') {
      const currentProjectId = new URLSearchParams(window.location.search).get("idProject");
      if (data.id_project.toString() !== currentProjectId) return;

      const taskId = data.id;
      const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
      console.log("🔍 Найдена задача в DOM:", taskElement);

      if (!taskElement) {
        console.warn(`⚠️ Элемент с data-task-id="${taskId}" не найден`);
        return;
      }

      const titleWrapper = taskElement.querySelector('.task-title');
      const desc = taskElement.querySelector('.task-description');
      const deadline = taskElement.querySelector('.task-deadline');
      const colorIndicator = taskElement.querySelector('.task-color') || taskElement.querySelector('.todo-indicator');


      console.log("➡️ Обновление элементов:");
      console.log("   📝 Заголовок:", titleWrapper);
      console.log("   🗒️ Описание:", desc);
      console.log("   ⏰ Дедлайн:", deadline);
      console.log("   🎨 Цвет:", colorIndicator);

      // 🔧 Заголовок: заменяем только текст, исключая иконку
      if (titleWrapper) {
        titleWrapper.innerHTML = `${data.task_name} <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2"></i></span>`;
      }

      // 🔧 Описание
      if (desc) desc.textContent = data.task_description;

      // 🔧 Дедлайн
      if (deadline) deadline.innerHTML = `<i>Deadline: ${data.deadline}</i>`;

      // 🔧 Цвет
      if (colorIndicator) colorIndicator.style.backgroundColor = data.task_color;


      // Обновление модального окна
      const nameInput = document.getElementById(`edit_task_name_${taskId}`);
      const descInput = document.getElementById(`edit_task_description_${taskId}`);
      const colorSelect = document.getElementById(`edit_task_color_${taskId}`);
      const deadlineInput = document.getElementById(`deadline_${taskId}`);
      const userSelect = document.getElementById(`edit_user_name_${taskId}`);

      console.log("➡️ Обновление формы:");
      console.log("   📥 Название:", nameInput);
      console.log("   📥 Описание:", descInput);
      console.log("   🎨 Цвет:", colorSelect);
      console.log("   ⏰ Дедлайн:", deadlineInput);
      console.log("   👤 Пользователь:", userSelect);

      if (nameInput) nameInput.value = data.task_name;
      if (descInput) descInput.value = data.task_description;
      if (colorSelect) colorSelect.value = data.task_color;
      if (deadlineInput) deadlineInput.value = data.deadline;
      if (userSelect) userSelect.value = data.user_name;
    }

    if (data.type === 'delete-task') {
      const taskElement = document.querySelector(`[data-task-id="${data.id}"]`);
      if (taskElement) taskElement.remove();
    }

    if (data.type === "create-task") {
      if (document.querySelector(`[data-task-id="${data.id}"]`)) return;

      const col = document.querySelector(`.drop-zone[task-status="${data.task_status}"]`);
      if (!col) return;

      const li = document.createElement("li");
      li.className = "accordion list-group-item pe-auto";
      li.id = `task-${data.id}`;
      li.setAttribute("data-task-id", data.id);
      li.setAttribute("data-project-id", data.id_project);
      li.setAttribute("draggable", "true");


      li.innerHTML = `
        <div class="todo-indicator task-color" style="background-color:${data.task_color};"></div>
        <div class="widget-content p-0">
          <div class="widget-content-wrapper">
            <a class="col-8 nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-task-${data.id}" aria-expanded="true">
              <div class="widget-content-left p-2 pl-3">
                <div class="widget-heading d-flex task-title">
                  ${data.task_name}
                  <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2"></i></span>
                </div>
                <div id="collapse-task-${data.id}" class="collapse" data-parent="#task-${data.id}">
                  <div class="widget-subheading text-muted"><i>id: ${data.id}</i></div>
                  ${data.deadline !== '1970-01-01' ? `<div class="widget-subheading text-muted task-deadline"><i>Deadline: ${data.deadline}</i></div>` : ""}
                  <p class="font-small text-dark pt-1 task-description">${data.task_description}</p>
                </div>
              </div>
            </a>
            <div class="widget-content-right ml-auto">
              <button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#edit-task-${data.id}">
                <i class="fas fa-pencil-alt"></i>
              </button>
              <button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#delete-task-${data.id}">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-center">
          <!-- Стрелки  updateTaskArrows -->
        </div>

      `;

        // Подключение вынесенных модалок
      const editModalHTML = createEditTaskModal(data);
      const deleteModalHTML = createDeleteTaskModal(data);
      // Добавляем в DOM
      document.body.insertAdjacentHTML("beforeend", editModalHTML);
      document.body.insertAdjacentHTML("beforeend", deleteModalHTML);
/*       col.prepend(li);

        // Подключаем логику
      updateTaskArrows(li, data.id, parseInt(data.task_status));
      bindTaskModals();
      bindModalButtonBlurHandlers(); */


/*       const editModal = document.createElement("div");
      editModal.className = "modal fade";
      editModal.id = `edit-task-${data.id}`;
      editModal.setAttribute("tabindex", "-1");
      editModal.setAttribute("role", "dialog");


      document.body.appendChild(editModal); */
      col.prepend(li);
      updateTaskArrows(li, data.id, parseInt(data.task_status));  // ✅ стрелки
      bindTaskModals();
      bindModalButtonBlurHandlers();
    }


    if (data.type === 'task-status-change') {
      const taskId = data.taskId;
      const newStatus = data.task_status;

      const taskEl = document.querySelector(`[data-task-id="${taskId}"]`);
      const targetColumn = document.querySelector(`.drop-zone[task-status="${newStatus}"]`);

      if (taskEl && targetColumn && !targetColumn.contains(taskEl)) {
        targetColumn.appendChild(taskEl);
        updateTaskArrows(taskEl, data.taskId, parseInt(data.task_status));
        console.log(`🔄 Переместили задачу ${taskId} в колонку ${newStatus}`);
      }
    }


    if (data.type === 'edit-project') {
      const projectEl = document.querySelector(`[data-project-id="${data.id}"]`);
      if (!projectEl) return;

      const titleEl = projectEl.querySelector('.project-title');
      const descEl = projectEl.querySelector('.project-description');
      const startEl = projectEl.querySelector('.project-start i');
      const endEl = projectEl.querySelector('.project-end i');

      if (titleEl) titleEl.textContent = data.project_name;
      if (descEl) descEl.textContent = data.project_description;
      if (startEl) startEl.textContent = `Start: ${data.start_date}`;
      if (endEl) endEl.textContent = `End: ${data.end_date}`;

      console.log(`🟢 Проект #${data.id} обновлён по WebSocket`);
    }

    if (data.type === 'delete-project') {
      const el = document.querySelector(`[data-project-id="${data.id}"]`);
      if (el) {
        el.remove();
        console.log(`🗑 Проект ${data.id} удалён через WebSocket`);
      }
    }

  } catch (err) {
    console.error("Ошибка WebSocket:", err);
  }
};

document.addEventListener("DOMContentLoaded", () => {
  // ----  Редактирование задачи        ------
  document.querySelectorAll(".edit-task-form").forEach(form => {
    form.addEventListener("submit", async e => {
      e.preventDefault();
      const formData = new FormData(form);

      const res = await fetch("projects.php", {
        method: "POST",
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      });

      const data = await res.json();
      console.log("Ответ от сервера:", data);

      if (data.status === 'success') {
        Swal.fire({
          title: 'Задача обновлена!',
          timer: 1500,
          showConfirmButton: false,
          icon: 'success'
        });
        taskSocket.send(JSON.stringify({
          type: 'edit-task',
          id: form.querySelector('[name="edit_id_task"]').value,
          task_name: form.querySelector('[name="edit_task_name"]').value,
          task_description: form.querySelector('[name="edit_task_description"]').value,
          task_color: form.querySelector('[name="edit_task_color"]').value,
          deadline: form.querySelector('[name="deadline"]').value,
          user_name: form.querySelector('[name="edit_user_name"]').value,
          id_project: form.querySelector('[name="id_task_project"]').value
        }));
        $(form.closest('.modal')).modal('hide');
        document.activeElement.blur();

        if (data.id_project) {
          const currentUrl = new URL(window.location.href);
          const projectParam = currentUrl.searchParams.get("idProject");

          if (projectParam !== data.id_project.toString()) {
            window.location.href = `projects.php?idProject=${data.id_project}`;
          }
        }
      } else {
        Swal.fire({
          title: 'Ошибка!',
          text: data.message || 'Не удалось обновить задачу',
          icon: 'error'
        });
      }
    });
  });
 
  // ----  Создание задачи        ------
  document.querySelectorAll(".create-task-form").forEach(form => {
  form.addEventListener("submit", async e => {
    e.preventDefault();

    const formData = new FormData(form);

    const res = await fetch("projects.php", {
      method: "POST",
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });

    const data = await res.json();

    if (data.status === 'success') {
      Swal.fire({
        title: 'Задача создана!',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      });

      $(form.closest('.modal')).modal('hide');
      document.activeElement.blur();
      form.reset();

    } else {
      Swal.fire("Ошибка", data.message || "Не удалось создать задачу", "error");
    }
  });
});

});


//------Перемещение по стрелкам------
function updateTaskArrows(taskEl, taskId, task_status) {
  const arrowsContainer = taskEl.querySelector('.d-flex.justify-content-center');
  if (!arrowsContainer) return;

  const leftDisabled = task_status === 1 ? "disabled" : "";
  const rightDisabled = task_status === 3 ? "disabled" : "";

  arrowsContainer.innerHTML = `
    <form name="id_task_left_${taskId}" method="POST">
      <input type="hidden" name="id_task_left" value="${taskId}">
      <input type="hidden" name="id_project_left" value="${taskEl.dataset.projectId || ''}">
      <input type="hidden" name="task_status" value="${task_status}">
      <button type="submit" class="border-0 btn-transition btn btn-outline-primary" ${leftDisabled}>
        <i class="fa fa-arrow-left"></i>
      </button>
    </form>
    <form name="id_task_right_${taskId}" method="POST">
      <input type="hidden" name="id_task_right" value="${taskId}">
      <input type="hidden" name="id_project_right" value="${taskEl.dataset.projectId || ''}">
      <input type="hidden" name="task_status" value="${task_status}">
      <button type="submit" class="border-0 btn-transition btn btn-outline-primary" ${rightDisabled}>
        <i class="fa fa-arrow-right"></i>
      </button>
    </form>
  `;

  arrowsContainer.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", e => {
          e.preventDefault();
          const formData = new FormData(form);
          fetch("projects.php", {
            method: "POST",
            headers: { "X-Requested-With": "XMLHttpRequest" },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.status !== "success") {
              Swal.fire("Ошибка!", "Не удалось изменить статус задачи", "error");
            }
          })
          .catch(() => {
            Swal.fire("Ошибка!", "Ошибка запроса", "error");
          });
        });
      });
    }


function deleteTask(id, projectId) {
  if (!confirm("Удалить задачу?")) return;

  const formData = new FormData();
  formData.append("id_task", id);
  formData.append("id_project", projectId);

  fetch("projects.php", {
    method: "POST",
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      Swal.fire({
        title: 'Удалено!',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      });

      const taskEl = document.querySelector(`[data-task-id="${id}"]`);
      if (taskEl) taskEl.remove();
    } else {
      Swal.fire("Ошибка", data.message || "Не удалось удалить", "error");
    }
  })
  .catch(err => {
    console.error("Ошибка удаления задачи:", err);
    Swal.fire("Ошибка", "Произошла ошибка при удалении задачи", "error");
  });
}




 // Удаление и редактирование
  async function handleEditTask(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);

    const res = await fetch("projects.php", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      },
      body: formData
    });

    const data = await res.json();

    if (data.status === "success") {
      Swal.fire({
        title: "Задача обновлена!",
        icon: "success",
        timer: 1500,
        showConfirmButton: false
      });

      taskSocket.send(JSON.stringify({
        type: "edit-task",
        id: form.querySelector('[name="edit_id_task"]').value,
        task_name: form.querySelector('[name="edit_task_name"]').value,
        task_description: form.querySelector('[name="edit_task_description"]').value,
        task_color: form.querySelector('[name="edit_task_color"]').value,
        deadline: form.querySelector('[name="deadline"]').value,
        user_name: form.querySelector('[name="edit_user_name"]').value,
        id_project: form.querySelector('[name="id_task_project"]').value
      }));

      $(form.closest('.modal')).modal('hide');
      document.activeElement.blur();
    } else {
      Swal.fire("Ошибка!", "Не удалось обновить задачу.", "error");
    }
  }

  async function handleDeleteTask(e) {
    e.preventDefault();
    const form = e.target;
    const taskId = form.querySelector('[name="id_task"]').value;
    const projectId = form.querySelector('[name="id_project"]').value;
    const modal = form.closest(".modal");

    const res = await fetch("projects.php", {
      method: "POST",
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      },
      body: new FormData(form)
    });

    const data = await res.json();

    if (data.status === "success") {
      $(modal).modal("hide");

      $(modal).on("hidden.bs.modal", () => {
        const el = document.querySelector(`[data-task-id="${taskId}"]`);
        if (el) el.remove();

        taskSocket.send(JSON.stringify({
          type: "delete-task",
          id: taskId
        }));

        Swal.fire({
          title: "Удалено!",
          icon: "success",
          timer: 1500,
          showConfirmButton: false
        });

        document.activeElement.blur();
      });
    } else {
      Swal.fire("Ошибка!", "Не удалось удалить задачу.", "error");
    }
  }

  // Создание модалки редактирования задачи
  function createEditTaskModal(data) {
    return `
      <div class="modal fade" id="edit-task-${data.id}" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3 class="lead text-edit">Редактирование задачи</h3>
              <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
            <form name="task" class="edit-task-form" method="POST" role="form" data-task-id="${data.id}">
              <div class="modal-body">
                <div class="form-group">
                  <label class="text-dark">Название<span class="text-danger pl-1">*</span></label>
                  <input class="form-control" type="text" name="edit_task_name" id="edit_task_name_${data.id}" value="${data.task_name}" required>
                </div>
                <div class="form-group">
                  <label class="text-dark">Описание</label>
                  <textarea class="form-control" name="edit_task_description" id="edit_task_description_${data.id}">${data.task_description}</textarea>
                </div>
                <div class="form-group">
                  <label class="text-dark">Приоритет</label>
                  <select name="edit_task_color" id="edit_task_color_${data.id}" class="form-control" style="color:${data.task_color}">
                    <option style="color:${data.task_color}" value="${data.task_color}">&#9724; ${getPriorityLabel(data.task_color)}</option>
                    <option style="color:#5cb85c" value="#5cb85c">&#9724; Низкий</option>
                    <option style="color:#f0ad4e" value="#f0ad4e">&#9724; Средний</option>
                    <option style="color:#d9534f" value="#d9534f">&#9724; Высокий</option>
                  </select>
                </div>
                <div class="form-group d-flex justify-content-between mt-2">
                  <div class="col-12 m-0 p-1">
                    <label class="text-dark">Срок</label>
                    <input type="date" class="form-control" name="deadline" id="deadline_${data.id}" value="${data.deadline !== '1970-01-01' ? data.deadline : ''}" min="${new Date().toISOString().split('T')[0]}">
                  </div>
                </div>
                <div class="form-group">
                  <label class="text-dark">Назначить пользователя:</label>
                  <input type="text" name="edit_user_name" id="edit_user_name_${data.id}" class="form-control" value="${data.user_name}">
                </div>
                <input hidden name="id_user" value="${document.body.dataset.userId}">
                <input hidden name="id_task_project" value="${data.id_project}">
                <input hidden name="edit_id_task" value="${data.id}">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Обновить</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }

  // Создание модалки удаления задачи
  function createDeleteTaskModal(data) {
    return `
      <div class="modal fade" id="delete-task-${data.id}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form class="delete-task-form" method="POST">
              <div class="text-white modal-header">
                <h3 class="lead text-edit">Вы уверены?</h3>
                <button type="button" class="text-white close" data-dismiss="modal">×</button>
              </div>
              <div class="modal-body">
                <p class="text-dark">Вы хотите безвозвратно удалить <i class="text-primary">${data.task_name}</i>?</p>
                <input type="hidden" name="id_task" value="${data.id}">
                <input type="hidden" name="id_project" value="${data.id_project}">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Удалить</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    `;
  }

  // Вспомогательная функция для перевода цвета в текст
  function getPriorityLabel(color) {
    switch (color) {
      case '#5cb85c': return 'Низкий';
      case '#f0ad4e': return 'Средний';
      case '#d9534f': return 'Высокий';
      default: return '';
    }
  }


  // Снимай фокус после закрытия любой модалки
  $('.modal').on('hidden.bs.modal', function () {
    if (document.activeElement) {
      document.activeElement.blur();
    }
  });