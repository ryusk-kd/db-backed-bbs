<?php
// include db_connect.php, function.php
require '../db_connect.php';
require '../function.php';

// check login status
session_start();
if (!isset($_SESSION['user_name'])) {
    // go to login
    echo 'ログインしてください。';
    header('refresh:3;url=../login');
    exit();
} else {
    $navlink = '<li><a href="../logout" class="nav_button">ログアウト</a></li>';
}

// connect to db
$pdo = db_connect();

// check $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    // delete post
    $post_id = $_POST['post_id'];
    if (delete_post($pdo, $post_id)) {
        header('Location: ./');
    } else {
        echo '削除に失敗しました。';
        header('refresh:3;url=./');
        exit();
    }
}

// get posts
$stmt = $pdo->prepare('select * from posts where username = :username');
$stmt->bindValue(':username', $_SESSION['user_name']);
$stmt->execute();
$fetchedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$posts = '';
$postNumber = 1;
foreach ($fetchedPosts as $post) {
    $posts .= '<div>' .
        '<p class="post">' .
        '<span class="post_number">' . $postNumber . ': </span>' .
        '<span class="date">' . $post['created_at'] . ' </span>' .
        '<span class="content">' . nl2br($post['content']) . ' </span>' .
        '</p>' .
        '<form method="post">
        <input type="hidden" name="post_id" value="' . $post['post_id'] . '">
        <input type="submit" name="delete" value="削除"></form>' .
        '</div>';
    $postNumber++;
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <nav>
            <ul>
                <?php echo $navlink; ?>
                <li><a href="../index.php" class="nav_button">話題一覧</a></li>
            </ul>
        </nav>
        <div id="posts">
            <?php echo $posts; ?>
        </div>
    </div>
</body>

</html>