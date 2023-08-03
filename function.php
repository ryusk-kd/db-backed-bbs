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

    // Check if the post belongs to the logged in user
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = :post_id');
    $stmt->bindValue(':post_id', $post_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['username'] !== $_SESSION['user_name']) {
        return false;
    }

    // Delete the post
    $sql = 'DELETE FROM posts WHERE post_id = :post_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':post_id', $post_id);
    $stmt->execute();

    return true;
}
