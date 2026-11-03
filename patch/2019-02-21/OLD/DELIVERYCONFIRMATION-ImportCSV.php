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
				 
				if (($getfile = fopen($csv_file, "r")) !== FALSE) { //OPEN FILE 
					$data = fgetcsv($getfile, 1000, ",");
					while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
						$result = $data;
						$str = implode(",", $data);	
						$slice = explode(",", $str);
						//SET VALUES OF EACH ITEM IN A ROW
						$dateIssued = $slice[0];
							$dateIssued = str_replace("'", "", $dateIssued);
						$category = $slice[1];
							$category = str_replace("'", "", $category);
						$poNo = $slice[2];
							$poNo = str_replace("'", "", $poNo);
						$orderNo = $slice[3];
							$orderNo = str_replace("'", "", $orderNo);
						$itemName = $slice[4];
							$itemName = str_replace("'", "", $itemName);
						$orderNo = $slice[5];
							$orderNo = str_replace("'", "", $orderNo);
						$orderBalance = str_replace("$","",$slice[6]);
						$targetSO = $slice[7];
						$shipmentDate = $slice[8];
							$shipmentDate = str_replace("'", "", $shipmentDate);
						$remarks = $slice[9];
							$remarks = str_replace("'", "", $remarks);
						$Username = $username;
						
						if($shipmentDate != "") {
							$shipmentDate = date("Y-m-d", strtotime($shipmentDate));
						} else {
							$shipmentDate = "";
						}
						
						if($dateIssued != "") {
							$dateIssued = date("Y-m-d", strtotime($dateIssued));
						} else {
							$dateIssued = "";
						}						
						
						
						if($dateIssued != '' && $orderNo != '' && $orderBalance != '' && $targetSO != '' && $shipmentDate != ''){
							if($targetSO > $orderBalance) {
								//echo "<script>alert('Target for ship out is greater than order balance!');</script>";
								echo "Target for ship out is greater than order balance! <br>";
							}else if($shipmentDate == ""){
								//echo "<script>alert('Import for ".$orderNo." was cancelled, shipment date not specified');</script>";
								echo "Import for ".$orderNo." was cancelled, shipment date not specified<br>";
							}else {
								$msg = PPSDBS::getInstance()->addDeliveryConfirmation($dateIssued,$orderNo,$orderBalance,$targetSO,$shipmentDate,$remarks,$Username);
								if($msg != 'ok') {
									echo "<script>alert('".$msg."');</script>";
								} else {
									echo "insert: ".$dateIssued."/".$orderNo."/".$orderBalance."/".$targetSO."/".$shipmentDate."/".$Username."<br>";
								}
							}
						}
					}
				}
			}
			if($msg != '') {
				//echo "<script>window.location.href='./poReceived.php'; </script>";
			} else {
				//echo "<script>window.location.href='./poReceived.php'; </script>";
			}
		}
	}
?>