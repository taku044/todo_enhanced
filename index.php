<?php
session_start();

// 未ログインならリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $pdo = new PDO(
        'mysql:host=' . $_SESSION['host'] . ';dbname=' . $_SESSION['dbname'] . ';charset=utf8',
        $_SESSION['user'],
        $_SESSION['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}
// 検索キーワード処理
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
if ($keyword !== '') {
    $stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? AND title LIKE ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id'], '%' . $keyword . '%']);
}// タスク取得 
else {
    $stmt = $pdo->prepare("SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC");
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
    <header>
    <h1>ToDoリスト</h1>
    <?= htmlspecialchars($_SESSION['username']) ?> さん
    <a href="logout.php">ログアウト</a>
    </header>
    <h2>タスクを追加</h2>
    <form method="POST" action="database.php">
        <input type="text" name="task" placeholder="タスク内容" required>
        <label for="due_date">年/月/日</label>
    <input type="date" id="due_date" name="due_date" required>
    <label for="priority">優先度</label>
    <select id="priority" name="priority" required>
        <option value="0">優先度（低）</option>
        <option value="1">中</option>
        <option value="2">高</option>
    </select>
        <button type="submit" name="check" value="todos">追加</button>
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



    
    <table>
        <thead>
        <tr>
            <th>状態</th><th>タスク</th><th>期限</th><th>優先度</th><th>操作</th>
        </tr>
        </thead>
    <tbody>
    <?php foreach ($tasks as $task): ?>
        <tr>
            <form style="display:inline;" method="POST" action="task_toggle.php">
                <input type="hidden" name="id" value="<?= $task['id'] ?>">
                <td><input type="checkbox" name="status" ></td>
            </form>
            <span style="<?= $task['status'] === 'done' ? 'text-decoration: line-through;' : '' ?>">
                <td><?= htmlspecialchars($task['task']) ?></td>
            </span>
            <td><?= $task['due_date'] ?></td>
            <td><?php if($task['priority'] == '0'){
                            echo '低';
                        }elseif($task['priority'] == '1'){
                            echo '中';
                        }else{
                            echo '高';
                        } ?>
            </td>
            <td><a href="task_edit.php?id=<?= $task['id'] ?>">編集</a>
            <a href="task_delete.php?id=<?= $task['id'] ?>" onclick="return confirm('削除しますか？')">削除</a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>