<?php session_start();

if (isset($_SESSION['user'])) {
	include '../db/functions.php';
	$database = new Database();
	$connection = $database->connection();
} else {
	header('Location: administration/main.php');
	die();
}
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

function sendWebSocketUpdate($data) {
	$socket = fsockopen("127.0.0.1", 8081, $errno, $errstr, 2);
	if ($socket) {
		fwrite($socket, json_encode($data));
		fclose($socket);
	}
}
// -------------------- SHOWING PROJECTS -------------------------

$projects = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects ORDER BY id_project DESC");
$projects->execute();
$projects = $projects->fetchAll();


	//Вставка кода
	$result1 = $connection->prepare("SELECT user FROM users ORDER BY id_user DESC");
	$result1->execute();
	$result1 = $result1->fetchAll();
	//Вставка кода	

// -------------------- SHOWING TASKS -------------------------
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !$isAjax) {
    if (isset($_GET['idProject'])) {
        $id_project_for_task = filter_var(htmlspecialchars($_GET['idProject']), FILTER_SANITIZE_SPECIAL_CHARS); 
        $user_name = $_SESSION['user'];
        $user_role = $_SESSION['role'];

        if ($user_role == "admin") {
            $show_tasks = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE id_project = ? ORDER BY deadline DESC");
            $show_tasks->execute([$id_project_for_task]);
        } elseif ($user_role == "user") {
            $show_tasks = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE user_name = ? AND id_project = ? ORDER BY deadline DESC");
            $show_tasks->execute([$user_name, $id_project_for_task]);
        }

        $show_tasks = $show_tasks->fetchAll();
    }

    require '../views/projects.view.php'; // ✅ теперь он подключается только при обычном запросе
}



