<?php
// debug
// ini_set('display_errors', 'On');

// include db_connect.php
require '../db_connect.php';

// include function.php
require '../function.php';

if (isset($_POST) && !empty($_POST)) {
    // validate input data
    $username = htmlspecialchars($_POST['username']);
    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $length = mb_strlen($username);

    // check username
    $pdo = db_connect();
    $stmt = $pdo->prepare('select count(*) from users where username = :username');
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        echo 'Username is already taken.<br>';
    } else if ($length > 24) {
        echo 'Username is too long. Username must be less than 24 characters.<br>';
    } else {
        // insert new user into database and redirect to login page if successful
        $stmt = $pdo->prepare('insert into users (username, pwhash) values (:username, :hashedPassword)');
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':hashedPassword', $hashedPassword);
        $stmt->execute();
        echo 'New user created. Redirecting to login page.<br>';
        header("refresh:5;url=../login");
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
        <input type="password" id="password" name="password" required minlength="8"><br>

        <input type="submit" value="Sign Up">
    </form>
</body>

</html>