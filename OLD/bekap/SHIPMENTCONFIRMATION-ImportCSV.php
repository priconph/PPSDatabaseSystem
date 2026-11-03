<?php
	require_once('ppsdbs-oop.php');
	session_start();
	$username = $_SESSION['username'];
	if(isset($_FILES['file']['name'])) {
		$file = $_FILES['file']['name'];
		$fileCheck = explode(".",$file);
		if($fileCheck[1] != 'csv') {
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
						$Supplier = $slice[0];
						$YECPO = $slice[1];
						$ItemCode = $slice[2];
						$Name = $slice[3];
						$OrderNo = $slice[4];
						$SchdQty = $slice[5];
						$PurchaseUnitCost = $slice[6];
						$PurchaseOrderAmt = str_replace("$","",$slice[7]);
						$DateIssued = $slice[8];
						$PPDReply = $slice[9];
						$DateNeeded = $slice[10];
						$Remarks = $slice[11];
						$DrawingNo = $slice[12];
						$Username = $username;
						if($DateIssued != "") {
							$DateIssued = date("Y-m-d", strtotime($DateIssued));
						} else {
							$DateIssued = "";
						}	
						if($DateNeeded != "") {
							$DateNeeded = date("Y-m-d", strtotime($DateNeeded));
						} else {
							$DateNeeded = "";
						}						
						
						echo "UPDATE: ".$Supplier."/".$YECPO."/".$ItemCode."/".$Name."/".$OrderNo."/".$SchdQty."/".$PurchaseUnitCost."/".$PurchaseOrderAmt."/".$DateIssued."/".$PPDReply."/".$DateNeeded."/".$Remarks."/".$DrawingNo."/".$Username."<br>";
						PPSDBS::getInstance()->updatePOReceived('one',$Supplier,$YECPO, $ItemCode, $Name, $OrderNo, $SchdQty, $PurchaseUnitCost, $PurchaseOrderAmt, $DateIssued, $PPDReply, $DateNeeded, $Remarks, $DrawingNo, $Username);
						
						
					}
				}
			}
			echo "<script>alert('".$msg."'); window.location.href='./poReceived.php'; </script>";
		}
	}
?>