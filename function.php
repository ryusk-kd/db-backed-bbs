<?php
// function.php

/**
 * Deletes a post from the database.
 *
 * @param PDO $pdo The database connection object.
 * @param int $post_id The ID of the post to delete.
 * @return bool True if the post was successfully deleted, false otherwise.
 */
function delete_post(PDO $pdo, int $post_id): bool
{
    // Check if user is logged in
    if (empty($_SESSION['user_name'])) {
        return false;
    }

    try {
        // Check if the post belongs to the logged in user
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = :post_id');
        $stmt->bindValue(':post_id', $post_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // If the post does not belong to the logged in user, return false
        if ($row['username'] !== $_SESSION['user_name']) {
            return false;
        }

        // Delete the post
        $sql = 'DELETE FROM posts WHERE post_id = :post_id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':post_id', $post_id);
        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        // Display error message and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Insert a new topic into the database.
 *
 * @param PDO $pdo The PDO object for the database connection.
 * @param string $title The title of the topic.
 * @param string $content The content of the topic.
 *
 * @return int The ID of the inserted topic.
 */
function insert_topic(PDO $pdo, string $title, string $content): int
{
    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('INSERT INTO topics (title, content) VALUES (:title, :content)');

        // Bind the values to the parameters
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':content', $content);

        // Execute the statement
        $stmt->execute();

        // Get the ID of the inserted topic
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        // Display error message and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Retrieve all topics from the database.
 *
 * @param PDO $pdo The PDO instance for the database connection.
 * @return array The array of topics.
 */
function get_topics(PDO $pdo): array
{
    try {
        // Prepare and execute the SQL query to retrieve topics
        $stmt = $pdo->prepare('SELECT * FROM topics');
        $stmt->execute();

        // Fetch all topics from the result set
        $fetchedTopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $fetchedTopics;
    } catch (PDOException $e) {
        // Handle any PDO exceptions and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Inserts a new post into the database.
 *
 * @param PDO    $pdo       The PDO object for the database connection.
 * @param int    $topic_id  The topic ID of the post.
 * @param string $content   The content of the post.
 * @param string $user_name The username of the post author.
 *
 * @return void
 */
function insert_post(PDO $pdo, int $topic_id, string $content, ?string $user_name): void
{
    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('INSERT INTO posts (topic_id, content, username) VALUES (:topic_id, :content, :username)');

        // Bind the values to the named parameters
        $stmt->bindValue(':topic_id', $topic_id);
        $stmt->bindValue(':content', $content);
        $stmt->bindValue(':username', $user_name);

        // Execute the SQL statement
        $stmt->execute();
    } catch (PDOException $e) {
        // Display the error message and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Retrieves a topic by its ID from the database.
 *
 * @param PDO $pdo the PDO object representing the database connection
 * @param int $topic_id the ID of the topic to retrieve
 * @throws PDOException if there is an error executing the query
 * @return array the topic details as an associative array
 */
function get_topic_by_id(PDO $pdo, int $topic_id): array
{
    try {
        // Prepare the SQL query
        $stmt = $pdo->prepare('SELECT * FROM topics WHERE topic_id = :id');

        // Bind the topic ID parameter
        $stmt->bindValue(':id', $topic_id);

        // Execute the query
        $stmt->execute();

        // Fetch the topic details as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle any errors that occur during execution
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Retrieves the posts of a given topic from the database.
 *
 * @param PDO $pdo The PDO instance.
 * @param int $topic_id The ID of the topic.
 * @return array The array of posts.
 */
function get_posts_of_topic(PDO $pdo, int $topic_id): array
{
    try {
        // Retrieve the posts of the topic
        $stmt = $pdo->prepare('SELECT post_id, content, created_at FROM posts WHERE topic_id = :topic_id');
        $stmt->bindValue(':topic_id', $topic_id);
        $stmt->execute();

        // Fetch all the posts
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Check if a user name exists in the database.
 *
 * @param PDO $pdo The database connection.
 * @param string $username The username to check.
 * @return bool True if the username exists, false otherwise.
 */
function check_user_name_exists(PDO $pdo, string $username): bool
{
    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('SELECT count(*) FROM users WHERE username = :username');

        // Bind the username parameter
        $stmt->bindValue(':username', $username);

        // Execute the SQL statement
        $stmt->execute();

        // Fetch the result column
        $count = $stmt->fetchColumn();

        // Check if the count is greater than zero
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        // Print the error message and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Insert a user into the users table.
 *
 * @param PDO $pdo The PDO instance.
 * @param string $username The username of the user.
 * @param string $hashedPassword The hashed password of the user.
 *
 * @return void
 */
function insert_user(PDO $pdo, string $username, string $hashedPassword): void
{
    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('INSERT INTO users (username, pwhash) VALUES (:username, :hashedPassword)');

        // Bind the values to the parameters
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':hashedPassword', $hashedPassword);

        // Execute the prepared statement
        $stmt->execute();
    } catch (PDOException $e) {
        // Print the error message and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }
}

/**
 * Authenticates a user using the provided PDO object, username, and password.
 *
 * @param PDO $pdo The PDO object used for database connection.
 * @param string $username The username of the user to authenticate.
 * @param string $password The password of the user to authenticate.
 * @return bool Returns true if the user is authenticated, false otherwise.
 */
function authenticateUser(PDO $pdo, string $username, string $password): bool
{
    try {
        // Prepare and execute the SQL query to retrieve the hashed password for the given username
        $stmt = $pdo->prepare('SELECT pwhash FROM users WHERE username = :username');
        $stmt->bindValue(':username', $username);
        $stmt->execute();

        // Fetch the hashed password from the query result
        $hashedPassword = $stmt->fetchColumn();
    } catch (PDOException $e) {
        // Print the error message and exit
        echo 'Error: ' . $e->getMessage();
        die();
    }

    // Check if a hashed password was found
    if ($hashedPassword === false) {
        return false;
    } else {
        // Verify the provided password against the hashed password
        return password_verify($password, $hashedPassword);
    }
}

/**
 * Retrieves an array of posts by username from the database.
 *
 * @param PDO $pdo The PDO object used to connect to the database.
 * @param string $username The username to retrieve posts for.
 * @return array An array of posts matching the given username.
 */
function get_posts_by_username(PDO $pdo, string $username): array
{
    try {
        // Prepare the SQL statement to select posts by username
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE username = :username');

        // Bind the username parameter to the value passed as argument
        $stmt->bindValue(':username', $username);

        // Execute the SQL statement
        $stmt->execute();

        // Fetch all the rows as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // If an exception occurs, display the error message
        echo 'Error: ' . $e->getMessage();
        die();
    }
}
