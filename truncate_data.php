<?php
$password = "joseph126";
?>
<form method="post" action="">
 <label>Password: </label><input type="password" name="password">
 <input type="submit" value="TRUNCATE RECORDS">
</form>

<?php
if(isset($_POST['password'])){
	if($password == $_POST['password']){
		echo "do truncate here!";
	}else{
		echo "incorrect password!";
	}
}
?>