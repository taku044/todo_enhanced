<?php
session_start();

$db = [
    'host' => "mysql321.phy.lolipop.lan",
    'user' => "LAA1554882",
    'pass' => "2301192",
    'dbname' => "LAA1554882-todolist"
];

// DB接続
try {
    $pdo = new PDO(
        'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'] . ';charset=utf8',
        $db['user'],
        $db['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('データベース接続失敗: ' . $e->getMessage());
}

//ログイン処理
function login($pdo)
{
    if (empty($_POST['name']) || empty($_POST['password'])) {
        setErrorAndRedirect('ログインに失敗しました。ユーザー名またはパスワードをご確認ください。1', 'login.php');
    }

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
    $stmt->execute([$_POST['name'], $_POST['password']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: https://aso2301192.hippy.jp/todolist/form.php");
        exit();
    } else {
        setErrorAndRedirect('ログインに失敗しました。ユーザー名またはパスワードをご確認ください。', 'login.php');
    }
}

//新規登録
function register($pdo)
{
    if (empty($_POST['username']) || empty($_POST['password'])) {
        setErrorAndRedirect('登録に失敗しました。必要な情報をご確認ください。', 'Registration.php');
    }

    try {
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([
            $_POST['username'],
            $_POST['password']
        ]);

        // 登録後ログイン処理
        $stm = $pdo->prepare('SELECT * FROM users WHERE username = ? AND password = ?');
        $stm->execute([$_POST['username'], $_POST['password']]);
        $user = $stm->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['id'] = $user['id'];
            $_SESSION['name'] = $user['username'];
            header("Location: list.php");
            exit();
        } else {
            setErrorAndRedirect('登録後のログインに失敗しました。', 'Registration.php');
        }
    } catch (PDOException $e) {
        setErrorAndRedirect('データベースエラー: ' . $e->getMessage(), 'Registration.php');
    }
}

//todo登録
function todo($pdo)
{
    if (empty($_SESSION['id']) || empty($_POST['task']) || empty($_POST['status']) || empty($_POST['due_date'])
        || empty($_POST['priority'])) {
        setErrorAndRedirect('投稿に失敗しました。', 'list.php');
    }

try {
        $stmt = $pdo->prepare('INSERT INTO todos (user_id, task, status, due_date, priority) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $_SESSION['id'],
            $_POST['task'],
            $_POST['status'],
            $_POST['due_date'],
            $_POST['priority'],
        ]);
        $_SESSION['message'] = "投稿に成功しました！";
        header("Location: https://aso2301192.hippy.jp/todolist/list.php");
        exit();
    } catch (PDOException $e) {
        setErrorAndRedirect('データベースエラー: ' . $e->getMessage(), 'list.php');
    }
}
function setErrorAndRedirect($message, $redirectPath)
{
    $_SESSION['login_error'] = $message;
    header("Location: https://aso2301192.hippy.jp/todolist/" . $redirectPath);
    exit();
}



// ログイン処理を実行
if ($_POST['check'] === 'login') {
    login($pdo);
} 
elseif ($_POST['check'] === 'registration') {
    todo($pdo);
}
elseif ($_POST['check'] === 'todos') {
    todo($pdo);
}
?>
