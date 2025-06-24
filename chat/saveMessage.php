<?php
require '../db/functions.php';
session_start();

// Проверка на наличие данных в сессии и POST
if (!isset($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Пользователь не авторизован']);
    exit;
}

if (empty($_POST['chatId']) || empty($_POST['message'])) {
    echo json_encode(['status' => 'error', 'message' => 'Недостаточно данных']);
    exit;
}

$db = new Database();
$conn = $db->connection();

$chatId = $_POST['chatId'];
$senderId = $_SESSION['id_user']; 
$message = $_POST['message'];

// Подготовка и выполнение запроса
$stmt = $conn->prepare("INSERT INTO messages (chat_id, sender_id, message) VALUES (:chatId, :senderId, :message)");

if ($stmt->execute([
    'chatId' => $chatId,
    'senderId' => $senderId,
    'message' => $message
])) {
    $id = $conn->lastInsertId(); // Получаем ID нового сообщения
    echo json_encode(['status' => 'success', 'id' => $id]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ошибка при сохранении сообщения']);
}
