<?php
require 'connect.php';
session_start();
if (isset($_POST['search'])) {

    $key = $_POST['search'];
    $sql = 'SELECT * FROM posts WHERE title LIKE "%' . $key . '%" OR content LIKE "%' . $key . '%"';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $sql = 'SELECT * FROM posts';
    $stmt = $db->prepare($sql);
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
            echo 'Welcome '  . $user['userName'];
        } else {
            echo 'Welcome';
        }
        ?>
    </h1>
    <?php
    if (isset($_SESSION['user'])) {
        $a = 'logout.php';
        $aName = 'Logout';
        $b = 'manager.php';
        $bName = 'Manager';
    } else {
        $a = 'login.php';
        $aName = 'Login';
        $b = 'signup.php';
        $bName = 'Signup';
    }
    ?>

    <button onclick="window.location.href = '<?php echo $a; ?>';"><?php echo $aName; ?></button>
    <button onclick="window.location.href = '<?php echo $b; ?>';"><?php echo $bName; ?></button>

</header>

<body>
    <h2>Posts</h2>
    <form action="posts.php" method="post">
        <input type="text" name="search" placeholder="Search">
        <button type="submit">Search</button>
    </form>
    <table>
        <tr>
            <th>Title |</th>
            <th>Content |</th>
            <th>Author |</th>
            <th>Created |</th>
            <th>Modified |</th>

        </tr>
        <?php
        if (isset($posts)) {
            foreach ($posts as $post) {
                echo '<tr>';
                echo '<td>' . $post['title'] . '</td>';
                echo '<td>|' . $post['content'] . '</td>';
                echo '<td>|' . $post['author'] . '</td>';
                echo '<td>|' . $post['created_at'] . '</td>';
                echo '<td>|' . $post['updated_at'] . '</td>';
                echo '</tr>';
            }
        }

        ?>

</body>