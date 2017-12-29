<?php

if(!empty($_POST))
{
	$pwd = $_POST['pwd'];
	$rand = $_POST['rand'];
	$result = md5($pwd . $rand);
	echo "$pwd,$rand,$result";
}
?>
<form method='post'>
	<input type="text" name="pwd">
	<input type="text" name="rand">
	<input type="submit" value="È·¶¨">
</form>