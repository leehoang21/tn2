<?php
require 'connect.php';
session_start();


if (isset($_SESSION['user'])) {

    $user = $_SESSION['user'];
    $sql =  'SELECT posts.ID,posts.title,posts.content,posts.created_at,categories.name as category,posts.category_id,posts.updated_at FROM posts left join categories on posts.category_id=categories.ID WHERE posts.author  = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $user['ID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

?>

<!DOCTYPE html>
<header class="header">
    <h1>
        <?php

        if (isset($_SESSION['user'])) {
            $user  = $_SESSION['user'];
            if ($user['role'] == 1) {
                header("Location: index.php");
            } else if ($user['role'] == 2) {
                header("Location: index.php");
            } else if ($user['role'] == 0) {
                echo 'Author ' . $user['userName'];
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: index.php");
        }
        ?>
    </h1>
    <?php
    if (isset($_SESSION['user'])) {
        $b = 'edit_post.php';
        $bName = 'Create post';
    }
    ?>
    <button onclick="window.location.href = '<?php echo $b; ?>';"><?php echo $bName; ?></button>
    <button onclick="window.location.href = 'index.php';">Back</button>
</header>

<body>
    <h2>Posts</h2>
    <table>
        <tr>
            <th>ID |</th>
            <th>Title |</th>
            <th>Content |</th>
            <th>Category</th>
            <th>Status</th>
            <th>Created |</th>
            <th>Modified |</th>
            <th>Action |</th>
        </tr>
        <?php
        if (isset($posts)) {
            foreach ($posts as $post) {
                echo '<tr>';
                echo '<td>' . $post['ID'] . '</td>';
                echo '<td>' . $post['title'] . '</td>';
                echo '<td>|' . $post['content'] . '</td>';
                echo '<td>|' . $post['category'] . '</td>';
                echo '<td>| accept</td>';
                echo '<td>|' . $post['created_at'] . '</td>';
                echo '<td>|' . $post['updated_at'] . '</td>';
                echo '<td>|<a href="edit_post.php?post_id=' . $post['ID'] . '">Edit</a></td>';
                echo '<td>|<a href="delete_post.php?post_id=' . $post['ID'] . '">Delete</a></td>';
                echo '</tr>';
            }
        }

        ?>
    </table>


</body>