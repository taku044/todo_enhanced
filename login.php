<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
   <?php
   session_start();
require 'database.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit;
    } else {
        $error = "ユーザー名またはパスワードが違います";
    }
}
?>
<form method="post">
  <input type="text" name="username" placeholder="ユーザー名" required>
  <input type="password" name="password" placeholder="パスワード" required>
  <button type="submit">ログイン</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
    <a href="register.php">新規登録</a>
    
</body>
</html>