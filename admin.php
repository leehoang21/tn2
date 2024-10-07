<?php
require 'connect.php';
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $sql = 'SELECT * FROM users';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$roles = [
    'author',
    'admin',
    'editor'
];

function roleFrom(int $role)
{
    switch ($role) {
        case 0:
            return 'author';
        case 1:
            return 'admin';
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
                header("Location: index.php");
            } else if ($user['role'] == 0) {
                header("Location: index.php");
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: index.php");
        }
        ?>
    </h1>
    <button onclick="window.location.href = 'index.php';">Back</button>

</header>

<body>
    <h2>Users</h2>
    <table>
        <tr>
            <th>ID|</th>
            <th>User name |</th>
            <th>role |</th>
            <th>Action |</th>
        </tr>
        <?php
        if (isset($users)) {
            foreach ($users as $user) {

                echo '<tr>';
                echo '<td>' . $user['ID'] . '</td>';
                echo '<td>|' . $user['userName'] . '</td>';
                //select role
                echo '<td>|  ';
                echo '<form action="edit_user.php" method="post">';
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
                echo '<a href="delete_user.php?id=' . $user['ID'] . '">Delete</a></td>';

                echo '</tr>';
            }
        }

        ?>
    </table>

</body>