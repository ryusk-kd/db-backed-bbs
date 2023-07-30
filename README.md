# db-backed-bbs

データベースを使ったBBSです。

## ページ構成

- 話題一覧ページ (index.php)
- 話題ページ (topic/index.php)
- 新規登録ページ (signup/index.php)
- ログインページ (login/index.php)
- ログアウトページ (logout/index.php)

## データベースのデータ構造

users, topics, posts の3テーブルを作成

### users

| Field | Type | Null | Key | Default | Extra |
| ----- | ---- | ---- | --- | ------- | ----- |
| date | timestamp | NO | | current_timestamp() | on update current_timestamp() |
| username | char(24) | NO | PRI | NULL |
| pwhash | char(255) | YES | | NULL | |

### topics

| Field | Type | Null | Key | Default | Extra |
| ---- | ---- | ---- | ---- | ---- | ---- |
| topic_id | int(11) | NO | PRI | NULL | auto_increment |
| title | varchar(100) | NO | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |
| content | text | NO | | NULL | |

### posts

| Field | Type | Null | Key | Default | Extra |
| ---- | ---- | ---- | ---- | ---- | ----- |
| post_id | int(11) | NO | PRI | NULL | auto_increment |
| topic_id | int(11) | NO | MUL | NULL | |
| content | text | NO | | NULL | |
| created_at | timestamp | NO | | current_timestamp() | on update current_timestamp() |
