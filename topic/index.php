<?php
// include db_connect.php, function.php
require '../db_connect.php';
require '../function.php';

// check login status
session_start();
if (!isset($_SESSION['user_name'])) {
    $navlink = '<li><a href="../login" class="nav_button">ログイン</a></li>' .
        '<li><a href="../signup" class="nav_button">新規登録</a></li>';
} else {
    $navlink = '<li><a href="../logout" class="nav_button">ログアウト</a></li>';
}

// connect to db
$pdo = db_connect();

// check $_POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // character count validation
    $contentLength = mb_strlen(trim(preg_replace('/\s+/u', ' ', $_POST['content'])));
    if ($contentLength < 1) {
        echo "本文を入力してください。";
        exit();
    }
    $contentLength = mb_strlen($_POST['content']);
    if ($contentLength > 400) {
        echo "本文は400文字以内で入力してください。({$contentLength}文字)";
        exit();
    }
    // sanitize $_POST['content']
    $_POST['content'] = htmlspecialchars($_POST['content']);
    $stmt = $pdo->prepare('insert into posts (topic_id, content) values (:topic_id, :content)');
    $stmt->bindValue(':topic_id', $_GET['id']);
    $stmt->bindValue(':content', $_POST['content']);
    $stmt->execute();
    header('Location: index.php?id=' . $_GET['id']);
    exit();
}

// get topic
$stmt = $pdo->prepare('select * from topics where topic_id = :id');
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$topic = $stmt->fetch(PDO::FETCH_ASSOC);
$title = $topic['title'];
$outline = nl2br($topic['content']);

// load posts
$stmt = $pdo->prepare('select post_id, content, created_at from posts where topic_id = :topic_id');
$stmt->bindValue(':topic_id', $_GET['id']);
$stmt->execute();

// fetch posts
$fetchedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$posts = '';
$postNumber = 1;
foreach ($fetchedPosts as $post) {
    $posts .= '<p class="post">' .
        '<span class="post_number">' . $postNumber . ': </span>' .
        '<span class="date">' . $post['created_at'] . ' </span>' .
        '<span class="content">' . nl2br($post['content']) . ' </span>' .
        '</p>';
    $postNumber++;
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
    <div id="container">
        <nav>
            <ul>
                <?php echo $navlink; ?>
                <li><a href="../index.php" class="nav_button">話題一覧</a></li>
                <li><label for="postcheck" class="nav_button">コメント</a></li>
            </ul>
        </nav>
        <div id="topic">
            <h1><?php echo $title; ?></h1>
            <p class="outline"><?php echo $outline; ?></p>
            <?php echo $posts; ?>
        </div>
        <input type="checkbox" id="postcheck" hidden>
        <div id="post">
            <h2>コメント</h2>
            <form action="" method="post">
                <input type="hidden" name="topic_id" value="<?php echo $_GET['id']; ?>">
                <textarea name="content" id="" cols="30" rows="10" required></textarea>
                <input type="submit" value="投稿">
            </form>
        </div>
    </div>
</body>

</html>