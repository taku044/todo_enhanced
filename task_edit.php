<?php
session_start();
require 'database.php';


// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 編集対象タスク取得
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['id'], $_SESSION['user_id']]);
    $task = $stmt->fetch();
    if (!$task) {
        header("Location: index.php");
        exit;
    }
}

// 更新処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newTitle = trim($_POST['title']);
    $stmt = $pdo->prepare("UPDATE tasks SET title = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$newTitle, $_POST['id'], $_SESSION['user_id']]);
    header("Location: index.php");
    exit;
}
?>

<form method="POST">
  <input type="hidden" name="id" value="<?= htmlspecialchars($task['id']) ?>">
  <input type="text" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
  <button type="submit">更新</button>
</form>