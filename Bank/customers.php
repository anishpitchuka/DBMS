<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

$errors = [];$ok='';
if ($_SERVER['REQUEST_METHOD']==='POST'){
    $name=trim($_POST['customer_name']??'');
    $street=trim($_POST['customer_street']??'');
    $city=trim($_POST['customer_city']??'');
    if($name===''){$errors[]='Name required';}
    if($street===''){$errors[]='Street required';}
    if($city===''){$errors[]='City required';}
    if(!$errors){
        $check=$conn->prepare('SELECT 1 FROM customer WHERE customer_name=?');
        $check->bind_param('s',$name);$check->execute();$check->store_result();
        if($check->num_rows>0){$errors[]='Customer already exists';}
        $check->close();
    }
    if(!$errors){
        $stmt=$conn->prepare('INSERT INTO customer(customer_name,customer_street,customer_city) VALUES(?,?,?)');
        $stmt->bind_param('sss',$name,$street,$city);
        if($stmt->execute()){$ok='Customer added';}
        $stmt->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Customers</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="site-header"><div class="brand"><img src="https://via.placeholder.com/40x40?text=IB"><h1>IndianBank</h1></div><nav><a href="dashboard.php">Dashboard</a><a href="customers.php" class="active">Customers</a><a href="branches.php">Branches</a><a href="accounts.php">Accounts</a><a href="loans.php">Loans</a><a href="borrowers.php">Borrowers</a><a href="logout.php">Logout</a></nav></header>
<main class="container">
    <section class="card">
        <h3>Add Customer</h3>
        <?php if($errors){echo '<div class="muted">'.implode(' | ',array_map('htmlspecialchars',$errors)).'</div>';}
        elseif($ok!==''){echo '<div class="muted">'.htmlspecialchars($ok).'</div>';}
        ?>
        <form method="post" class="form-grid">
            <label>Name<input type="text" name="customer_name" required></label>
            <label>Street<input type="text" name="customer_street" required></label>
            <label>City<input type="text" name="customer_city" required></label>
            <div></div>
            <button type="submit" class="btn">Add</button>
        </form>
    </section>

    <section class="card">
        <h3>Customers</h3>
        <table>
            <tr><th>Name</th><th>Street</th><th>City</th></tr>
            <?php $rs=$conn->query('SELECT customer_name,customer_street,customer_city FROM customer ORDER BY customer_name');
            while($rs && $row=$rs->fetch_assoc()){echo '<tr><td>'.htmlspecialchars($row['customer_name']).'</td><td>'.htmlspecialchars($row['customer_street']).'</td><td>'.htmlspecialchars($row['customer_city'])."</td></tr>";}
            ?>
        </table>
    </section>
</main>
</body></html>