if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// --------------------- ADDING NEW PROJECTS -------------------------
	if ( isset($_POST['project_name']) AND isset($_POST['start_date']) AND isset($_POST['end_date']) ) {

		$project_name = filter_var(htmlspecialchars($_POST['project_name']), FILTER_SANITIZE_SPECIAL_CHARS);
		$project_description = filter_var(htmlspecialchars($_POST['project_description']), FILTER_SANITIZE_SPECIAL_CHARS);
		$start_date= filter_var(htmlspecialchars($_POST['start_date']), FILTER_SANITIZE_SPECIAL_CHARS); 
		$start_date= date("Y-m-d", strtotime($start_date));
		$end_date= filter_var(htmlspecialchars($_POST['end_date']), FILTER_SANITIZE_SPECIAL_CHARS); 
		$end_date= date("Y-m-d", strtotime($end_date));
		$id_user = filter_var(htmlspecialchars($_POST['id_user']), FILTER_SANITIZE_SPECIAL_CHARS);
		$id_user = (int)$id_user; 



		$statement = $connection->prepare('INSERT INTO projects (id_user, project_name, project_description, start_date, end_date) VALUES
		(?, ?, ?, ?, ?)');
		$statement->execute(array($id_user, $project_name, $project_description, $start_date, $end_date));	
		$project_id = $connection->lastInsertId();

		if ($project_id) {
			// WebSocket
			$sock = stream_socket_client("tcp://127.0.0.1:8081", $errno, $errstr, 1);
			if ($sock) {
				fwrite($sock, json_encode([
					'type' => 'create-project',
					'id_project' => $project_id,
					'project_name' => $project_name,
					'project_description' => $project_description,
					'start_date' => $start_date,
					'end_date' => $end_date,
					'id_user' => $id_user
				]));
				fclose($sock);
			}

			// AJAX-ответ
			echo json_encode([
				'status' => 'success',
				'id_project' => $project_id
			]);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}

	// -------------------- DELETING PROJECT --------------------------
	if (isset($_POST['delete_project_id'])) {
		$delete_id_project = filter_var($_POST['delete_project_id'], FILTER_SANITIZE_SPECIAL_CHARS);

		$stmt = $connection->prepare("DELETE FROM projects WHERE id_project = ?");
		$success = $stmt->execute([$delete_id_project]);

		if ($success) {
			sendWebSocketUpdate([
				'type' => 'delete-project',
				'id' => $delete_id_project
			]);

			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}


	// --------------------------- EDITING PROJECT -------------------------------
	if (isset($_POST['edit_id_project'])) {
		$edit_id_project = filter_var($_POST['edit_id_project'], FILTER_SANITIZE_SPECIAL_CHARS);
		$edit_project_name = filter_var($_POST['edit_project_name'], FILTER_SANITIZE_SPECIAL_CHARS);
		$edit_project_description = filter_var($_POST['edit_project_description'], FILTER_SANITIZE_SPECIAL_CHARS);
		$edit_start_date = date("Y-m-d", strtotime($_POST['edit_start_date']));
		$edit_end_date = date("Y-m-d", strtotime($_POST['edit_end_date']));

		$statement = $connection->prepare('UPDATE projects SET project_name=?, project_description=?, start_date=?, end_date=? WHERE id_project=?');
		$success = $statement->execute([
			$edit_project_name,
			$edit_project_description,
			$edit_start_date,
			$edit_end_date,
			$edit_id_project
		]);

		if ($success) {
			sendWebSocketUpdate([
				'type' => 'edit-project',
				'id' => $edit_id_project,
				'project_name' => $edit_project_name,
				'project_description' => $edit_project_description,
				'start_date' => $edit_start_date,
				'end_date' => $edit_end_date
			]);

			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}
	

	// --------------------------- ADDING NEW TASK -------------------------------
	if (
    isset($_POST['task_name'], $_POST['id_task_project'], $_POST['id_user']) &&
    $isAjax
) {
    header('Content-Type: application/json');

    $id_project = (int) filter_var($_POST['id_task_project'], FILTER_SANITIZE_SPECIAL_CHARS);
    $id_user = (int) filter_var($_POST['id_user'], FILTER_SANITIZE_SPECIAL_CHARS);
    $task_status = (int) filter_var($_POST['task_status'], FILTER_SANITIZE_SPECIAL_CHARS);
    $task_name = filter_var($_POST['task_name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $task_description = filter_var($_POST['task_description'], FILTER_SANITIZE_SPECIAL_CHARS);
    $task_color = filter_var($_POST['task_color'], FILTER_SANITIZE_SPECIAL_CHARS);
    $deadline = date("Y-m-d", strtotime($_POST['deadline']));
    $user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_SPECIAL_CHARS);

    $statement = $connection->prepare('INSERT INTO tasks 
        (id_user, id_project, task_status, task_name, task_description, task_color, deadline, user_name) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $success = $statement->execute([
        $id_user, $id_project, $task_status, $task_name,
        $task_description, $task_color, $deadline, $user_name
    ]);

    if ($success) {
    $taskId = $connection->lastInsertId();

    $sock = stream_socket_client("tcp://127.0.0.1:8081", $errno, $errstr, 1);
    if ($sock) {
        fwrite($sock, json_encode([
            'type' => 'create-task',
            'id' => $taskId,
            'id_project' => $id_project,
            'task_status' => $task_status,
            'task_name' => $task_name,
            'task_description' => $task_description,
            'task_color' => $task_color,
            'deadline' => $deadline,
            'user_name' => $user_name
        ]));
        fclose($sock);
    }

    echo json_encode([
        'status' => 'success',
        'id_project' => $id_project,
        'id_task' => $taskId  // 🔁 обязательно!
    ]);
	} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Не удалось создать задачу'
			]);
		}

		exit;
}


// -------------------- DELETING TASK --------------------------
if (isset($_POST['id_task'], $_POST['id_project']) && $isAjax) {
    header('Content-Type: application/json');

    $id_task = filter_var(htmlspecialchars($_POST['id_task']), FILTER_SANITIZE_SPECIAL_CHARS);
    $id_project = filter_var(htmlspecialchars($_POST['id_project']), FILTER_SANITIZE_SPECIAL_CHARS);

    $del_task = $connection->prepare("DELETE FROM tasks WHERE id_task = ?");
    $success = $del_task->execute([$id_task]);

    if ($success) {
        // отправка WebSocket-сообщения
        $sock = stream_socket_client("tcp://127.0.0.1:8081", $errno, $errstr, 1);
        if ($sock) {
            fwrite($sock, json_encode([
                'type' => 'delete-task',
                'id' => (int)$id_task
            ]));
            fclose($sock);
        }

        echo json_encode(['status' => 'success', 'id_project' => $id_project]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Ошибка удаления']);
    }
    exit;
}

// --------------------------- EDITING TASK -------------------------------
if (isset($_POST['edit_id_task'])) {
    $id_project           = filter_var(htmlspecialchars($_POST['id_task_project']), FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_id_task         = filter_var(htmlspecialchars($_POST['edit_id_task']), FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_task_name       = filter_var(htmlspecialchars($_POST['edit_task_name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_task_description= filter_var(htmlspecialchars($_POST['edit_task_description']), FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_task_color      = filter_var(htmlspecialchars($_POST['edit_task_color']), FILTER_SANITIZE_SPECIAL_CHARS);
    $edit_user_name       = filter_var(htmlspecialchars($_POST['edit_user_name']), FILTER_SANITIZE_SPECIAL_CHARS);
    $deadline             = date("Y-m-d", strtotime($_POST['deadline']));

    // Обновление задачи в базе данных
    $statement = $connection->prepare('
        UPDATE tasks 
        SET task_name = ?, task_description = ?, task_color = ?, deadline = ?, user_name = ? 
        WHERE id_task = ?
    ');
    $result = $statement->execute([
        $edit_task_name,
        $edit_task_description,
        $edit_task_color,
        $deadline,
        $edit_user_name,
        $edit_id_task
    ]);

    if ($result) {
        // Уведомление по WebSocket через TCP
        $sock = stream_socket_client("tcp://127.0.0.1:8081", $errno, $errstr, 1);
        if ($sock) {
            fwrite($sock, json_encode([
                'type'              => 'edit-task',
                'id'                => (int)$edit_id_task,
                'task_name'         => $edit_task_name,
                'task_description'  => $edit_task_description,
                'task_color'        => $edit_task_color,
                'deadline'          => $deadline,
                'user_name'         => $edit_user_name,
                'id_project'        => (int)$id_project // 👈 ОБЯЗАТЕЛЬНО добавлено для JS фильтрации
            ]));
            fclose($sock);
        }

        echo json_encode([
            'status'      => 'success',
            'id_project'  => $id_project
        ]);
    } else {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Ошибка при обновлении задачи'
        ]);
    }

    exit;
}

	// --------------------------- MOVING TASK TO THE RIGHT -------------------------------
	if (isset($_POST['id_task_right'])) {
		$id_project_right = filter_var($_POST['id_project_right'], FILTER_SANITIZE_SPECIAL_CHARS);
		$id_task_right = filter_var($_POST['id_task_right'], FILTER_SANITIZE_SPECIAL_CHARS);
		$task_status = filter_var($_POST['task_status'], FILTER_SANITIZE_SPECIAL_CHARS);
		$new_status = ((int)$task_status + 1);

		$statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
		$success = $statement->execute([$new_status, $id_task_right]);

		if ($success) {
			sendWebSocketUpdate([
				'type' => 'task-status-change',
				'taskId' => $id_task_right,
				'task_status' => $new_status
			]);

			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}


	// --------------------------- MOVING TASK TO THE LEFT -------------------------------
	if (isset($_POST['id_task_left'])) {
		$id_project_left = filter_var($_POST['id_project_left'], FILTER_SANITIZE_SPECIAL_CHARS);
		$id_task_left = filter_var($_POST['id_task_left'], FILTER_SANITIZE_SPECIAL_CHARS);
		$task_status = filter_var($_POST['task_status'], FILTER_SANITIZE_SPECIAL_CHARS);
		$new_status = ((int)$task_status - 1);

		$statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
		$success = $statement->execute([$new_status, $id_task_left]);

		if ($success) {
			sendWebSocketUpdate([
				'type' => 'task-status-change',
				'taskId' => $id_task_left,
				'task_status' => $new_status
			]);

			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
		exit;
	}
	// --------------------------- Drag & Drop -------------------------------
/* 	header('Content-Type: application/json');
	echo json_encode($data); */
	if(isset($_POST['taskId'])) {
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$id_task_drop = $_POST['taskId'];
			$new_status = $_POST['newStatus'];
		
			$statement = $connection->prepare("UPDATE tasks SET task_status = ? WHERE id_task = ?");
			if ($statement->execute(array($new_status, $id_task_drop))) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error']);
			}
	}
		// $move_task = $statement->fetch();
		// if (isset($move_task)) {
		// 	echo '<script language="javascript">';
		// 	echo "Swal.fire({
		// 		timer: 5,
		// 	}).then(function(){ 
		// 		location.href = '../administration/projects.php?idProject=";				
		// 	echo "$id_project'";			
		// 	echo "});";
		// 	echo '</script>';
		// }
			



	}

}
/*
		$role = $_POST['role'];
		var_dump($role);      // Выводит тип и значение переменной
        print_r($role); 
*/

if ($isAjax) {
    exit;
}
// --------------------------- chat -------------------------------
if (isset($_GET['id_user'])) {
	if (!isset($connection)) {
		die("❌ Ошибка: отсутствует подключение к базе данных.");
	}

	if (!isset($_SESSION['id_user'])) {
		die("❌ Ошибка: пользователь не авторизован.");
	}

    $currentId = $_SESSION['id_user'];
    $otherId = (int)$_GET['id_user'];

    if (!$otherId || $otherId == $currentId) {
        die("Некорректный пользователь");
    }

    $stmt = $connection->prepare("SELECT id FROM chats WHERE user_min_id = LEAST(:u1, :u2) AND user_max_id = GREATEST(:u1, :u2)");
    $stmt->execute(['u1' => $currentId, 'u2' => $otherId]);
    $chat = $stmt->fetch(PDO::FETCH_ASSOC);

    $chatId = $chat ? $chat['id'] : null;

    if (!$chatId) {
        $stmt = $connection->prepare("INSERT INTO chats (user1_id, user2_id) VALUES (:u1, :u2)");
        $stmt->execute(['u1' => $currentId, 'u2' => $otherId]);
        $chatId = $connection->lastInsertId();
    }

    include '../views/chat.view.php';
}



?>