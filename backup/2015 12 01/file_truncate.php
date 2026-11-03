
<form method="POST" action="#">
	Please enter password to truncate: <input type="password" name="txt_password">
	<input type="submit">
</form>
<?php
	if (isset($_POST['txt_password'])){
		$password = "Truncate Now";
		if($_POST['txt_password'] == $password){
			truncate_pps();
		}else{
			echo "Password is incorrect, Truncate ";
		}
	}
	
function truncate_pps(){
	require_once("ppsdbs-oopDouble.php");
	echo "file truncate complete";
}	
?>
