<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

$errors=[];$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=trim($_POST['customer_name']??'');
    $loan=trim($_POST['loan_number']??'');
    if($name===''){$errors[]='Customer name required';}
    if($loan===''){$errors[]='Loan number required';}
    if(!$errors){
        $stmt=$conn->prepare('INSERT INTO borrower(customer_name,loan_number) VALUES(?,?)');
        $stmt->bind_param('ss',$name,$loan);
        if($stmt->execute()){$ok='Borrower added';}
        $stmt->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Borrowers</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="site-header"><div class="brand"><img src="https://via.placeholder.com/40x40?text=IB"><h1>IndianBank</h1></div><nav><a href="dashboard.php">Dashboard</a><a href="customers.php">Customers</a><a href="branches.php">Branches</a><a href="accounts.php">Accounts</a><a href="loans.php">Loans</a><a href="borrowers.php" class="active">Borrowers</a><a href="logout.php">Logout</a></nav></header>
<main class="container">
    <section class="card">
        <h3>Add Borrower</h3>
        <?php if($errors){echo '<div class="muted">'.implode(' | ',array_map('htmlspecialchars',$errors)).'</div>';} elseif($ok!==''){echo '<div class="muted">'.htmlspecialchars($ok).'</div>';} ?>
        <form method="post" class="form-grid">
            <label>Customer Name<input type="text" name="customer_name" required></label>
            <label>Loan Number<input type="text" name="loan_number" required></label>
            <div></div>
            <button class="btn" type="submit">Add</button>
        </form>
    </section>
    <section class="card">
        <h3>Borrowers</h3>
        <table>
            <tr><th>Customer</th><th>Loan No.</th></tr>
            <?php $rs=$conn->query('SELECT customer_name,loan_number FROM borrower ORDER BY customer_name');
            while($rs && $row=$rs->fetch_assoc()){echo '<tr><td>'.htmlspecialchars($row['customer_name']).'</td><td>'.htmlspecialchars($row['loan_number'])."</td></tr>";}
            ?>
        </table>
    </section>
</main>
</body></html>


