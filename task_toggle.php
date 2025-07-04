<?php
session_start();
require 'database.php';


// ログインチェック
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 現在の状態を反転して更新
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    // 現在の状態を取得
    $stmt = $pdo->prepare("SELECT is_done FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['id'], $_SESSION['user_id']]);
    $task = $stmt->fetch();

    if ($task) {
        $newStatus = $task['is_done'] ? 0 : 1;
        $update = $pdo->prepare("UPDATE tasks SET is_done = ? WHERE id = ? AND user_id = ?");
        $update->execute([$newStatus, $_POST['id'], $_SESSION['user_id']]);
    }
}

header("Location: index.php");
exit;
?>