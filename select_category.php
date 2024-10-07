<?php
require 'connect.php';
session_start();

if (isset($_POST['category']) && isset($_POST['post_id'])) {

    $categoryId = $_POST['category'];
    $id = $_POST['post_id'];
    if ($categoryId == '') {
        $categoryId = null;
    }
    $sql = 'UPDATE posts SET category_id = ? WHERE ID = ?';
    $stmt = $db->prepare($sql);

    $stmt->bind_param('ii', $categoryId, $id);
    $stmt->execute();
    header('Location: editor.php');
}
