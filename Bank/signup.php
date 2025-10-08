<?php
require_once __DIR__ . '/db.php';
session_start();

$errors = [];
$ok = '';

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm  = trim($_POST['confirm']  ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($username === '') { $errors[] = 'Username is required.'; }
    if ($password === '') { $errors[] = 'Password is required.'; }
    if ($password !== $confirm) { $errors[] = 'Passwords do not match.'; }

    if (!$errors) {
        // Check uniqueness
        $chk = $conn->prepare('SELECT 1 FROM users WHERE username = ? LIMIT 1');
        $chk->bind_param('s', $username);
        $chk->execute();
        $chk->store_result();
        if ($chk->num_rows > 0) {
            $errors[] = 'Username already exists.';
        }
        $chk->close();
    }

    if (!$errors) {
        // Plain-text store to match lab schema (can be swapped for hashing later)
        $ins = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $ins->bind_param('ss', $username, $password);
        if ($ins->execute()) {
            header('Location: index.html?msg=signup-ok#login');
            exit;
        } else {
            $errors[] = 'Failed to create user.';
        }
        $ins->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up – IndianBank</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="site-header">
        <div class="brand">
            <img src="https://via.placeholder.com/40x40?text=IB" alt="logo">
            <h1>IndianBank</h1>
        </div>
        <nav>
            <a href="index.html">Home</a>
            <a href="dashboard.php">Dashboard</a>
        </nav>
    </header>

    <main class="container">
        <section class="card">
            <h3>Create Account</h3>
            <?php if ($errors) { echo '<div class="muted">'.implode(' | ', array_map('htmlspecialchars', $errors)).'</div>'; } ?>
            <form method="post" class="form-grid">
                <label>Username
                    <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </label>
                <label>Password
                    <input type="password" name="password" required>
                </label>
                <label>Confirm Password
                    <input type="password" name="confirm" required>
                </label>
                <div></div>
                <button type="submit" class="btn">Sign Up</button>
            </form>
            <p class="muted" style="margin-top:10px;">Already have an account? <a href="index.html#login">Login</a></p>
        </section>
    </main>
</body>
</html>


