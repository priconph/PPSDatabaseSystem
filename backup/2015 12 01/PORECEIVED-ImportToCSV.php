<?php
	require_once('ppsdbs-oop.php');
	session_start();
	$username = $_SESSION['username'];
	if(isset($_FILES['file']['name'])) {
		$file = $_FILES['file']['name'];
		$fileCheck = explode(".",$file);
		$fileCheck = end($fileCheck);
		if($fileCheck != 'csv') {
			echo '<center><br><br>ERROR: PLEASE MAKE SURE YOU CONVERTED THE EXCEL FILE TO CSV FORMAT.<br><br>
				How to convert excel file to csv:<br>
				1. Open File <br>
				2. Save File as csv (example: itemlist.csv) then press save.
				<br><br><img src="/images/csv.png" style="width:700px"><br><br>';
			echo '<a href="poReceived.php">BACK</a></center><br>';
		} else {
			if ($_FILES["file"]["error"] > 0) {
				$msg = "Error: " . $_FILES["file"]["error"] . "<br>";
			}
			else
			{
				echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				echo "Type: " . $_FILES["file"]["type"] . "<br>";
				echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br>";
				   
				$a=$_FILES["file"]["tmp_name"]; //FILENAME
								 
				$csv_file = $a; //PASS THE FILE NAME 
				 $ctr = 0;
				if (($getfile = fopen($csv_file, "r")) !== FALSE) { //OPEN FILE 
					$data = fgetcsv($getfile, 1000, ",");
					while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
						$result = $data;
						$str = implode(",", $data);	
						$slice = explode(",", $str);
						/*
						$validateItemCode = strlen($slice[2]);
						if($validateItemCode < 9){
							echo "<br>The itemcode of the next product is less than 9 break operation,<br>";
							echo "<br>Files imported: ".$ctr."<br>";
							echo "<script>alert('Error '+".$validateItemCode.")</script>";
							break 1;
						}
						$ctr++;
						*/
						//SET VALUES OF EACH ITEM IN A ROW
						$category = $slice[0];
						$poNo = $slice[1];
						$itemCode = $slice[2];
						$itemName = $slice[3];
						$orderNo = $slice[4];
						$orderQty = $slice[5];
						$price = str_replace("$","",$slice[6]);
						$amount = $orderQty * $price;
						$dateIssued = $slice[8];
						$shipOut = $slice[9];
						$poBalance = $orderQty-$shipOut;
						$Username = $username;
						
						if($dateIssued != "") {
							$dateIssued = date("Y-m-d", strtotime($dateIssued));
						} else {
							$dateIssued = "";
						}						
						
						if($poNo == ""){
							$poNo = "n/a";
						}
						
						if($price == ""){
							$price = "0";
						}
						
						if($category == ""){
							$category = "n/a";
						}
						
						if($shipOut == ""){
							$shipout = "0";
						}
						
						if($itemCode == ""){
							$itemCode = 0;
						}
						
						if($amount == ""){
							$amount = "0";
						}
						
						if($category != '' && $poNo != '' && $itemCode != '' && $itemName != '' && $orderNo != '' && $orderQty != '' && $price != '' && $amount != '' && $dateIssued != '') {
							$msg = PPSDBS::getInstance()->addPOReceived($category,$poNo, $itemCode, $itemName, $orderNo, $orderQty, $price, $amount, $dateIssued, $shipOut, number_format($poBalance, 4, '.', ''),'',$Username);
								//echo $msg;
							if($msg != 'Successfully added!') {
								//echo "<script>alert('".$msg."'); </script>";
								echo "partcode already exist".$msg."<br>";
							} else {
								echo "insert: ".$category."/".$poNo."/".$itemCode."/".$itemName."/".$orderNo."/".$orderQty."/".$price."/".$amount."/(".$dateIssued.")/[".$shipOut."]/".$poBalance."/".$Username."<br>";
							}
						}else{
							echo "File import failed in ".$orderNo." <br>";
						}
					}
				}
			}
			//echo "<script> window.location.href='./poReceived.php'; </script>";
		}
	}
?>
