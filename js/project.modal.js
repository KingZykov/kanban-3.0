document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ project.modal.js загружен");
  bindModalButtonBlurHandlers();
/*   //----------------Создание Проекта----------------------------------
  document.querySelectorAll('.create-task-form').forEach(form => {
        form.addEventListener("submit", async e => {
        e.preventDefault();

        const formData = new FormData(form);

        const res = await fetch("projects.php", {
            method: "POST",
            headers: {
            "X-Requested-With": "XMLHttpRequest"
            },
            body: formData
        });

        const data = await res.json();

        if (data.status === 'success') {
            Swal.fire({
            title: "Задача создана!",
            icon: "success",
            timer: 1500,
            showConfirmButton: false
            });

            taskSocket.send(JSON.stringify({
            type: "create-task",
            id: data.id_task, // если сервер возвращает
            task_name: form.querySelector('[name="task_name"]').value,
            task_description: form.querySelector('[name="task_description"]').value,
            task_color: form.querySelector('[name="task_color"]').value,
            deadline: form.querySelector('[name="deadline"]').value,
            user_name: form.querySelector('[name="user_name"]').value,
            id_project: form.querySelector('[name="id_task_project"]').value,
            task_status: form.querySelector('[name="task_status"]').value
            }));

            $(form.closest('.modal')).modal('hide');
            document.activeElement.blur();
            form.reset();
        } else {
            Swal.fire({
            title: "Ошибка!",
            text: data.message || "Не удалось создать задачу",
            icon: "error"
            });
        }
        });
  }); */

  //------------------Редактирование проекта-------------------------------
  document.querySelectorAll(".edit-project-form").forEach(form => {
    form.addEventListener("submit", async e => {
      e.preventDefault();

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
          title: "Проект обновлён!",
          icon: "success",
          timer: 1500,
          showConfirmButton: false
        });

        // WebSocket-уведомление другим клиентам
        taskSocket.send(JSON.stringify({
          type: "edit-project",
          id: form.querySelector('[name="edit_id_project"]').value,
          project_name: form.querySelector('[name="edit_project_name"]').value,
          project_description: form.querySelector('[name="edit_project_description"]').value,
          start_date: form.querySelector('[name="edit_start_date"]').value,
          end_date: form.querySelector('[name="edit_end_date"]').value
        }));

        // Закрыть модалку
        $(form.closest('.modal')).modal('hide');
        document.activeElement.blur();

        // Перенаправление, если idProject изменился
        if (data.id_project) {
          const currentUrl = new URL(window.location.href);
          const currentParam = currentUrl.searchParams.get("idProject");

          if (currentParam !== data.id_project.toString()) {
            window.location.href = `projects.php?idProject=${data.id_project}`;
          }
        }

      } else {
        Swal.fire({
          title: "Ошибка!",
          text: "Не удалось обновить проект",
          icon: "error"
        });
      }
    });
  });
  //--Удаление проекта
  document.querySelectorAll(".delete-project-form").forEach(form => {
    form.addEventListener("submit", async e => {
      e.preventDefault();

      const formData = new FormData(form);
      const projectId = formData.get("delete_project_id");
      const modal = form.closest(".modal");

      // Отправка в PHP
      const res = await fetch("projects.php", {
        method: "POST",
        headers: {
          "X-Requested-With": "XMLHttpRequest"
        },
        body: formData
      });

      const data = await res.json();

      if (data.status === "success") {
        // Закрыть модалку
        $(modal).modal("hide");

        // После полного закрытия — удалить DOM и отправить WebSocket
        $(modal).on("hidden.bs.modal", () => {
          // Удалить элемент проекта из списка
          const el = document.querySelector(`[data-project-id="${projectId}"]`);
          if (el) el.remove();

          // Отправить WebSocket другим клиентам
          taskSocket.send(JSON.stringify({
            type: "delete-project",
            id: projectId
          }));

          // Уведомление
          Swal.fire({
            title: "Удалено!",
            icon: "success",
            timer: 1000,
            showConfirmButton: false
          });

          document.activeElement.blur();
        });

      } else {
        Swal.fire("Ошибка!", "Не удалось удалить проект.", "error");
      }
    });
  });


    document.querySelector('.new-project-form')?.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('projects.php', {
            method: 'POST',
            body: formData,
            headers: {
            'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(res => res.json())
            .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                title: 'Проект создан!',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
                });

                taskSocket.send(JSON.stringify({
                type: 'create-project',
                id_project: data.id_project,
                project_name: formData.get('project_name'),
                project_description: formData.get('project_description'),
                start_date: formData.get('start_date'),
                end_date: formData.get('end_date'),
                id_user: formData.get('id_user')
                }));

                this.reset();
                $(this.closest('.modal')).modal('hide');
                document.activeElement.blur();
            } else {
                Swal.fire({
                title: 'Ошибка!',
                text: 'Не удалось создать проект',
                icon: 'error'
                });
            }
            })
            .catch(err => {
            console.error('Ошибка запроса:', err);
            Swal.fire({
                title: 'Ошибка!',
                text: 'Ошибка соединения с сервером',
                icon: 'error'
            });
            });
    });

    taskSocket.addEventListener("message", function (event) {
        const data = JSON.parse(event.data);

        if (data.type === "create-project") {
            if (document.querySelector(`[data-project-id="${data.id_project}"]`)) return;

            const i = Date.now(); // уникальный идентификатор для collapse и модалок

            const projectItem = document.createElement("li");
            projectItem.className = "accordion list-group-item pe-auto";
            projectItem.id = `project-p-${i}`;
            projectItem.setAttribute("data-project-id", data.id_project);
            projectItem.setAttribute("draggable", false);

            projectItem.innerHTML = `
            <div class="widget-content p-0">
                <div class="widget-content-wrapper">
                <form name="id_project_task" action="projects.php" method="GET">
                    <input hidden name="idProject" value="${data.id_project}">
                    <button class="btn" type="submit">
                    <div class="widget-content-left">
                        <div class="text-center widget-heading text-primary project-title">
                        ${data.project_name}
                        </div>
                        <div class="widget-subheading text-muted project-start"><i>Start: ${data.start_date}</i></div>
                        <div class="widget-subheading text-muted project-end"><i>End: ${data.end_date}</i></div>
                    </div>
                    </button>
                    ${data.project_description ? `
                    <a class="d-flex justify-content-center nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-p-${i}" aria-expanded="false">
                        <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2 pr-2"></i></span>
                        <div id="collapse-p-${i}" class="collapse" data-parent="#project-p-${i}">
                        <p class="font-small text-dark pt-1 project-description">${data.project_description}</p>
                        </div>
                    </a>
                    ` : ''}
                </form>
                <div class="widget-content-right ml-auto d-flex flex-nowrap">
                    <button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#project-edit-${i}"> <i class="fas fa-pencil-alt"></i></button>
                    <button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#project-delete-${i}"> <i class="fas fa-trash-alt"></i></button>
                </div>
                </div>
            </div>

            <!-- EDIT MODAL -->
            <div id="project-edit-${i}" class="modal fade" role="dialog">
                <div class="modal-dialog"><div class="modal-content">
                <div class="modal-header">
                    <h3 class="lead text-edit">Изменить проект</h3>
                    <a class="close text-white btn" data-dismiss="modal">×</a>
                </div>
                <form class="edit-project-form" method="POST">
                    <div class="modal-body">
                    <div class="form-group">
                        <label class="text-dark">Название<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="edit_project_name" value="${data.project_name}" required>
                    </div>
                    <div class="form-group">
                        <label class="text-dark">Описание</label>
                        <textarea class="form-control" name="edit_project_description">${data.project_description || ''}</textarea>
                    </div>
                    <div class="form-group d-flex justify-content-between mt-2">
                        <div class="col-6 mt-0 p-1">
                        <label class="text-dark">Начало<span class="text-danger pl-1">*</span></label>
                        <input type="date" class="form-control" name="edit_start_date" value="${data.start_date}" required>
                        </div>
                        <div class="col-6 mt-0 p-1">
                        <label class="text-dark">Конец<span class="text-danger pl-1">*</span></label>
                        <input type="date" class="form-control" name="edit_end_date" value="${data.end_date}" required>
                        </div>
                    </div>
                    <input type="hidden" name="edit_id_project" value="${data.id_project}">
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Обновить</button>
                    </div>
                </form>
                </div></div>
            </div>

            <!-- DELETE MODAL -->
            <div id="project-delete-${i}" class="modal fade" role="dialog">
                <div class="modal-dialog"><div class="modal-content">
                <div class="text-white modal-header">btn-outline-primary
                    <h3 class="lead text-edit">Вы уверены?</h3>
                    <a class="close text-white btn" data-dismiss="modal">×</a>
                </div>
                <form class="delete-project-form" method="POST">
                    <div class="modal-body">
                    <p class="text-white">Вы хотите безвозвратно удалить <i class="text-primary">${data.project_name}</i>?</p>
                    <input type="hidden" name="delete_project_id" value="${data.id_project}">
                    </div>
                    <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Удалить</button>
                    </div>
                </form>
                </div></div>
            </div>
            `;

            document.querySelector(".proj .list-group").prepend(projectItem);
            bindProjectModals();
            bindModalButtonBlurHandlers();
        }
        });

        function bindProjectModals() {
            document.querySelectorAll(".edit-project-form").forEach(form => {
                if (!form.dataset.bound) {
                form.dataset.bound = "true";
                form.addEventListener("submit", handleEditProject);
                }
            });

            document.querySelectorAll(".delete-project-form").forEach(form => {
                if (!form.dataset.bound) {
                form.dataset.bound = "true";
                form.addEventListener("submit", handleDeleteProject);
                }
            });
            }

            async function handleEditProject(e) {
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
                    title: "Проект обновлён!",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                    });

                    taskSocket.send(JSON.stringify({
                    type: "edit-project",
                    id: form.querySelector('[name="edit_id_project"]').value,
                    project_name: form.querySelector('[name="edit_project_name"]').value,
                    project_description: form.querySelector('[name="edit_project_description"]').value,
                    start_date: form.querySelector('[name="edit_start_date"]').value,
                    end_date: form.querySelector('[name="edit_end_date"]').value
                    }));

                    $(form.closest('.modal')).modal('hide');
                    document.activeElement.blur();
                } else {
                    Swal.fire("Ошибка!", "Не удалось обновить проект.", "error");
                }
                }

            async function handleDeleteProject(e) {
            e.preventDefault();
            const form = e.target;
            const projectId = form.querySelector('[name="delete_project_id"]').value;
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
                const el = document.querySelector(`[data-project-id="${projectId}"]`);
                if (el) el.remove();

                taskSocket.send(JSON.stringify({
                    type: "delete-project",
                    id: projectId
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
                Swal.fire("Ошибка!", "Не удалось удалить проект.", "error");
            }
            }

      document.querySelectorAll("[data-task-id]").forEach(taskEl => {
        const taskId = taskEl.dataset.taskId;
        const taskStatus = parseInt(
          taskEl.closest(".drop-zone")?.getAttribute("task-status") || "1"
        );
        updateTaskArrows(taskEl, taskId, taskStatus);
      });


});
