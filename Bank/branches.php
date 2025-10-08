<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
ensureLoggedIn();

$errors=[];$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=trim($_POST['branch_name']??'');
    $city=trim($_POST['branch_city']??'');
    $assets=trim($_POST['assets']??'');
    if($name===''){$errors[]='Branch name required';}
    if($city===''){$errors[]='City required';}
    if($assets!==''&&!is_numeric($assets)){$errors[]='Assets must be number';}
    if(!$errors){
        $stmt=$conn->prepare('INSERT INTO branch(branch_name,branch_city,assets) VALUES(?,?,?)');
        $assetsVal=$assets===''?0:$assets;
        $stmt->bind_param('ssd',$name,$city,$assetsVal);
        if($stmt->execute()){$ok='Branch added';}
        $stmt->close();
    }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Branches</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header class="site-header"><div class="brand"><img src="https://via.placeholder.com/40x40?text=IB"><h1>IndianBank</h1></div><nav><a href="dashboard.php">Dashboard</a><a href="customers.php">Customers</a><a href="branches.php" class="active">Branches</a><a href="accounts.php">Accounts</a><a href="loans.php">Loans</a><a href="borrowers.php">Borrowers</a><a href="logout.php">Logout</a></nav></header>
<main class="container">
    <section class="card">
        <h3>Add Branch</h3>
        <?php if($errors){echo '<div class="muted">'.implode(' | ',array_map('htmlspecialchars',$errors)).'</div>';} elseif($ok!==''){echo '<div class="muted">'.htmlspecialchars($ok).'</div>';} ?>
        <form method="post" class="form-grid">
            <label>Name<input type="text" name="branch_name" required></label>
            <label>City<input type="text" name="branch_city" required></label>
            <label>Assets<input type="number" step="0.01" name="assets"></label>
            <div></div>
            <button type="submit" class="btn">Add</button>
        </form>
    </section>
    <section class="card">
        <h3>Branches</h3>
        <table>
            <tr><th>Name</th><th>City</th><th>Assets</th></tr>
            <?php $rs=$conn->query('SELECT branch_name,branch_city,assets FROM branch ORDER BY branch_name');
            while($rs && $row=$rs->fetch_assoc()){echo '<tr><td>'.htmlspecialchars($row['branch_name']).'</td><td>'.htmlspecialchars($row['branch_city']).'</td><td>'.htmlspecialchars($row['assets'])."</td></tr>";}
            ?>
        </table>
    </section>
</main>
</body></html>


