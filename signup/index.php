<?php
// include db_connect.php, function.php
require '../db_connect.php';
require '../function.php';

// Session start and Unset $_SESSION['user_name']
session_start();
unset($_SESSION['user_name']);

// db connect
$pdo = db_connect();

// check $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize username; username must be less than 24 characters
    $username = htmlspecialchars($_POST['username']);
    $length = mb_strlen($username);

    // get hashed password
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // check username
    $stmt = $pdo->prepare('select count(*) from users where username = :username');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        echo $username . 'は既に登録されています。';
    } else if ($length > 24) {
        echo 'ユーザー名は24文字以内で入力してください。';
    } else {
        // insert new user into database and redirect to login page if successful
        $stmt = $pdo->prepare('insert into users (username, pwhash) values (:username, :hashedPassword)');
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':hashedPassword', $hashedPassword);
        $stmt->execute();
        echo $username . 'を登録しました。';
        header("refresh:3;url=../login");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <nav>
            <ul>
                <li><a href="../login" class="nav_button">ログイン</a></li>
                <li><a href="../" class="nav_button">話題一覧</a></li>
            </ul>
        </nav>
        <form method="post">
            <label for="username">ユーザー名：</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">パスワード：</label>
            <input type="password" id="password" name="password" required minlength="8">
            <br>
            <input type="submit" value="登録">
        </form>
    </div>
</body>

</html>