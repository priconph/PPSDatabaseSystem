<?php
	echo "<form method='post' action='#'>";
	echo "ENTER A VALUE: <input type = 'number' step='any'  name='txt_Sample'>";
	echo "<input type='submit'>";
	echo "</form>";
	
	if(isset($_POST['txt_Sample'])){
		$val = $_POST['txt_Sample'];
		$val = trim($val);
		
		if(is_float($val)){
			echo $val.": IS VALID<br>";
			echo var_dump(isset($val));
		}else{
			echo $val.": IS NOT VALID<br>";
			echo var_dump(is_float($val));
		}
	}
	
?>
<?php
	$dir    = './';
	$files1 = scandir($dir);
	
	$ctr=0;
	$endctr = count($files1);
	while($ctr < $endctr){
		echo $files1[$ctr]."<br>";
		$ctr++;
	}
	echo "Files 2:<br>";
	
	
	//echo $files1;
	//echo $files2;

?>