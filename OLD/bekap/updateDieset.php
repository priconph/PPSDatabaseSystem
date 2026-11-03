<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$YECMoldCtrlNo = $_GET['yecno'];
session_start();
$username = $_SESSION["username"];
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/updatedieset_style_sheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#btn_Cancel').click(function() {
		window.location.href = "./dieset.php";
	});
	$('#txt_NumOfCavity').change(function(){
		$('#txt_NumOfCavityGQ').val($('#txt_NumOfCavity').val()); //update Generate Quotation, number of cavity textbox
	});
	$('#txt_UnitWeight').change(function(){
		$('#txt_ProductWeightGQ').val($('#txt_UnitWeight').val()); //update Generate Quotation, product weight textbox
	});
	$('#txt_SprueWeight').change(function(){
		$('#txt_SprueWeightGQ').val($('#txt_UnitWeight').val()); //update Generate Quotation, Sprue weight textbox
	});
	$('#txt_1ShotWeight').change(function(){
		$('#txt_ShotWeightGQ').val($('#txt_1ShotWeight').val()); //update Generate Quotation, 1shot weight textbox
	});
	$('#txt_CycleTimePerShot').change(function(){
		$('#txt_CycleTimeGQ').val($('#txt_CycleTimePerShot').val()); //update Generate Quotation, cycletime per shot weight textbox
	});
	$('#txt_ResinCostGQ').change(function(){
		var ResinCost = $('#txt_ResinCostGQ').val();
		if (isNaN(ResinCost)){
			$('#txt_ResinCostGQ').val("0");
			return;
		}
		var TotalResinCost = parseFloat(ResinCost) + (parseFloat(ResinCost) * parseFloat(.10)); //Resin cost + 10 percent
		TotalResinCost = TotalResinCost.toFixed(4);
		$('#txt_ResinCostGQ').val(TotalResinCost); //add 10% to the current price of resin cost
	});
	
	$('#btn_UpdateDieset').click(function(){
		var data = {
			"action" : "updateDieset",
			"Date" : $('#txt_Date').val(),
			"Status" : $('#cmb_Status').val(),
			"YECControlNum" : $('#txt_YECControlNum').val(),
			"R3Code" : $('#txt_R3Code').val(),
			"ExpCode" : $('#txt_EXPCode').val(),
			"DeviceName" : $('#txt_DeviceName').val(),
			"DrawingNum" : $('#txt_DrawingNum').val(),
			"Rev" : $('#txt_Rev').val(),
			"MoldClass" : $('#cmb_MoldClass').val(),
			"DieNo" : $('#txt_DieNo').val(),
			"NumOfCavity" : $('#txt_NumOfCavity').val(),
			"CavNum" : $('#cmb_CavNum').val(),
			"ChangingPart" : $('#cmb_ChangingPart').val(),
			"DiesetSize" : $('#txt_DiesetSize').val(),
			"DiesetWeight" : $('#txt_DiesetWeight').val(),
			"UnitWeight" : $('#txt_UnitWeight').val(),
			"SprueWeight" : $('#txt_SprueWeight').val(),
			"ShotWeight" : $('#txt_1ShotWeight').val(),
			"CycleTimePerShot" : $('#txt_CycleTimePerShot').val(),
			"FixedAssetNum" : $('#txt_FixedAssetNo').val(),
			"MoldMake" : $('#txt_MoldMak').val(),
			"ToolLife" : $('#txt_ToolLife').val(),
			"ToolShot" : $('#txt_ToolShot').val(),
			"ToolLifePMI" : $('#txt_ToolLifePMI').val(),
			"MaintenanceCycle" : $('#txt_MaintenanceCycle').val(),
			"InjectionMachine" : $('#txt_InjectionMachine').val(),
			"Material" : $('#txt_Material').val(),
			"ToneColor" : $('#txt_ToneColor').val(),
			"Remarks" : $('#txt_Remarks').val()
		}
		//for checking
		//alert(data['action']+data['Date']+data['YECControlNum']+data['Status']+data['R3Code']+data['ExpCode']+data['DeviceName']+data['DrawingNum']+data['Rev']+data['DieNo']+data['MoldClass']+data['DieNo']+data['NumOfCavity']+data['CavNum']+data['ChangingPart']+data['DiesetSize']+data['DiesetWeight']+data['UnitWeight']+data['SprueWeight']+data['1ShotWeight']+data['CycleTimePerShot']+data['FixedAssetNum']+data['MoldMake']+data['ToolLife']+data['ToolShot']+data['ToolLifePMI']+data['MaintenanceCycle']+data['InjectionMachine']+data['Material']+data['ToneColor']+data['Remarks']);
		data = $.param(data);
		$.ajax({
			type : "POST",
			dataType : "json",
			url : "ppsdbsHandler.php",
			data : data,
			success : function(data) {
				alert("Successfully updated!");
			}
		});
	
	});
});
	function goBack() {
		window.history.back()
	}
