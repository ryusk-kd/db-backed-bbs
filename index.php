<?php
// include db_connect.php, function.php
require 'db_connect.php';
require 'function.php';

// check login status
session_start();
if (!isset($_SESSION['user_name'])) {
    $navlink = '<li><a href="login">ログイン</a></li>';
} else {
    $navlink = '<li><a href="logout">ログアウト</a></li>';
}

// connect to db
$pdo = db_connect();

// check $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize title and content in $_POST
    $_POST['title'] = htmlspecialchars($_POST['title']);
    $_POST['content'] = htmlspecialchars($_POST['content']);

    // insert post
    $stmt = $pdo->prepare('insert into topics (title, content) values (:title, :content)');
    $stmt->bindValue(':title', $_POST['title']);
    $stmt->bindValue(':content', $_POST['content']);
    $stmt->execute();

    // get post id and redirect to the topic page
    $post_id = $pdo->lastInsertId();
    header('Location: ./topic/?id=' . $post_id);
    exit();
} else {
    // get topics
    $stmt = $pdo->prepare('select * from topics');
    $stmt->execute();
    $fetchedTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $topics = '<ul>';
    foreach ($fetchedTopics as $topic) {
        $title = $topic['title'];
        $outline = $topic['content'];
        $id = $topic['topic_id'];
        $topics .= '<li><a href="./topic/?id=' . $id . '">' .
            '<h2>' . $title . '</h2>' . '<p>' . $outline . '</p>' . '</a></li>';
    }
    $topics .= '</ul>';
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topics</title>
</head>

<body>
    <nav id="verticalnav">
        <ul>
            <?php echo $navlink; ?>
            <li><a href="#post">スレ立て</a></li>
        </ul>
    </nav>
    <h1>Topics</h1>
    <div id="topics">
        <?php echo $topics; ?>
    </div>
    <div id="post">
        <form action="" method="post">
            <p>
                <input type="text" placeholder="タイトルを入力" name="title">
            </p>
            <p>
                <textarea name="content" placeholder="概要を入力" cols="30" rows="10"></textarea>
            </p>
            <input type="submit" value="投稿">
    </div>
</body>

</html>