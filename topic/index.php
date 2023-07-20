<?php
// debug
ini_set('display_errors', 'On');

// include db_connect.php
require '../db_connect.php';

// include function.php
require '../function.php';

// connect to db
$pdo = db_connect();

// get topic
$stmt = $pdo->prepare('select * from topics where topic_id = :id');
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$topic = $stmt->fetch(PDO::FETCH_ASSOC);
$title = $topic['title'];
$outline = $topic['content'];


// load posts
$stmt = $pdo->prepare('select post_id, content, created_at from posts where topic_id = :topic_id');
$stmt->bindValue(':topic_id', $_GET['id']);
$stmt->execute();

// fetch posts
$fetchedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$posts = '';
foreach ($fetchedPosts as $post) {
    $posts .= '<p class="post">' .
        '<span class="post_id">' . $post['post_id'] . ': </span>' .
        '<span class="date">' . $post['created_at'] . ' </span>' .
        '<span class="content">' . $post['content'] . ' </span>' .
        '</p>';
}

// check $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('insert into posts (topic_id, content) values (:topic_id, :content)');
    $stmt->bindValue(':topic_id', $_GET['id']);
    $stmt->bindValue(':content', $_POST['content']);
    $stmt->execute();
    header('Location: index.php?id=' . $_GET['id']);
    exit();
}

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav id="verticalnav">
        <ul>
            <li><a href="../index.php">Topics</a></li>
            <li><a href="#post">post</a></li>
        </ul>
    </nav>
    <div id="topic">
        <h1><?php echo $title; ?></h1>
        <p class="outline"><?php echo $outline; ?></p>
        <?php echo $posts; ?>
    </div>
    <hr>
    <div id="post">
        <form action="" method="post">
            <input type="hidden" name="topic_id" value="<?php echo $_GET['id']; ?>">
            <textarea name="content" id="" cols="30" rows="10"></textarea>
            <input type="submit" value="投稿">
        </form>
    </div>
</body>

</html>