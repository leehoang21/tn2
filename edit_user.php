<?php
require 'connect.php';
session_start();
function roleTo($role): int
{
    switch ($role) {
        case 'author':
            return 0;
        case 'admin':
            return 1;
        case 'editor':
            return 2;
        default:
            return 3;
    }
}
if (isset($_POST['id']) && isset($_POST['role'])) {

    $id = $_POST['id'];
    $role = roleTo($_POST['role']);
    $sql = 'UPDATE users SET role = ? WHERE ID = ?';
    $stmt = $db->prepare($sql);
    $stmt->bind_param('ii', $role, $id);
    $stmt->execute();
    header('Location: admin.php');
}