</script>
</head>
<body>
	<div class="panel panel-primary" style="width:100%;margin:auto;">
	  <div class="panel-heading">Update Die-set Transfer and Material</div>
	  <div class="panel-body">
		<div class="panel panel-primary">
			<div class="panel-body">
				<?php
				$result = PPSDBS::getInstance()->getDiesetTransferMaterial($YECMoldCtrlNo);
				while($row = mysqli_fetch_array($result)) {
					$date = $row['Date'];
					$DiesetStatus = $row['DiesetStatus'];
					$R3Code = $row['R3Code'];
					$EXPCode = $row['EXPCode'];
					$DeviceName = $row['DeviceName'];
					$DrawingNo = $row['DrawingNo'];
					$Rev = $row['Rev'];
					$MoldClass = $row['MoldClass'];
					$DieNo = $row['DieNo'];
					$NoOfCav = $row['NoOfCav'];
					$CavNo = $row['CavNo'];
					$ChangingPart = $row['ChangingPart'];
					$DiesetSize = $row['DiesetSize'];
					$DiesetWgt = $row['DiesetWgt'];
					$UnitWgt = $row['UnitWgt'];
					$SprueWgt = $row['SprueWgt'];
					$ShotWgt = $row['ShotWgt'];
					$CycleShot = $row['CycleShot'];
					$FixedAssetNo = $row['FixedAssetNo'];
					$DateManufactured = $row['DateManufactured'];
					$MoldMake = $row['MoldMake'];
					$ToolLife = $row['ToolLife'];
					$ToolShot = $row['ToolShot'];
					$ToolLifePMI = $row['ToolLifePMI'];
					$MaintenanceCycle = $row['MaintenanceCycle'];
					$InjectionMachine = $row['InjectionMachine'];
					$Material = $row['Material'];
					$ToneColor = $row['ToneColor'];
					$Remarks = $row['Remarks'];
				}
				echo '<label id="lbl_Date">Date:</label><input type="date" id="txt_Date" value="'.$date.'" style="width:20%;">';
				?>
				<label id="lbl_DiesetStatus">DIE-SET NEW / RETURN:</label>
				<select id="cmb_Status" style="width:20%;">
					<?php
						if($DiesetStatus == 1) {
							echo '<option value="1" selected>NEW DIE-SET</option>
								  <option value="2">RETURN DIE-SET</option>';
						} else if($DiesetStatus == 2) {
							echo '<option value="1">NEW DIE-SET</option>
								  <option value="2" selected>RETURN DIE-SET</option>';
						}
					?>
				</select>
				<label id="lbl_YECControlNum">YEC Molding Prd'n Control No:</label><input type="text" id="txt_YECControlNum" value="<?php echo $YECMoldCtrlNo; ?>" style="width:20%;" readOnly>
				<label id="lbl_R3Code">R/3 Code:</label><input type="text" id="txt_R3Code" value="<?php echo $R3Code; ?>" style="width:20%;"><br>
				<label id="lbl_EXPCode">EXP Code:</label><input type="text" id="txt_EXPCode" value="<?php echo $EXPCode; ?>" style="width:20%;">
				<label id="lbl_DeviceName">Device Name:</label><input type="text" id="txt_DeviceName" value="<?php echo $DeviceName; ?>" style="width:20%;"><br>
				<label id="lbl_DrawingNum">Drawing Number:</label><input type="text" id="txt_DrawingNum" value="<?php echo $DrawingNo; ?>" style="width:20%;">
				<label id="lbl_Rev">Rev:</label><input type="text" id="txt_Rev" value="<?php echo $Rev; ?>" style="width:8%;"><br>
				<label id="lbl_MoldClass">Mold Classification:</label>
				<select id="cmb_MoldClass" style="width:20%;">
					<?php 
						if($MoldClass == "Present Mold") {
							echo '<option value="Present Mold" selected>Present Mold</option>
								  <option value="Renewal Mold">Renewal Mold</option>
								  <option value="Additional Mold">Additional Mold</option>';
						} else if($MoldClass == "Renewal Mold") {
							echo '<option value="Present Mold">Present Mold</option>
								  <option value="Renewal Mold" selected>Renewal Mold</option>
								  <option value="Additional Mold">Additional Mold</option>';
						} else if($MoldClass == "Additional Mold") {
							echo '<option value="Present Mold">Present Mold</option>
								  <option value="Renewal Mold">Renewal Mold</option>
								  <option value="Additional Mold" selected>Additional Mold</option>';
						} 
					?>
				</select>
				<label id="lbl_DieNo">Die No:</label><input type="text" id="txt_DieNo" value="<?php echo $DieNo; ?>" style="width:8%;"><br>
				<label id="lbl_NoOfCav">Number of Cavity:</label><input type="text" id="txt_NumOfCavity" value="<?php echo $NoOfCav; ?>" style="width:20%;">
				<label id="lbl_CavNo">Cav No:</label>
				<select id="cmb_CavNum" style="width:8%;">
					<?php
						if($CavNo == "A") {
							echo '<option value"A" selected>A</option>
								  <option value="B">B</option>
								  <option value="C">C</option>
								  <option value="D">D</option>';
						} else if($CavNo == "B") {
							echo '<option value"A">A</option>
								  <option value="B" selected>B</option>
								  <option value="C">C</option>
								  <option value="D">D</option>';
						} else if($CavNo == "C") {
							echo '<option value"A">A</option>
								  <option value="B">B</option>
								  <option value="C" selected>C</option>
								  <option value="D">D</option>';
						} else if($CavNo == "D") {
							echo '<option value"A">A</option>
								  <option value="B">B</option>
								  <option value="C">C</option>
								  <option value="D" selected>D</option>';
						} 
					?>
				</select>
				<label id="lbl_ChangingPart">Changing Part:</label>
				<select id="cmb_ChangingPart" style="width:8%;">
					<?php
						if($ChangingPart == "Have") {
							echo '<option value="Have" selected>Have</option>
								  <option value="None">None</option>';
						} else if($ChangingPart == "None") {
							echo '<option value="Have">Have</option>
								  <option value="None" selected>None</option>';
						}
					?>
				</select><br>
				<label id="lbl_DiesetSize">Dieset Size:</label><input type="text" id="txt_DiesetSize" value="<?php echo $DiesetSize; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">Dieset Weight:</label><input type="text" id="txt_DiesetWeight" value="<?php echo $DiesetWgt; ?>" style="width:8%;"><br>
				<label id="lbl_UnitWgt">Unit Weight:</label><input type="text" id="txt_UnitWeight" value="<?php echo $UnitWgt; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">Sprue Weight:</label><input type="text" id="txt_SprueWeight" value="<?php echo $SprueWgt; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">1Shot Weight:</label><input type="text" id="txt_1ShotWeight" value="<?php echo $ShotWgt; ?>" style="width:8%;"><br>
				<label id="lbl_CycleTime">Cycle time/ 1shot:</label><input type="text" id="txt_CycleTimePerShot" value="<?php echo $CycleShot; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">Fixed Asset No:</label><input type="text" id="txt_FixedAssetNo" value="<?php echo $FixedAssetNo; ?>" style="width:20%;"><br><br>
				<!--Missing Date Manufactured>-->
			</div>
		  </div>
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<label id="lbl_MoldMak">Mold Make:</label><input type="text" id="txt_MoldMak" value="<?php echo $MoldMake; ?>" style="width:20%;">
				<label id="lbl_ToolLife">Tool Life:</label><input type="text" id="txt_ToolLife" value="<?php echo $ToolLife; ?>" style="width:20%;"><br>
				<label id="lbl_ToolShot">Tool Shot:</label><input type="text" id="txt_ToolShot" value="<?php echo $ToolShot; ?>" style="width:20%;">
				<label id="lbl_ToolLifePMI">Tool Life in PMI:</label><input type="text" id="txt_ToolLifePMI" value="<?php echo $ToolLifePMI; ?>" style="width:20%;"><br>
				<label id="lbl_MaintenanceCycle">Maintenance Cycle:</label><input type="text" id="txt_MaintenanceCycle" value="<?php echo $MaintenanceCycle; ?>" style="width:20%;"><br>
				<label id="lbl_InjectionMachine">Injection Machine:</label><input type="text" id="txt_InjectionMachine" value="<?php echo $InjectionMachine; ?>" style="width:20%;">
				<label id="lbl_Material">Material:</label><input type="text" id="txt_Material" value="<?php echo $Material; ?>" style="width:20%;"><br>
				<label id="lbl_ToneColor">Tone Color:</label><input type="text" id="txt_ToneColor" value="<?php echo $ToneColor; ?>" style="width:20%;"><br>
				<label id="lbl_Remarks">Remarks:</label><textarea rows="3" id="txt_Remarks" style="width:100%;"><?php echo $Remarks; ?></textarea><br>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<button id="btn_UpdateDieset" class="btn btn-success">Save</button>
				<?php
				  $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
				  echo "<a href='$url'><button class='btn btn-default'>GO BACK</button></a>"; 
				?>
			</div>
		</div>
	  </div>
	</div>
</body>
</html>