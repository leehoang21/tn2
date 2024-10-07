<!-- connect database -->
<?php
$host = 'localhost';
$port = '3306';
$user = 'root';
$pass = '';
$database = 'b1';

$db = new mysqli($host, $user, $pass, $database, $port);

?>