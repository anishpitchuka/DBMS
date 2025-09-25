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
$address = $_POST['Address'] ?? '';
$city    = $_POST['city'] ?? '';
$errors  = [];

// Insert into account_opening if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }
    if (empty($address)) {
        $errors[] = "Address is required.";
    }
    if (empty($city)) {
        $errors[] = "City is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO account_opening (customer_name, address, city) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $address, $city);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch all records
$result = $conn->query("SELECT * FROM account_opening");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Account Submitted</title>
    <link rel="stylesheet" href="account.css">
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
        .error { color: red; font-weight: bold; }
        .success { color: green; font-weight: bold; }
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
        <h2>Account Opening Details</h2>

        <?php if ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
                </div>
            <?php else: ?>
                <div class="success">
                    <p>✅ Account successfully created!</p>
                </div>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
                <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($address)); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($city); ?></p>
            <?php endif; ?>
        <?php endif; ?>

        <h3>All Accounts</h3>
        <table class="result-table">
            <thead>
                <tr>
                
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>City</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            
                                <td>" . htmlspecialchars($row['customer_name']) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td>" . htmlspecialchars($row['city']) . "</td>
                              </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php $conn->close(); ?>
