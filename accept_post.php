<?php
require 'connect.php';

if (isset($_GET['post_id'])) {
    $id = $_GET['post_id'];
    $sql = 'Select * from pre_posts where ID = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($posts as $post) {
        $sql = "DELETE FROM pre_posts WHERE ID = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        switch ($post['action']) {

            case 'delete':

                $sql = "DELETE FROM posts WHERE ID = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("i", $post['posts_id']);
                $stmt->execute();
                break;
            case 'edit':
                $sql = "UPDATE posts SET title = ?, content = ? , updated_at = ? WHERE ID = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("sssi", $post['title'], $post['content'], $post['created_at'], $post['posts_id']);
                $stmt->execute();

                break;
            case 'create':
                $sql = "INSERT INTO posts(title,content,created_at,author) VALUES (?,?,?,?)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("sssi", $post['title'], $post['content'], $post['created_at'], $post['author']);
                $stmt->execute();


                break;
            default:
                # code...
                break;
        }
    }
    $db->close();
    header('Location: editor.php');
}
