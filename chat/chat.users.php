<?php
if (!isset($currentId)) {
    $currentId = $_SESSION['id_user'] ?? 0;
}

$stmt = $connection->prepare("SELECT id_user, user FROM users WHERE id_user != :id");
$stmt->execute(['id' => $currentId]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $u): ?>
  <li>
    <a href="javascript:void(0)" onclick="openChat(<?= $u['id_user'] ?>, '<?= addslashes($u['user']) ?>')">
      <?= htmlspecialchars($u['user']) ?>
    </a>

  </li>
<?php endforeach; ?>
