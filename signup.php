<?php
// signup.php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $regexUserName  = '/^[\w]{5,12}$/';
    $regexPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[\w@$!%*?&]{8,16}$/';


    if (!preg_match($regexUserName, $username)) {
        echo 'Username is invalid';
    } else if (!preg_match($regexPassword, $password)) {
        echo 'Password is invalid';
    } else if ($password != $password2) {
        echo 'Passwords do not match';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hashed_password);
        $stmt->execute();
        echo 'User created';
        header('Location: login.php');
    }
}

?>

<!DOCTYPE html>
<header class="header">
    <h1>Sign up</h1>
</header>

<body class="signup">
    <form action="signup.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="password2">Repeat password:</label>
        <input type="password" id="password2" name="password2" required>
        <br>
        <button type="submit">Sign up</button>

    </form>

</body>