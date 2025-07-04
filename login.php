<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<form method="post" action="database.php">
  <h1>ログイン</h1>  
  <input type="text" name="username" placeholder="ユーザー名" required value="test"></br>
  <input type="password" name="password" placeholder="パスワード" required value="1234"></br>
  <button type="submit" name="check" value="login">ログイン</button>
</form>
<?php if (isset($error)) echo "<p>$error</p>"; ?>
    <a href="register.php">新規登録</a>
    
</body>
</html>