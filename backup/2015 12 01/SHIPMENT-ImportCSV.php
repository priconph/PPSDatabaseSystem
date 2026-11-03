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
				//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				//echo "Type: " . $_FILES["file"]["type"] . "<br>";
				//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br>";
				   
				$a=$_FILES["file"]["tmp_name"]; //FILENAME
								 
				$csv_file = $a; //PASS THE FILE NAME 
				 
				if (($getfile = fopen($csv_file, "r")) !== FALSE) { //OPEN FILE 
					$data = fgetcsv($getfile, 1000, ",");
					while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
						$result = $data;
						$str = implode(",", $data);	
						$slice = explode(",", $str);
						//SET VALUES OF EACH ITEM IN A ROW
						$orderNo = $slice[2];
						//$shipOut = $slice[6];
						//$unitPrice = $slice[7];
						//$amount = $slice[8];
						$remarks = $slice[6];
						if($orderNo == '') {
						} else {
							//echo "INSERT: ".$controlNumber."/".$dateIssued."/".$orderNo."/".$shipOut."/".$unitPrice."/".$amount."/".$remarks."/".$username."<br>";
							//echo "<script>alert('".$orderNo."'); </script>";
							$query = PPSDBS::getInstance()->exportShipmentRecord('orderno','','',$orderNo);
							if($query->num_rows == 0){
							} else {
								while($row = mysqli_fetch_array($query)) {
									$actual = $row['ActualSO'];
									$price = $row['Price'];
									$amount = number_format($actual*$price,4);
									echo "
									$('#tbl_Shipment tbody').append(
										'<tr>
											  <td>".$row['ItemCode']."</td>
											  <td>".$row['ItemName']."</td>
											  <td>".$orderNo."</td>
											  <td>".$row['ActualSO']."</td>
											  <td>".$row['Price']."</td>
											  <td>".$amount."</td>
											  <td>".$remarks."</td>
											  <td><a href='javascript:void(0);' class='remCF'>Remove</a></td></tr>');
									";
									
									//PPSDBS::getInstance()->addNewRecordShipment($controlNumber,$dateIssued,$orderNo,$row['ActualSO'],$row['Price'],number_format($row['ActualSO']*$row['Price'],4),$remarks,$username);
								}
							}
						}
					}
				}
			}
			
		}
	}
?>