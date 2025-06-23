<?php
session_start();
$_SESSION = [];              // セッション変数を空にする
session_destroy();           // セッションを破棄
header("Location: login.php"); // ログインページへリダイレクト
exit;
?>