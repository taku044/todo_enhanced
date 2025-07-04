<?php
session_start();
require 'database.php';


// 未ログインならリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// POSTでタスクが送られてきた場合
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title']);

    if ($title !== '') {
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, is_done, created_at) VALUES (?, ?, 0, NOW())");
        $stmt->execute([$_SESSION['user_id'], $title]);
    }
}

header("Location: index.php");
exit;
?>