<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

$errors=[];$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $acc=trim($_POST['account_number']??'');
    $branch=trim($_POST['branch_name']??'');
    $bal=trim($_POST['balance']??'');
    $date=trim($_POST['DATE']??'');
    $customer=trim($_POST['customer_name']??'');
    if($acc===''){$errors[]='Account number required';}
    if($branch===''){$errors[]='Branch name required';}
    if($bal===''||!is_numeric($bal)){$errors[]='Valid balance required';}
    if($date===''){$errors[]='Date required';}
    if($customer===''){$errors[]='Customer name required for depositer link';}
    if(!$errors){
        $conn->begin_transaction();
        try{
            $stmt=$conn->prepare('INSERT INTO account(account_number,branch_name,balance,DATE) VALUES(?,?,?,?)');
            $stmt->bind_param('ssds',$acc,$branch,$bal,$date);$stmt->execute();$stmt->close();
            $stmt2=$conn->prepare('INSERT INTO depositer(customer_name,account_number) VALUES(?,?)');
            $stmt2->bind_param('ss',$customer,$acc);$stmt2->execute();$stmt2->close();
            $conn->commit();
            $ok='Account opened and depositer linked';
        }catch(Exception $e){$conn->rollback();$errors[]='Failed to open account';}
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Accounts</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="site-header"><div class="brand"><img src="https://via.placeholder.com/40x40?text=IB"><h1>IndianBank</h1></div><nav><a href="dashboard.php">Dashboard</a><a href="customers.php">Customers</a><a href="branches.php">Branches</a><a href="accounts.php" class="active">Accounts</a><a href="loans.php">Loans</a><a href="borrowers.php">Borrowers</a><a href="logout.php">Logout</a></nav></header>
<main class="container">
    <section class="card">
        <h3>Open Account</h3>
        <?php if($errors){echo '<div class="muted">'.implode(' | ',array_map('htmlspecialchars',$errors)).'</div>';} elseif($ok!==''){echo '<div class="muted">'.htmlspecialchars($ok).'</div>';} ?>
        <form method="post" class="form-grid">
            <label>Customer Name<input type="text" name="customer_name" required></label>
            <label>Account Number<input type="text" name="account_number" required></label>
            <label>Branch Name<input type="text" name="branch_name" required></label>
            <label>Opening Balance<input type="number" step="0.01" name="balance" required></label>
            <label>Date<input type="date" name="DATE" required></label>
            <div></div>
            <button class="btn" type="submit">Open</button>
        </form>
    </section>
    <section class="card">
        <h3>Accounts</h3>
        <table>
            <tr><th>Account</th><th>Branch</th><th>Balance</th><th>Date</th></tr>
            <?php $rs=$conn->query('SELECT account_number,branch_name,balance,DATE FROM account ORDER BY account_number');
            while($rs && $row=$rs->fetch_assoc()){echo '<tr><td>'.htmlspecialchars($row['account_number']).'</td><td>'.htmlspecialchars($row['branch_name']).'</td><td>'.htmlspecialchars($row['balance']).'</td><td>'.htmlspecialchars($row['DATE'])."</td></tr>";}
            ?>
        </table>
    </section>
</main>
</body></html>


