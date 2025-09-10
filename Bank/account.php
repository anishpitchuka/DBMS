<?php
// Fetch submitted data
$name = $_POST['customername'] ?? '';
$address = $_POST['Address'] ?? '';
$city = $_POST['city'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Submitted</title>
    <link rel="stylesheet" href="account.css">
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
        <p><strong>Name:</strong> <?php echo $name; ?></p>
        <p><strong>Address:</strong> <?php echo nl2br($address); ?></p>
        <p><strong>City:</strong> <?php echo $city; ?></p>
    </div>
</body>
</html>
