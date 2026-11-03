<?php
require_once('./includes.php');
//require_once('./ppsdbs-oop.php'); //the real thing
require_once('./ppsdbs-oop.php'); //for testing
$dateToday = date("m-d H:i");
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var oTable = $('#TableID HERE').dataTable({
			paginate:false,
			"autoWidth": false
	});
	//START DATE VALIDATION
	$('#txtStartDate').change(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (startDate>endDate){
				$('#txtStartDate').val(endDate);  
			}
	});
	//END DATE VALIDATION
	$('#txtEndDate').change(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (endDate<startDate){
				$('#txtEndDate').val(startDate);
			}
	});
	
	$('#btn_PRExportToExcel').click(function(){ ExportToExcel() });
	
});
function SavePrTransaction(){
	$(document).ready(function(){
		
	});
}

function CheckNumeric()
{
  // Get ASCII value of key that user pressed
   var key = window.event.keyCode;
  // Was key that was pressed a numeric character (0-9)?
   if ( key > 45 && key < 58 )
	 return; // if so, do nothing
   else
	 window.event.returnValue = null; // otherwise, 
								   // discard character
}

function ExportToExcel(){
	window.location.href = "./generateResinPR-ExcelExport.php";
}
</script>
</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">RESIN MONITORING</div>
	<div class="panel-body">
		<div class="panel panel-info">
			<div class="panel-heading">PO RECEIVED / TOTAL KGS. NEEDED</div>
			<div class="panel-body">
				
				<h4>PR Information</h4>
				<table id="tbl_ResinMain2" class="table" border="1">
					<thead>
						<tr>
							<th>R3 Code</th>
							<th>Device Name</th>
							<th>Total PO Balance Qty</th>
							<th>WIP <label style="font-size:12px;">as of <?php echo $dateToday; ?></label></th>
							<th>FGS <label style="font-size:12px;">as of <?php echo $dateToday; ?></label></th>
							<th>For Loading</th>
							<th>Material</th>
							<th>No of Cavity</th>
							<th>Shot Weight</th>
							<th>Kilograms Needed</th>
							<th>Additional Kgs</th>
						</tr>
					</thead>
					<tbody>
						<?php
							//$TotalItemForPR = 0;
							//$TotalAddKgsNeeded = 0;
							$DeviceFGSList = array();
							$ForPR = 0;
							$MaterialNameOld = "";
							$result = PPSDBS::getInstance()->MaterialsForPR();
							 while($row = mysqli_fetch_array($result)){
								if(is_null($row['EOH'])){ 
									$row['EOH'] = 0;
								}
								if(is_null($row['FGSQty'])){ 
									$row['FGSQty'] = 0;
								}
								$forLoading = $row["POBalance"] - ($row['FGSQty'] + $row['EOH']);
								$kgsNeeded = (($row["ShotWgt"] / 1000)*$forLoading);
								$kgsNeeded = number_format($kgsNeeded, 4, '.', '');
								$additionalKgs = (0.8*(180+(34*$row["ShotWgt"])))/1000;
								$additionalKgs = number_format($additionalKgs, 4, '.', '');
								$forPR = ($kgsNeeded+$additionalKgs)-$row["Balance"];
								$forPR = number_format($forPR, 4, '.', '');
								echo '<tr>';
								echo '<td>'.$row["R3Code"].'</td>';
								echo '<td>'.$row["DeviceName"].'</td>';
								echo '<td>'.$row["POBalance"].'</td>';
								echo '<td>'.$row['EOH'].'</td>';
								echo '<td>'.$row['FGSQty'].'</td>';
								echo '<td>'.$forLoading.'</td>';
								$DeviceFGSList[$row["DeviceName"]] = $row['FGSQty'];
								echo '<td>'.$row["Material"].'</td>';
								echo '<td>'.$row["NoOfCav"].'</td>';
								echo '<td>'.number_format($row["ShotWgt"], 4,'.','').'</td>';
								echo '<td>'.$kgsNeeded.'</td>';
								echo '<td>'.$additionalKgs.'</td>';
								echo '</tr>';					
						 }
							
						?>
					</tbody>
				</table>
				
				<?php
					$ForPR = 0;
					$KgsNeededArray = array();
					$AdditionalKgsNeededArray = array();
					$result = PPSDBS::getInstance()->MaterialsForPRByMaterialsbbb();
					while($row = mysqli_fetch_array($result)){
						//dapat ito kapag tapos na yung loop lang mag miminus :) pero dyan lang muna sya pansamantala
						$MaterialInfo = PPSDBS::getInstance()->getPPSWarehouseDataByMaterial($row["Material"]); 
						while ($row2 = mysqli_fetch_array($MaterialInfo)){
							$WarehouseBalance = $row2['Balance'];
						}
						if(is_null($row['EOH'])){ 
							$row['FGSQty'] = 0;
						}
						if(is_null($row['FGSQty'])){ 
							$row['FGSQty'] = 0;
						}
						$forLoading = $row["POBalance"] - ($row['FGSQty'] + $row['EOH']);
						$kgsNeeded = (($row["ShotWgt"] / 1000)*$forLoading);
						$kgsNeeded = number_format($kgsNeeded, 4, '.', '');
						$additionalKgs = (0.8*(180+(34*$row["ShotWgt"])))/1000;
						$additionalKgs = number_format($additionalKgs, 4, '.', '');
						$KgsNeededArray[$row['Material']] = $KgsNeededArray[$row['Material']] + $kgsNeeded;
						$AdditionalKgsNeededArray[$row['Material']] = $AdditionalKgsNeededArray[$row['Material']] + $additionalKgs;
					}
				
				?>
				<table id="tbl_MaterialForPR" class="table" border="1">
					<thead>
						<tr>
							<th>MATERIAL</th>
							<th>TOTAL KGS FOR PR</th>
							<th>ADDT'L KGS NEEDED</th>
							<!--<th>WAREHOUSE BALANCE</th>-->
							<th>FOR PR</th>
							<!--<th>DONE PR</th>-->
						</tr>
					</thead>
					<tbody>
						<?php 
							$forPR = 0;
							$MaterialInfo = PPSDBS::getInstance()->getPPSWarehouseData(); 
							while ($row2 = mysqli_fetch_array($MaterialInfo)){
								if(isset($KgsNeededArray[$row2['MaterialType']])){
									$forPR = ($KgsNeededArray[$row2['MaterialType']] + $AdditionalKgsNeededArray[$row2['MaterialType']]) - $row2['Balance'];
								}else{
									$forPR = 0;
								}
								if($forPR > 0){
									echo "<tr>";
									echo "<td>".$row2["MaterialType"]."</td>";
									echo "<td>".$KgsNeededArray[$row2['MaterialType']]."</td>";
									echo "<td>".$AdditionalKgsNeededArray[$row2['MaterialType']]."</td>";
									echo "<td>".$forPR."</td>";
									echo "</tr>";
								}
							}
							//for non existint materials
							foreach(array_keys($KgsNeededArray) as $paramName){
								//echo $paramName . "<br>";
								$checkExistMaterial = PPSDBS::getInstance()->checkExistMaterial($paramName);
								if($checkExistMaterial == 'Not Existing'){
									$PRThis = $KgsNeededArray[$paramName] + $AdditionalKgsNeededArray[$paramName];
									if($PRThis > 0){
										echo "<tr>";
										echo "<td>".$paramName."</td>";
										echo "<td>".$KgsNeededArray[$paramName]."</td>";
										echo "<td>".$AdditionalKgsNeededArray[$paramName]."</td>";
										echo "<td>".$PRThis."</td>";
										echo "</tr>";
									}
								}
							}
						?>
					</tbody>
				</table>
				
				<button id="btn_PRExportToExcel" class="btn btn-success" style="float:right;">EXPORT TO EXCEL</button>
			</div>
		</div>
		<div class="panel panel-info">
			<div class="panel-heading">WAREHOUSE BALANCE</div>
			<div class="panel-body">
				<!--Get Material Type base on partname/devicename-->
				<!--<label style="margin-right:5%;width:20%;">Warehouse Total Balance:</label><input type="text" id="txt_WarehouseGM" style="width:20%;">--><br><br>
				<table id="tbl_ResinMain" class="table" border="1">
					<thead>
						<tr>
							<th>RESIN TYPE</th>
							<th>BALANCE <label style="font-size:12px;">as of <?php echo $dateToday; ?></label></th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$result = PPSDBS::getInstance()->getPPSWarehouseData();
						while($row = mysqli_fetch_array($result)){
							echo '<tr>';
							echo '<td>'.$row["MaterialType"].'</td>';
							echo '<td>'.$row["Balance"].'</td>';
							echo '</tr>';
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modalNameHere" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">TITLE</h4>
			</div>
			<div class="modal-body">
				<p>CONTENT</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btnYes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>