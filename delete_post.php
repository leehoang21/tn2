<?php
require 'connect.php';
session_start();
if (isset($_GET['post_id'])) {
    $id = $_GET['post_id'];
    $author = $_SESSION['user']['ID'];

    $sql = "INSERT INTO pre_posts(action,posts_id,author) VALUES ('delete',?,?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $id, $author);
    $stmt->execute();
}
header('Location: author.php');
