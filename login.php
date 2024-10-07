<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            session_start();
            $user = $row;
            unset($user['password']);
            $_SESSION['user'] =   $user;
            if ($user['role'] == 1) {
                header('Location: admin.php');
            } else if ($user['role'] == 2) {
                header('Location: editor.php');
            } else if ($user['role'] == 0) {
                header('Location: author.php');
            } else {
                header("Location: index.php");
            }
        } else {
            echo "Incorrect password!";
        }
    } else {
        echo "Username does not exist!";
    }

    $stmt->close();
    $db->close();
}

?>

<!DOCTYPE html>
<header class="header">
    <h1>Log in</h1>
</header>

<body>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Log in</button>

    </form>
</body>
