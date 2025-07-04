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
   <h1>新規登録</h1> 
  <input type="text" name="username" placeholder="ユーザー名" required></br>
  <input type="password" name="password" placeholder="パスワード" required></br>
  <button type="submit" name="check" value="registration">登録</button>
</form>
    
<a href="login.php">ログインはこちら</a>
    
</body>
</html>