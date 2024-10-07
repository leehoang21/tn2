<?php
require 'connect.php';
session_start();
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
    $post = $db->query("SELECT * FROM posts WHERE id = $post_id")->fetch_array(MYSQLI_ASSOC);
}

if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['post_id'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $created_at = date(format: 'Y-m-d H:i:s');
    $author = $_SESSION['user']['ID'];

    $sql = 'INSERT INTO pre_posts (title, content, created_at, author,posts_id ,action) VALUES (?, ?, ?, ?, ?, ?)';

    if ($_POST['post_id'] != '') {
        $action = 'edit';
        $posts_id = $_POST['post_id'];
    } else {
        $action = 'create';
        $posts_id = null;
    }
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ssssis', $title, $content, $created_at, $author, $posts_id, $action);
    $stmt->execute();
    header('Location: author.php');
}
?>

<!DOCTYPE html>
<header class="header">
    <h1>Edit post</h1>
</header>

<body>
    <form action="edit_post.php" method="post">
        <input type="hidden" name="post_id" value=<?php if (isset($post)) {
                                                        echo $post['ID'];
                                                    } else {
                                                        echo '';
                                                    } ?>>
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required value=<?php if (isset($post)) {
                                                                        echo $post['title'];
                                                                    } else {
                                                                        echo '';
                                                                    } ?>>
        <br>
        <label for="content">Content:</label>
        <textarea id="content" name="content" required><?php if (isset($post)) {
                                                            echo $post['content'];
                                                        } else {
                                                            echo '';
                                                        } ?></textarea>
        <br>
        <button type="submit">
            <?php if (isset($post)) {
                echo 'Edit post';
            } else {
                echo 'Create post';
            } ?>
        </button>
    </form>

</body>