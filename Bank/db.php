<?php
// Reusable MySQL connection for database 24bcs192
// Update credentials if your XAMPP setup differs
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = '24bcs192';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
?>


