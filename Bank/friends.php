<?php

session_start();
//find out whether user has logged in with valid password
if(isset($_SESSION['authuser']))
{
if($_SESSION['authuser']!=1) {

echo 'Sorry!! you do not have permission to view this page!';
exit(); }

}
else{

echo 'You need to login to view this content!<br/>';
echo 'Go to login page!';
exit();

}

?>

<b>Hello PHP Programmer!!</b><br/>
<b>Your friends are:</b>
<table border=2>
<tr><td>Suresh</td></tr>
<tr><td>Anil</td></tr>
<tr><td>Aditya</td></tr>
<tr><td>Sanjay</td></tr>
<tr><td>Shaan</td></tr>
</table><br/><br/>

<b>Thanks to visit your friends!!</b>
</body>