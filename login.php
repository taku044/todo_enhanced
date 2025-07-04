
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
 
<form method="post">
  <input type="text" name="username" placeholder="ユーザー名" required>
  <input type="password" name="password" placeholder="パスワード" required>
  <button type="submit">ログイン</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
    <a href="register.php">新規登録</a>
    
</body>
</html>