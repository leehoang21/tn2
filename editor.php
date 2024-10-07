<?php
require 'connect.php';
session_start();
function getCategory($user)
{
    require 'connect.php';
    $sql = 'SELECT * FROM categories';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $categories = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $categories;
}

function getPost($user)
{
    require 'connect.php';
    $sql = 'SELECT posts.ID,posts.title,posts.content,users.userName as author,posts.created_at,posts.updated_at,categories.name as category,posts.category_id FROM posts left join categories on posts.category_id=categories.ID left join users on posts.author=users.ID';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $posts;
}

if (isset($_SESSION['user'])) {
    $categories = getCategory($_SESSION['user']);
    $posts = getPost($_SESSION['user']);

    //pre posts
    $user = $_SESSION['user'];
    $sql = 'SELECT posts.ID,posts.posts_id,posts.title,posts.content,posts.created_at,categories.name as category,posts.category_id,posts.action FROM pre_posts as posts left join categories on posts.category_id=categories.ID WHERE posts.author  = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $user['ID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $prePosts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    //user
    $user = $_SESSION['user'];
    $sql = 'SELECT * FROM users where role != 1';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$roles = [
    'author',
    'editor'
];

function roleFrom(int $role)
{
    switch ($role) {
        case 0:
            return 'author';
        case 2:
            return  'editor';
        default:
            return 'user';
    }
}


?>

<!DOCTYPE html>
<header class="header">
    <h1>
        <?php
        if (isset($_SESSION['user'])) {
            $user  = $_SESSION['user'];
            if ($user['role'] == 1) {
                echo 'Admin ' . $user['userName'];
            } else if ($user['role'] == 2) {
                echo 'Editor ' . $user['userName'];
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
        $a = 'logout.php';
        $aName = 'Logout';
        $b = 'edit_category.php';
        $bName = 'Create category';
    }
    ?>

    <button onclick="window.location.href = '<?php echo $a; ?>';"><?php echo $aName; ?></button>
    <button onclick="window.location.href = '<?php echo $b; ?>';"><?php echo $bName; ?></button>
    <button onclick="window.location.href = 'manager.php';">Back</button>
</header>

<body>
    <h2>Users</h2>
    <table>
        <tr>
            <th>ID|</th>
            <th>User name |</th>
            <th>role |Action</th>

        </tr>
        <?php
        if (isset($users)) {
            foreach ($users as $user) {

                echo '<tr>';
                echo '<td>' . $user['ID'] . '</td>';
                echo '<td>|' . $user['userName'] . '</td>';
                //select role
                echo '<td>|  ';
                echo '<form action="edit_user_editor.php" method="post">';
                echo '<input type="hidden" name="id" value="' . $user['ID'] . '">';
                echo '<select name="role" id="role">';
                foreach ($roles as $role) {
                    echo '<option value="' . $role . '"';
                    if (roleFrom($user['role']) == $role) {
                        echo ' selected';
                    }
                    echo '>' . $role . '</option>';
                }
                echo '</select>';
                echo '    <input type="submit" value="Update">';
                echo  '</form>';
                echo '</td>';
                //
                echo '<td>|';

                echo '</tr>';
            }
        }

        ?>
    </table>
    <h2>Categories</h2>
    <table>
        <tr>
            <th>Name |</th>
            <th>Created |</th>
            <th>Modified |</th>
            <th>Action |</th>
        </tr>
        <?php
        if (isset($categories)) {
            foreach ($categories as $category) {
                echo '<tr>';
                echo '<td>' . $category['name'] . '</td>';
                echo '<td>|' . $category['created_at'] . '</td>';
                echo '<td>|' . $category['updated_at'] . '</td>';
                echo '<td>|<a href="edit_category.php?id=' . $category['ID'] . '">Edit</a></td>';
                echo '<td>|<a href="delete_category.php?id=' . $category['ID'] . '">Delete</a></td>';
                echo '</tr>';
            }
        }

        ?>
    </table>
    <h2>Posts</h2>
    <table>
        <tr>
            <th>Title |</th>
            <th>Content |</th>
            <th>Author |</th>
            <th>Category | Action |</th>
            <th>Status |</th>
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
                //select category
                echo '<td>|  ';
                echo '<form action="select_category.php" method="post">';
                echo  '<input type="hidden" name="post_id" value=' . $post['ID'] . '>';
                echo '<select name="category" id="category">';
                echo '<option value="">Select category</option>';
                foreach ($categories as $category) {
                    echo '<option value="' . $category['ID'] . '"';
                    if ($post['category_id'] == $category['ID']) {
                        echo ' selected';
                    }
                    echo '>' . $category['name'] . '</option>';
                }
                echo '</select>';
                echo '    <input type="submit" value="Update">';
                echo  '</form>';
                echo '</td>';
                echo '<td>|' . $post['name'] . '</td>';
                echo '<td>|' . $post['created_at'] . '</td>';
                echo '<td>|' . $post['updated_at'] . '</td>';
                echo '</tr>';
            }
        }
        ?>
    </table>
    <h2>the act of waiting for approval from the editor</h2>
    <table>
        <tr>
            <th>ID |</th>
            <th>Title |</th>
            <th>Content |</th>
            <th>Category</th>
            <th>Action</th>
            <th>Created |</th>


        </tr>
        <?php
        if (isset($posts)) {
            foreach ($prePosts as $post) {
                echo '<tr>';
                echo '<td>' . $post['posts_id'] . '</td>';
                echo '<td>' . $post['title'] . '</td>';
                echo '<td>|' . $post['content'] . '</td>';
                echo '<td>|' . $post['category'] . '</td>';
                echo '<td>| ' . $post['action'] . '</td>';
                echo '<td>|' . $post['created_at'] . '</td>';

                echo '<td>|<a href="accept_post.php?post_id=' . $post['ID'] . '">Accept </a></td>';

                echo '</tr>';
            }
        }

        ?>
    </table>

</body>