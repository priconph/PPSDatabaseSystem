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
			echo '<a href="dieset.php">BACK</a></center><br>';
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
					//$ctr = 0;
					while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
						$result = $data;
						$str = implode(",", $data);	
						$slice = explode(",", $str);
						/*
						$validateDieset = $slice[33];
						if($validateDieset != "Die-set Import" && $ctr == '0'){
							//echo "<script>alert('DONE')</script>";
							echo "<br>Warning you may be uploading an incorrect file<br>";
							break 1;
						}*/
						//$ctr++;
						//SET VALUES OF EACH ITEM IN A ROW
						$date = $slice[0];
						$status = $slice[1];
						$DateModified = $slice[2];
						$DateReturned = $slice[3];
						$YECControlNum = $slice[4];
						$R3Code = $slice[5];
						$EXPCode = $slice[6];
						$DeviceName = $slice[7];
						//$DrawingName = $slice[6];
						$DrawingNum = $slice[8];
						$Rev = $slice[9];
						$MoldClass = $slice[10];
						$DieNo = $slice[11];
						$NumOfCavity = $slice[12];
						$CavNum = $slice[13];
						$ChangingPart = $slice[14];
						$RackNum = $slice[15];
						$DiesetSize = $slice[16];
						$DiesetWeight = $slice[17];
						$UnitWeight = $slice[18];
						$SprueWeight = $slice[19];
						$ShotWeight = $slice[20];
						$CycleTimePerShot = $slice[21];
						$FixedAssetNum = $slice[22];
						$DateManufactured = $slice[23];
						$MoldMake = $slice[24];
						$ToolLife = $slice[25];
						$ToolShot = $slice[26];
						$ToolLifePMI = $slice[27];
						$MaintenanceCycle = $slice[28];
						$InjectionMachine = $slice[29];
						$Material = $slice[30];
						$ToneColor = $slice[31];
						$Remarks = $slice[32];
						$Username = $username;
						$date = date("Y-m-d", strtotime($date));
						if($DateManufactured != "") {
							$DateManufactured = date("Y-m-d", strtotime($DateManufactured));
						} else {
							$DateManufactured = "";
						}	
						if($DateModified != "") {
							$DateModified = date("Y-m-d", strtotime($DateModified));
						} else {
							$DateModified = "";
						}	
						if($DateReturned != "") {
							$DateReturned = date("Y-m-d", strtotime($DateReturned));
						} else {
							$DateReturned = "";
						}
						
						if($status != "" || $R3Code != "" || $DeviceName != "") {
							//check kung existing na sya sa tbl_dieset kapag meron na dapat sa tbl_dieset_history na sya ilalagay. tapos kapag new palang, sa tbl_dieset dapat
							$msg = PPSDBS::getInstance()->addDieset($date,$status,$DateModified,$DateReturned,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$RackNum,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username);
							if($msg == "Successfully added!") {			
								//echo "<script>alert('".$msg."');</script>";
								echo "insert: ".$date."/".$status."/".$YECControlNum."/".$R3Code."/".$EXPCode."/".$DeviceName."/".$DrawingNum."/".$Rev."/".$MoldClass."/".$DieNo."/".$NumOfCavity."/".$CavNum."/".$ChangingPart."/".$DiesetSize."/".$DiesetWeight."/".$UnitWeight."/".$SprueWeight."/".$ShotWeight."/".$CycleTimePerShot."/".$FixedAssetNum."/".$DateManufactured."/".$MoldMake."/".$ToolLife."/". $ToolShot."/".$ToolLifePMI."/".$MaintenanceCycle."/".$InjectionMachine."/".$Material."/".$ToneColor."/".$Remarks."/".$Username."<br>";
							} else {
								if($status != 1) {
									$msg = PPSDBS::getInstance()->updateDiesetTransferMaterial($date,$status,$DateModified,$DateReturned,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$RackNum,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username);
									if($msg != "Successfully updated!") {
										echo "<script>alert('R3Code [".$msg."] already exists in the database!'); </script>";
										echo "insert: ".$date."/".$status."/".$YECControlNum."/".$R3Code."/".$EXPCode."/".$DeviceName."/".$DrawingNum."/".$Rev."/".$MoldClass."/".$DieNo."/".$NumOfCavity."/".$CavNum."/".$ChangingPart."/".$DiesetSize."/".$DiesetWeight."/".$UnitWeight."/".$SprueWeight."/".$ShotWeight."/".$CycleTimePerShot."/".$FixedAssetNum."/".$DateManufactured."/".$MoldMake."/".$ToolLife."/". $ToolShot."/".$ToolLifePMI."/".$MaintenanceCycle."/".$InjectionMachine."/".$Material."/".$ToneColor."/".$Remarks."/".$Username."<br>";
									}
								} else {
									echo "<script>alert('R3Code [".$msg."] already exists in the database!'); </script>";
								}
								
							}

						}
					}
				}
			}
			echo "<script>window.location.href='./dieset.php'; </script>";
		}
	}
?>