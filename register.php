<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php
require 'database.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $password]);
    header("Location: login.php");
    exit;
}
?>
<form method="post">
  <input type="text" name="username" placeholder="ユーザー名" required>
  <input type="password" name="password" placeholder="パスワード" required>
  <button type="submit">登録</button>
</form>
    
<a href="login.php">ログインはこちら</a>
    
</body>
</html>