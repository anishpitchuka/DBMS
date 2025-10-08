<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

$errors=[];$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $acc=trim($_POST['account_number']??'');
    $amount=trim($_POST['amount']??'');
    if($acc===''){$errors[]='Account number required';}
    if($amount===''||!is_numeric($amount)){$errors[]='Valid amount required';}
    if(!$errors){
        $stmt=$conn->prepare('UPDATE account SET balance = balance + ? WHERE account_number = ?');
        $stmt->bind_param('ds',$amount,$acc);
        if($stmt->execute() && $stmt->affected_rows>0){$ok='Deposit successful';} else {$errors[]='Account not found';}
        $stmt->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Deposits</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="site-header"><div class="brand"><img src="https://via.placeholder.com/40x40?text=IB"><h1>IndianBank</h1></div><nav><a href="dashboard.php">Dashboard</a><a href="customers.php">Customers</a><a href="branches.php">Branches</a><a href="accounts.php">Accounts</a><a href="deposits.php" class="active">Deposits</a><a href="loans.php">Loans</a><a href="borrowers.php">Borrowers</a><a href="logout.php">Logout</a></nav></header>
<main class="container">
    <section class="card">
        <h3>Make a Deposit</h3>
        <?php if($errors){echo '<div class="muted">'.implode(' | ',array_map('htmlspecialchars',$errors)).'</div>';} elseif($ok!==''){echo '<div class="muted">'.htmlspecialchars($ok).'</div>';} ?>
        <form method="post" class="form-grid">
            <label>Account Number<input type="text" name="account_number" required></label>
            <label>Amount<input type="number" step="0.01" name="amount" required></label>
            <div></div>
            <button type="submit" class="btn">Deposit</button>
        </form>
    </section>
    <section class="card">
        <h3>Accounts Overview</h3>
        <table>
            <tr><th>Account</th><th>Branch</th><th>Balance</th></tr>
            <?php $rs=$conn->query('SELECT account_number,branch_name,balance FROM account ORDER BY account_number');
            while($rs && $row=$rs->fetch_assoc()){echo '<tr><td>'.htmlspecialchars($row['account_number']).'</td><td>'.htmlspecialchars($row['branch_name']).'</td><td>'.htmlspecialchars($row['balance'])."</td></tr>";}
            ?>
        </table>
    </section>
</main>
</body></html>


