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
    // Title and Content length validation
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Check if title is empty
    if (mb_strlen(preg_replace('/\s+/u', '', $title)) < 1) {
        echo "タイトルを入力してください。";
        exit();
    }

    // Check if content is empty
    if (mb_strlen(preg_replace('/\s+/u', '', $content)) < 1) {
        echo "本文を入力してください。";
        exit();
    }

    // Check if title exceeds 30 characters
    if (mb_strlen($title) > 30) {
        echo "タイトルは30文字以内で入力してください。";
        exit();
    }

    // Check if content exceeds 2000 characters
    if (mb_strlen($content) > 2000) {
        echo "本文は2000文字以内で入力してください。";
        exit();
    }

    // Sanitize Title and Content
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    // Insert new topic into db
    $post_id = insert_topic($pdo, $title, $content);
    header('Location: ./topic/?id=' . $post_id);
    exit();
} else {
    // Get all topics from db
    $fetchedTopics = get_topics($pdo);
    $topics = '<ul>';
    foreach ($fetchedTopics as $topic) {
        $title = $topic['title'];
        $outline = nl2br($topic['content']);
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