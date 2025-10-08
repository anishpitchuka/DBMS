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
$name    = $_POST['customername'] ?? '';
$street  = $_POST['Address'] ?? '';
$city    = $_POST['city'] ?? '';
$errors  = [];
$successMsg = "";

// Insert into customer if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($street)) {
        $errors[] = "Street is required.";
    }
    if (empty($city)) {
        $errors[] = "City is required.";
    }

    if (empty($errors)) {
        // Check if customer already exists
        $check = $conn->prepare("SELECT customer_name FROM customer WHERE customer_name = ?");
        $check->bind_param("s", $name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "⚠️ Customer already exists!";
        } else {
            // Insert new record
            $stmt = $conn->prepare("INSERT INTO customer (customer_name, customer_street, customer_city) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $street, $city);
            if ($stmt->execute()) {
                $successMsg = "✅ Customer added successfully!";
            }
            $stmt->close();
        }

        $check->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Entry</title>
    <link rel="stylesheet" href="account.css">
    <style>
        .result-container {
            width: 60%;
            margin: 20px auto;
            text-align: center;
        }
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 50px;
            background: #004080;
            color: white;
        }
        .logo img {
            height: 50px;
        }
        .nav-menu table {
            margin: 0 auto;
        }
        .nav-menu td {
            padding: 10px 20px;
        }
        .nav-menu a {
            text-decoration: none;
            color: #004080;
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
        <h2>Customer Information</h2>

        <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
                </div>
            <?php else: ?>
                <div class="success">
                    <p><?php echo $successMsg; ?></p>
                </div>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Street:</strong> <?php echo htmlspecialchars($street); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
