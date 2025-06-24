<?php
header('Content-Type: application/json');
session_start();
require '../db/functions.php';

$db = new Database();
$conn = $db->connection();

$chatId = $_GET['chatId'] ?? 0;

if (!$chatId) {
    echo json_encode(['error' => 'chatId не передан']);
    exit;
}

$stmt = $conn->prepare("
    SELECT m.id, m.message, m.created_at, u.user AS sender_name
    FROM messages m
    JOIN users u ON m.sender_id = u.id_user
    WHERE m.chat_id = :chatId
    ORDER BY m.created_at ASC
");
$stmt->execute(['chatId' => $chatId]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
