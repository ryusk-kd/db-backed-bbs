<?php
// logout
session_start();
unset($_SESSION['user_name']);
header('refresh:3;url=../');
?>
<!DOCTYPE html>
<html lang="jp">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
</head>

<body>
    <p>ログアウトしました。</p>
</body>

</html>