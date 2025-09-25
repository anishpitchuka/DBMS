

<?php
$link = mysqli_connect ('localhost', 'root', '', '24bcs192') or die
('Unable to connect the server. ');
$insert = "INSERT INTO customer (customer_name,
customer_street, customer_city) VALUES
('khan','sarafa','patna')";
$results = mysqli_query($link, $insert) or die(mysqli_error($link));
echo "Data inserted successfully!";
?>