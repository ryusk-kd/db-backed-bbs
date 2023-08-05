<?php
// Include necessary files
require '../db_connect.php';
require '../function.php';

// Check login status
session_start();
if (!isset($_SESSION['user_name'])) {
    // Redirect to login page if user is not logged in
    echo 'ログインしてください。';
    header('refresh:3;url=../login');
    exit();
} else {
    // Display logout button if user is logged in
    $navlink = '<li><a href="../logout" class="nav_button">ログアウト</a></li>';
}

// Connect to the database
$pdo = db_connect();

// Check if the request method is POST and post_id is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    // Delete post
    $post_id = $_POST['post_id'];
    if (delete_post($pdo, $post_id)) {
        // Redirect to current page after successful deletion
        header('Location: ./');
    } else {
        // Display error message if deletion fails
        echo '削除に失敗しました。';
        header('refresh:3;url=./');
        exit();
    }
}

// Get posts for current user
$stmt = $pdo->prepare('SELECT * FROM posts WHERE username = :username');
$stmt->bindValue(':username', $_SESSION['user_name']);
$stmt->execute();
$fetchedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$posts = '';
$postNumber = 1;
foreach ($fetchedPosts as $post) {
    // Generate HTML for each post
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