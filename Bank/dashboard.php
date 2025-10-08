<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

// Quick counts
$countCustomer = (int)($conn->query('SELECT COUNT(*) c FROM customer')->fetch_assoc()['c'] ?? 0);
$countAccounts = (int)($conn->query('SELECT COUNT(*) c FROM account')->fetch_assoc()['c'] ?? 0);
$countLoans    = (int)($conn->query('SELECT COUNT(*) c FROM loan')->fetch_assoc()['c'] ?? 0);
$countBranches = (int)($conn->query('SELECT COUNT(*) c FROM branch')->fetch_assoc()['c'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – IndianBank</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}
        .kpi{background:#fff;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,.08);padding:18px;text-align:center}
        .kpi .num{font-size:28px;font-weight:bold;margin-top:6px}
        .cards{display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-top:18px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:10px;border-bottom:1px solid #e5e7eb}
        th{background:#1a472a;color:#fff;text-align:left}
        tr:hover{background:#f9fafb}
    </style>
    </head>
<body>
    <header class="site-header">
        <div class="brand">
            <img src="https://via.placeholder.com/40x40?text=IB" alt="logo">
            <h1>IndianBank</h1>
        </div>
        <nav>
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="customers.php">Customers</a>
            <a href="branches.php">Branches</a>
            <a href="accounts.php">Accounts</a>
            <a href="deposits.php">Deposits</a>
            <a href="loans.php">Loans</a>
            <a href="borrowers.php">Borrowers</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main class="container">
        <h2>Dashboard</h2>
        <div class="grid">
            <div class="kpi"><div>Customers</div><div class="num"><?php echo $countCustomer; ?></div></div>
            <div class="kpi"><div>Accounts</div><div class="num"><?php echo $countAccounts; ?></div></div>
            <div class="kpi"><div>Loans</div><div class="num"><?php echo $countLoans; ?></div></div>
            <div class="kpi"><div>Branches</div><div class="num"><?php echo $countBranches; ?></div></div>
        </div>

        <div class="cards">
            <section class="card">
                <h3>Recent Depositors</h3>
                <table>
                    <tr><th>Name</th><th>Account</th></tr>
                    <?php
                    $q = $conn->query('SELECT customer_name, account_number FROM depositer ORDER BY customer_name LIMIT 10');
                    if ($q) {
                        while ($r = $q->fetch_assoc()) {
                            echo '<tr><td>'.htmlspecialchars($r['customer_name']).'</td><td>'.htmlspecialchars($r['account_number'])."</td></tr>";
                        }
                    }
                    ?>
                </table>
            </section>
            <section class="card">
                <h3>Recent Borrowers</h3>
                <table>
                    <tr><th>Name</th><th>Loan No.</th></tr>
                    <?php
                    $q2 = $conn->query('SELECT customer_name, loan_number FROM borrower ORDER BY customer_name LIMIT 10');
                    if ($q2) {
                        while ($r = $q2->fetch_assoc()) {
                            echo '<tr><td>'.htmlspecialchars($r['customer_name']).'</td><td>'.htmlspecialchars($r['loan_number'])."</td></tr>";
                        }
                    }
                    ?>
                </table>
            </section>
        </div>
    </main>
</body>
</html>


