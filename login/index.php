<?php
// Include necessary files
require '../db_connect.php';
require '../function.php';

// Start session and unset user_name session variable
session_start();
unset($_SESSION['user_name']);

// Connect to the database
$pdo = db_connect();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve the username and password from $_POST
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Check the username and password
    if (authenticateUser($pdo, $username, $password)) {
        // Login successful
        echo 'ログインしました。';
        $_SESSION['user_name'] = $username;
        header("refresh:3;url=../");
        exit();
    } else {
        // Login failed
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