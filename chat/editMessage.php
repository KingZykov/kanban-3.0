<?php
session_start();
header('Content-Type: application/json');
require_once '../db/functions.php';

if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Не авторизован']);
    exit;
}

if (empty($_POST['id']) || empty($_POST['message'])) {
    echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
    exit;
}

$id = (int)$_POST['id'];
$message = trim($_POST['message']);
$userId = $_SESSION['id_user'];

$db = new Database();
$conn = $db->connection();

// Разрешаем редактировать только свои сообщения
$stmt = $conn->prepare("UPDATE messages SET message = :message WHERE id = :id AND sender_id = :sender_id");
$success = $stmt->execute([
    'message' => $message,
    'id' => $id,
    'sender_id' => $userId
]);

if ($success && $stmt->rowCount() > 0) {
    // WebSocket broadcast
    $wsData = json_encode([
        'type' => 'edit',
        'id' => $id,
        'message' => $message
    ]);

    $sock = stream_socket_client("tcp://127.0.0.1:8081", $errno, $errstr);
    if ($sock) {
        fwrite($sock, $wsData);
        fclose($sock);
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Обновление не выполнено']);
}
