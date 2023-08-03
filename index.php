<?php
// include db_connect.php, function.php
require 'db_connect.php';
require 'function.php';

// check login status
session_start();
if (!isset($_SESSION['user_name'])) {
    $navlink = '<li><a href="login" class="nav_button">ログイン</a></li>' .
        '<li><a href="signup" class="nav_button">新規登録</a></li>';
} else {
    $navlink = '<li><a href="account" class="nav_button">アカウント</a></li>';
}

// connect to db
$pdo = db_connect();

// check $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // character count validation
    if (mb_strlen(trim(preg_replace('/\s+/u', ' ', $_POST['title']))) < 1) {
        echo "タイトルを入力してください。";
        exit();
    }
    if (mb_strlen(trim(preg_replace('/\s+/u', ' ', $_POST['content']))) < 1) {
        echo "本文を入力してください。";
        exit();
    }
    if (mb_strlen($_POST['title']) > 30) {
        echo "タイトルは30文字以内で入力してください。";
        exit();
    } elseif (mb_strlen($_POST['content']) > 400) {
        echo "本文は400文字以内で入力してください。";
        exit();
    }

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
            '<h2>' . $title . '</h2>' . '<p>' . $outline . '</p>' . '</a>' .
            '<div class="gradientscreen"></div></li>';
    }
    $topics .= '</ul>';
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>話題一覧</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <nav>
            <ul>
                <?php echo $navlink; ?>
                <li><label class="nav_button" for="postcheck">新しい話題</label></li>
            </ul>
        </nav>
        <div id="topics">
            <h1>話題一覧</h1>
            <?php echo $topics; ?>
        </div>
        <input type="checkbox" id="postcheck" hidden>
        <div id="post">
            <h2>新しい話題</h2>
            <form action="" method="post">
                <input type="text" placeholder="タイトルを入力" name="title" required>
                <textarea name="content" placeholder="概要を入力" cols="30" rows="10" required></textarea>
                <input type="submit" value="投稿">
            </form>
        </div>
    </div>
</body>

</html>