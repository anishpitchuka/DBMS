<?php
// Database connection
$servername = "localhost";
$dbusername = "root";  // change if needed
$dbpassword = "";      // change if needed
$dbname = "24bcs192"; // your database name

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch submitted data
$username = $_POST['Username'] ?? '';
$password = $_POST['password'] ?? '';
$errors   = [];

// Insert data into master_login if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation checks
    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        $errors[] = "Username must be 3-20 characters (letters, numbers, underscore).";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // If no errors, check duplicate and insert
    if (empty($errors)) {
        $check = $conn->prepare("SELECT user_id FROM master_login WHERE user_id = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Username already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO master_login (user_id, passwd) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password); // ⚠️ Use password_hash in real apps
            $stmt->execute();
            $stmt->close();
        }
        $check->close();
    }
}

// Fetch all records
$result = $conn->query("SELECT * FROM master_login");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Result</title>
    <link rel="stylesheet" href="login.css">
    <style>
        .result-table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        .result-table th, .result-table td {
            border: 1px solid #333;
            padding: 10px;
            text-align: center;
        }
        .result-table th {
            background-color: #eee;
        }
        .result-container {
            width: 60%;
            margin: 20px auto;
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

    <!-- Navigation Menu using table structure -->
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
        <h2>Login Details</h2>

        <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
                </div>
            <?php else: ?>
                <div class="success">
                    <p>✅ New user added successfully.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

      
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
