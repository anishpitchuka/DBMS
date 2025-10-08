<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

$errors=[];$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $loan=trim($_POST['loan_number']??'');
    $branch=trim($_POST['branch_name']??'');
    $amount=trim($_POST['amount']??'');
    if($loan===''){$errors[]='Loan number required';}
    if($branch===''){$errors[]='Branch required';}
    if($amount===''||!is_numeric($amount)){$errors[]='Valid amount required';}
    if(!$errors){
        $stmt=$conn->prepare('INSERT INTO loan(loan_number,branch_name,amount) VALUES(?,?,?)');
        $stmt->bind_param('ssd',$loan,$branch,$amount);
        if($stmt->execute()){$ok='Loan added';}
        $stmt->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Loans</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="site-header"><div class="brand"><img src="https://via.placeholder.com/40x40?text=IB"><h1>IndianBank</h1></div><nav><a href="dashboard.php">Dashboard</a><a href="customers.php">Customers</a><a href="branches.php">Branches</a><a href="accounts.php">Accounts</a><a href="loans.php" class="active">Loans</a><a href="borrowers.php">Borrowers</a><a href="logout.php">Logout</a></nav></header>
<main class="container">
    <section class="card">
        <h3>Add Loan</h3>
        <?php if($errors){echo '<div class="muted">'.implode(' | ',array_map('htmlspecialchars',$errors)).'</div>';} elseif($ok!==''){echo '<div class="muted">'.htmlspecialchars($ok).'</div>';} ?>
        <form method="post" class="form-grid">
            <label>Loan Number<input type="text" name="loan_number" required></label>
            <label>Branch Name<input type="text" name="branch_name" required></label>
            <label>Amount<input type="number" step="0.01" name="amount" required></label>
            <div></div>
            <button type="submit" class="btn">Add</button>
        </form>
    </section>
    <section class="card">
        <h3>Loans</h3>
        <table>
            <tr><th>Loan No.</th><th>Branch</th><th>Amount</th></tr>
            <?php $rs=$conn->query('SELECT loan_number,branch_name,amount FROM loan ORDER BY loan_number');
            while($rs && $row=$rs->fetch_assoc()){echo '<tr><td>'.htmlspecialchars($row['loan_number']).'</td><td>'.htmlspecialchars($row['branch_name']).'</td><td>'.htmlspecialchars($row['amount'])."</td></tr>";}
            ?>
        </table>
    </section>
</main>
</body></html>


