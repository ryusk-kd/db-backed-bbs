<?php
// debug
ini_set('display_errors', 'On');

// include db_connect.php
require '../db_connect.php';

// include function.php
require '../function.php';
/*
$pdo = db_connect();
$stmt = $pdo->prepare('select * from users');
$stmt->execute();
$results = $stmt->fetchAll();
$table = '<table>';
foreach ($results as $row) {
    $table .= '<tr>';
    foreach ($row as $column) {
        $table .= '<td>' . $column;
    }
}
$table .= '</table>';
*/
if ($_POST) {
    var_dump($_POST);
    header("refresh:5;url=index.php");
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

        <input type="submit" value="Sign Up">
    </form>

</body>

</html>