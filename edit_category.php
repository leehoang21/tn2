<?php
require 'connect.php';
session_start();
if (isset($_GET['category_id'])) {
    $id = $_GET['category_id'];
    $category = $db->query("SELECT * FROM categories WHERE id = $id")->fetch_array(MYSQLI_ASSOC);
}

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    if ($_POST['category_id'] != '') {
        $id = $_POST['category_id'];
        $sql = 'UPDATE categories SET name = ?, updated_at = ? WHERE id = ?';
        $date = date(format: 'Y-m-d H:i:s');
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ssi', $name, $date, $id);
        $stmt->execute();
        header('Location: editor.php');
    } else {
        $sql = 'INSERT INTO categories (name,created_at) VALUES (?, ?)';
        $date = date(format: 'Y-m-d H:i:s');
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ss', $name, $date);
        $stmt->execute();
        header('Location: editor.php');
    }
}
?>

<!DOCTYPE html>
<header class="header">
    <h1>Edit category</h1>
    <button onclick="window.location.href = 'editor.php';">Back</button>
</header>

<body>
    <form action="edit_category.php" method="post">
        <input type="hidden" name="category_id" value=<?php if (isset($category)) {
                                                            echo $category['ID'];
                                                        } else {
                                                            echo '';
                                                        } ?>>
        <label for="title">Name:</label>
        <input type="text" id="name" name="name" required value=<?php if (isset($category)) {
                                                                    echo $category['name'];
                                                                } else {
                                                                    echo '';
                                                                } ?>>
        <br>
        <button type="submit">
            <?php if (isset($category)) {
                echo 'Edit category';
            } else {
                echo 'Create category';
            } ?>
        </button>
    </form>

</body>