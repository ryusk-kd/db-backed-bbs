<?php
$jsonString = file_get_contents(dirname(__FILE__) . '/dbsettings.json');
$data = json_decode($jsonString, true);

// DB名
define('DB_DATABASE', $data['database']);
// MySQLのユーザー名
define('DB_USERNAME', $data['username']);
// MySQLのログインパスワード
define('DB_PASSWORD', $data['password']);
// DSN
define('PDO_DSN', 'mysql:host=localhost;charset=utf8mb4;dbname=' . DB_DATABASE);

/**
 * DBの接続設定をしたPDOインスタンスを返却する
 * @return object
 */
function db_connect()
{
    try {
        // PDOインスタンスの作成
        $pdo = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
        // エラー処理方法の設定
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        echo PDO_DSN;
        die();
    }
}
