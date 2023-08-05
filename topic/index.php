<?php
// Include db_connect.php and function.php
require '../db_connect.php';
require '../function.php';

// Check login status
session_start();
$navlink = '';
if (!isset($_SESSION['user_name'])) {
    $navlink .= '<li><a href="../login" class="nav_button">ログイン</a></li>';
    $navlink .= '<li><a href="../signup" class="nav_button">新規登録</a></li>';
    $username = null;
} else {
    $navlink .= '<li><a href="../account" class="nav_button">アカウント</a></li>';
    $username = $_SESSION['user_name'];
}

// Connect to the database
$pdo = db_connect();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = trim($_POST['content']);

    // Validate content length
    $contentLength = mb_strlen(preg_replace('/\s+/u', '', $content));
    if ($contentLength < 1) {
        echo "本文を入力してください。";
        exit();
    }

    $contentLength = mb_strlen($content);
    if ($contentLength > 2000) {
        echo "本文は2000文字以内で入力してください。({$contentLength}文字)";
        exit();
    }

    // Sanitize the content
    $sanitizedContent = htmlspecialchars($content);

    // Insert the post into the database
    insert_post($pdo, $_GET['id'], $sanitizedContent, $username);

    // Redirect to the index page
    header('Location: index.php?id=' . $_GET['id']);
    exit();
}

// Retrieve the title and outline of the topic
$topic = get_topic_by_id($pdo, $_GET['id']);

// Retrieves the posts of a given topic from the database.
$fetchedPosts = get_posts_of_topic($pdo, $_GET['id']);

// Initialize a variable to store the generated HTML
$html = '';

// Initialize a counter for the post number
$postNumber = 1;

// Loop through each fetched post and generate the HTML
foreach ($fetchedPosts as $post) {
    $html .= '<div>' .
        '<p class="post">' .
        '<span class="post_number">' . $postNumber . ': </span>' .
        '<span class="date">' . $post['created_at'] . ' </span>' .
        '<span class="content">' . nl2br($post['content']) . ' </span>' .
        '</p>' .
        '<div class="gradientscreen"></div>' .
        '</div>';

    // Increment the post number
    $postNumber++;
}

// Assign the generated HTML to the variable
$posts = $html;

?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $topic['title']; ?>
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
            <h1><?php echo $topic['title']; ?></h1>
            <p class="outline"><?php echo nl2br($topic['content']); ?></p>
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