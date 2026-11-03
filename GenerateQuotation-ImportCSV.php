<?php
	require_once('ppsdbs-oop.php');
	require_once('includes.php');
	session_start();
	$username = $_SESSION['username'];
	$dateToday = date('Y-m-d H-i-s');
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
					echo "<button class='btn btn-primary' id='btn_SaveRecord' style='float:right;'>Save this record</button>";
					$data = fgetcsv($getfile, 1000, ",");
					echo "<table border='1' class='table' id='tbl_Quotation'>";
					echo "<thead>";
					echo "<tr>";
					echo "<th>ControlNo</th>";
					echo "<th>Device</th>";
					echo "<th>MaterialName</th>";
					echo "<th>PartsCode</th>";
					echo "<th>BuyingPrice</th>";
					echo "<th>NoOfCav</th>";
					echo "<th>SprueWgt</th>";
					echo "<th>ProductWgt</th>";
					echo "<th>ShowWgt</th>";
					echo "<th>LaborCostHr</th>";
					echo "<th>CycleTime</th>";
					echo "<th>ProcessCost</th>";
					echo "<th>MaterialCostPerPiece</th>";
					echo "<th>SecondProcessTime</th>";
					echo "<th>SecondProcessCost</th>";
					echo "<th>Profit</th>";
					echo "<th>MoldingProductQuotation</th>";
					echo "<th>Remarks</th>";
					echo "</tr>";
					echo "</thead>";
					echo "<tbody>";
					while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
						$result = $data;
						$str = implode(",", $data);	
						$slice = explode(",", $str);
						if(empty($slice[0]) && empty($slice[1]) && empty($slice[2])){
						
						}else{
							//SET VALUES OF EACH ITEM IN A ROW
							echo "<tr>";
							echo "<td>".$ControlNo = $slice[0]."</td>";
							echo "<td>".$Device = $slice[1]."</td>";
							echo "<td>".$MaterialName = $slice[2]."</td>";
							echo "<td>".$PartsCode = $slice[3]."</td>";
							echo "<td>".$BuyingPrice = $slice[4]."</td>";
							echo "<td>".$NoOfCav = $slice[5]."</td>";
							echo "<td>".$ProductWgt = $slice[6]."</td>";
							echo "<td>".$SprueWgt = $slice[7]."</td>";
							echo "<td>".$ShowWgt = $slice[8]."</td>";
							echo "<td>".$LaborCostHr = $slice[9]."</td>";
							echo "<td>".$CycleTime = $slice[10]."</td>";
							echo "<td>".$ProcessCost = $slice[11]."</td>";
							echo "<td>".$MaterialCostPerPiece = $slice[12]."</td>";
							echo "<td>".$SecondProcessTime = $slice[13]."</td>";
							echo "<td>".$SecondProcessCost = $slice[14]."</td>";
							echo "<td>".$Profit = $slice[15]."</td>";
							echo "<td>".$MoldingProductQuotation = $slice[16]."</td>";
							echo "<td>".$Remarks = $slice[17]."</td>";
							$Username = $username;
							echo "</tr>";
						}
					}
					echo "</tbody>";
					echo "</table>";
					
				}
			}
			
		}
	}
?>
<script>
$(document).ready(function(){
	$('#btn_SaveRecord').click(function(){
		if(confirm("ALL DATA ARE CORRECT? UPLOAD?")){
			var row=0;
			$("#tbl_Quotation tr").each(function(row,tr){
				if(row>0){ //this first row is the label kaya di sya isasama
					var data = {
					"action" : "addQuotationImport",
					"ControlNumber" :	$(tr).find('td:eq(0)').text(),
					"Device" :	$(tr).find('td:eq(1)').text(),
					"ResinCost" : $(tr).find('td:eq(4)').text(),
					"NumOfCavity" : $(tr).find('td:eq(5)').text(),
					"ProductWeight" : $(tr).find('td:eq(6)').text(),
					"SprueWeight" : $(tr).find('td:eq(7)').text(),
					"ShotWeight" : $(tr).find('td:eq(8)').text(),
					"LaborCost" : $(tr).find('td:eq(9)').text(),
					"CycleTime" : $(tr).find('td:eq(10)').text(),
					"ProcessCost" : $(tr).find('td:eq(11)').text(),
					"MaterialCost" : $(tr).find('td:eq(12)').text(),
					"SecondTimeProcess" : $(tr).find('td:eq(13)').text(),
					"SecondProcessCost" : $(tr).find('td:eq(14)').text(),
					"Profit" : $(tr).find('td:eq(15)').text(),
					"MoldingQuotation" : $(tr).find('td:eq(16)').text(),
					"Remarks" : $(tr).find('td:eq(17)').text(),
					"LastUpdate" : "<?php echo $dateToday;?>",
					"Username" : "<?php echo $username;?>"
					}
					
					
					data = $.param(data);
					$.ajax({
						type : "post",
						dataType : "json",
						data : data,
						url : "./ppsdbsHandler",
						success : function(data){
							alert(data["result"]);
						}
					});
					
				}
				row++;
			});
			alert("Data has been uploaded!");
		}else{
			
		}
	});
});
</script>