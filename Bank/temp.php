<center><h3>COOKIES EXAMPLE</h3></center>
<hr/>
<?php
$cookie_name = "user";
$cookie_value = "PHP Programmer";
if (isset($_COOKIE["time"])){
$past=$_COOKIE['time'];
$now=time();
$diff=$now-$past;
echo "<center>Welcome

<b>".$_COOKIE[$cookie_name]."</b>!</center> <br />";

if($diff > 20)
echo "<center><a href='sample1.txt'>click

here to download file</a>!</center> <br />";

else{

$left=20-$diff;

echo "<center>Please wait for <b>".$left."</b>
more seconds to begin download!!</center>
<br />";
}

}
else{
setcookie($cookie_name,$cookie_value,time()
+120);
setcookie("time",time(),time()+120);
echo "<center>Cookie has been set !! try next
time!!</center>";
}
?>