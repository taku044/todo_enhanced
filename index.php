<?php
require 'db.php';
session_start();

// 未ログインならリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


// 検索キーワード処理
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? AND title LIKE ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id'], '%' . $keyword . '%']);
}// タスク取得 
else {
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
}
$tasks = $stmt->fetchAll();
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>こんにちは、<?= htmlspecialchars($_SESSION['user_id']) ?> さん</h1>
    <a href="logout.php">ログアウト</a>

    <h2>タスクを追加</h2>
    <form method="POST" action="task_add.php">
        <input type="text" name="title" placeholder="タスク内容" required>
        <label for="deadline">年/月/日</label>
    <input type="date" id="deadline" name="deadline" required>

    <label for="priority">優先度</label>
    <select id="priority" name="priority" required>
        <option value="低">優先度（低）</option>
        <option value="中">中</option>
        <option value="高">高</option>
    </select>

        <button type="submit">追加</button>
    </form>

    <h2>タスクを検索</h2>
    <form method="GET">
        <input type="text" name="keyword" placeholder="キーワード" value="<?= htmlspecialchars($keyword) ?>">
        <label for="deadline">すべて</label>
        <input type="date" id="deadline" name="deadline" required>

        <label for="priority">優先度</label>
        <select id="priority" name="priority" required>
            <option value="">優先度（全て）</option>
            <option value="低">低</option>
            <option value="中">中</option>
            <option value="高">高</option>
        </select>
        <button type="submit">検索</button>
    </form>



    
    <ul>
    <?php foreach ($tasks as $task): ?>
        <li>
            <form style="display:inline;" method="POST" action="task_toggle.php">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <button type="submit"><?= $task['is_done'] ? '✅' : '⬜' ?></button>
            </form>
            <span style="<?= $task['is_done'] ? 'text-decoration: line-through;' : '' ?>">
                <?= htmlspecialchars($task['title']) ?>
            </span>
            <a href="task_edit.php?id=<?= $task['id'] ?>">編集</a>
            <a href="task_delete.php?id=<?= $task['id'] ?>" onclick="return confirm('削除しますか？')">削除</a>
        </li>
    <?php endforeach; ?>
    </ul>
</body>
</html>