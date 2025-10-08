<?php
require_once __DIR__ . '/db.php';
session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: index.html?msg=missing#login');
    exit;
}

$stmt = $conn->prepare('SELECT username, password FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // For simplicity, plaintext passwords per typical lab setup
    if ($row['password'] === $password) {
        $_SESSION['username'] = $row['username'];
        header('Location: dashboard.php');
        exit;
    }
}

// Failed login
header('Location: index.html?msg=failed#login');
exit;
?>


