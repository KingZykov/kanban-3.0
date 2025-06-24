<?php
session_start();
header('Content-Type: application/json');
require_once '../db/functions.php';

if (!isset($_POST['id']) || !$_SESSION['id_user']) {
    echo json_encode(['status' => 'error', 'message' => 'Недостаточно прав']);
    exit;
}

$db = new Database();
$conn = $db->connection();

$id = (int)$_POST['id'];
$userId = $_SESSION['id_user'];

// Удаляем только своё сообщение
$stmt = $conn->prepare("DELETE FROM messages WHERE id = :id AND sender_id = :sender_id");
$success = $stmt->execute(['id' => $id, 'sender_id' => $userId]);

if ($success) {
    // Уведомление WebSocket-серверу
    $sock = stream_socket_client("tcp://127.0.0.1:8081", $errno, $errstr);
    if ($sock) {
        $payload = json_encode([
            'type' => 'delete',
            'id' => $id
        ]);
        fwrite($sock, $payload);
        fclose($sock);
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка удаления']);
}
