<?php
// debug
ini_set('display_errors', 'On');

// include db_connect.php
require '../db_connect.php';

// include function.php
require '../function.php';

// check login
if (isset($_POST) && !empty($_POST)) {
    // validate input data
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // check username and password
    $pdo = db_connect();
    $stmt = $pdo->prepare('select pwhash from users where username = :username');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $hashedPassword = $stmt->fetchColumn();
    if ($hashedPassword !== false && password_verify($password, $hashedPassword)) {
        echo 'Login successful.<br>';
        // session start
        session_start();
        $_SESSION['user_name'] = $username;
        // header("refresh:5;url=../");
    } else {
        echo 'Invalid username or password.<br>';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>
</body>

</html>