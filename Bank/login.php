<?php
// Fetch submitted data
$username = $_POST['Username'] ?? '';
$password = $_POST['password'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login Result</title>
    <link rel="stylesheet" href="login.css">
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
        <p><strong>Username:</strong> <?php echo $username; ?></p>
        <p><strong>Password:</strong> <?php echo $password; ?></p>
    </div>
</body>
</html>
