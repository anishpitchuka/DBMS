
<?php
$link = mysqli_connect ('localhost', 'root', '','24bcs192') or die ('Unable to connect the server.');
$row="CREATE TABLE customer( customer_name VARCHAR(15) NOT NULL, customer_street VARCHAR(15), customer_city VARCHAR(15), PRIMARY KEY(customer_name))";
$results = mysqli_query($link, $row);
if($results == FALSE)
echo mysqli_error($link);
else
echo "Customer table created.";

$row = "CREATE TABLE depositor( customer_name VARCHAR(15), account_number INTEGER, PRIMARY KEY(customer_name, account_number), FOREIGN KEY(customer_name) REFERENCES customer(customer_name), FOREIGN KEY(account_number) REFERENCES account(account_number))";
$results = mysqli_query($link, $row);
if($results == FALSE)
echo mysqli_error($link);
else
echo "Depositor table created.";
?>
