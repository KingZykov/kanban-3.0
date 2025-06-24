<?php
session_start();
header('Content-Type: application/json');
require_once '../db/functions.php';

$db = new Database();
$conn = $db->connection();

if (!$conn) {
    echo json_encode(['error' => 'Отсутствует подключение к базе данных']);
    exit;
}


if (!isset($currentId)) {
    $currentId = $_SESSION['id_user'] ?? null;
}

if (!$currentId || !isset($_GET['user_id'])) {
    echo json_encode(['error' => 'Пользователь не авторизован или параметры отсутствуют']);
    exit;
}

$otherId = (int)$_GET['user_id'];

if (!$otherId || $currentId === $otherId) {
    echo json_encode(['error' => 'Некорректный выбор собеседника']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM chats WHERE user_min_id = LEAST(:u1, :u2) AND user_max_id = GREATEST(:u1, :u2)");
$stmt->execute(['u1' => $currentId, 'u2' => $otherId]);
$chat = $stmt->fetch(PDO::FETCH_ASSOC);

$chatId = $chat['id'] ?? null;

if (!$chatId) {
    $stmt = $conn->prepare("INSERT INTO chats (user1_id, user2_id) VALUES (:u1, :u2)");
    $stmt->execute(['u1' => $currentId, 'u2' => $otherId]);
    $chatId = $conn->lastInsertId();
}

echo json_encode(['chatId' => $chatId]);
exit;
