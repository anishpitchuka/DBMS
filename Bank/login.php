<?php
// Database connection
$servername = "localhost";
$dbusername = "root";   // change if needed
$dbpassword = "";       // change if needed
$dbname = "24bcs192";   // your database name

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch submitted data
$username = $_POST['Username'] ?? '';
$password = $_POST['password'] ?? '';
$errors   = [];
$message  = "";

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Validation checks
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errors[] = "Username must be 3–20 characters (letters, numbers, underscore).";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // If no validation errors
    if (empty($errors)) {
        // Check if user already exists
        $check = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $check->bind_param("ss", $username, $password);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $message = "✅ Logged in successfully!";
        } else {
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password); // ⚠️ Use password_hash() in real apps
            if ($stmt->execute()) {
                $message = "✅ New user added successfully!";
            } else {
                $errors[] = "Error adding user: " . $stmt->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Result</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .result-container {
            width: 60%;
            margin: 40px auto;
            text-align: center;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header class="header">
    <div class="logo-section">
        <div class="logo">
            <img src="images/logo.png" alt="logo">
        </div>
        <h1 class="bank-name">IndianBank</h1>
    </div>
</header>

<nav class="nav-menu">
    <table>
        <tr>
            <td><a href="#home">Home</a></td>
            <td><a href="#about">About Us</a></td>
            <td><a href="#login">Login</a></td>
        </tr>
    </table>
</nav>

<div class="result-container">
    <h2>Login Result</h2>

    <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
            </div>
        <?php else: ?>
            <div class="success">
                <p><?= htmlspecialchars($message) ?></p>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

</body>
</html>
