<?php
require 'connect.php';
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['role'] == 1) {
        header('Location: admin.php');
    } else if ($user['role'] == 2) {
        header('Location: editor.php');
    } else if ($user['role'] == 0) {
        header('Location: author.php');
    } else {
        header("Location: index.php");
    }
}
