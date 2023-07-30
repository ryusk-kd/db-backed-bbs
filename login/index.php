<?php
// include db_connect.php, function.php
require '../db_connect.php';
require '../function.php';

// Session start and Unset $_SESSION['user_name']
session_start();
unset($_SESSION['user_name']);

// db connect
$pdo = db_connect();

// check login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize username in $_POST; set $username and $password
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // check username and password
    $stmt = $pdo->prepare('select pwhash from users where username = :username');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $hashedPassword = $stmt->fetchColumn();
    if ($hashedPassword !== false && password_verify($password, $hashedPassword)) {
        echo 'ログインしました。';
        $_SESSION['user_name'] = $username;
        header("refresh:3;url=../");
        exit();
    } else {
        echo 'ログインに失敗しました。';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <nav>
            <ul>
                <li><a href="../signup" class="nav_button">新規登録</a></li>
                <li><a href="../" class="nav_button">話題一覧</a></li>
            </ul>
        </nav>
        <form method="post">
            <label for="username">ユーザー名：</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">パスワード：</label>
            <input type="password" id="password" name="password" required>
            <br>
            <input type="submit" value="ログイン">
        </form>
    </div>
</body>

</html>