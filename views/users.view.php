<?php
require 'connection.php';
session_start();

$currentId = $_SESSION['user_id'];

$result = $conn->query("SELECT id_user, user FROM users WHERE id_user != $currentId");
while ($row = $result->fetch_assoc()) {
    echo "<div>{$row['user']} <a href='chat.view.php?user_id={$row['id_user']}'>Чат</a></div>";
}
?>
