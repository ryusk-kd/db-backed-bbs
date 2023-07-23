<?php
// logout
session_start();
unset($_SESSION['user_name']);
header('refresh:5;url=../');
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log out</title>
</head>

<body>
    <p>ログアウトしました。5秒後にTopicsページにリダイレクトします。</p>
</body>

</html>