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
    // Sanitize and validate the username input
    $username = htmlspecialchars($_POST['username']);
    $length = mb_strlen($username);

    // Hash the password
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the username already exists
    if (check_user_name_exists($pdo, $username) > 0) {
        echo $username . 'は既に登録されています。';
    } else if ($length > 24) {
        echo 'ユーザー名は24文字以内で入力してください。';
    } else {
        // Insert the new user into the database and redirect to the login page
        insert_user($pdo, $username, $hashedPassword);
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