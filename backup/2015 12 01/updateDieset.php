<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$R3Code = $_GET['r3code'];
$Rev = $_GET['rev'];
session_start();
$username = $_SESSION["username"];
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/updatedieset_style_sheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#cmb_Status').change(function () {
		if($('#cmb_Status').val() == 1) {
			$('#txt_DateReturned').val("mm/dd/yyyy");
			$('#txt_DateModified').val("mm/dd/yyyy");
			$('#txt_DateModified').attr("disabled", "disabled");
			$('#txt_DateReturned').attr("disabled", "disabled");
		} else if($('#cmb_Status').val() == 2) {
			$('#txt_DateReturned').attr("disabled", "disabled");
			$('#txt_DateModified').removeAttr("disabled"); 
		} else if($('#cmb_Status').val() == 3) {
			$('#txt_DateModified').attr("disabled", "disabled");
			$('#txt_DateReturned').removeAttr("disabled"); 
		}
	});
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
		check_session_status();
	});
	
	$('#txt_DateManufactured').focusout(function () {
		var dateToday = '<?php echo $dateToday; ?>';
		var dateSelected = $('#txt_DateManufactured').val();
		if(dateSelected > dateToday) {
			alert('Invalid date manufactured!');
			$('#txt_DateManufactured').focus();
		} 
	});
});

function UpdateRecord(){
	if($('#cmb_Status').val() == 2 && $('#txt_DateModified').val() == '') {
		alert('Please specify the date modified!');
	} else if($('#cmb_Status').val() == 3 && $('#txt_DateReturned').val() == '') {
		alert('Please specify the date returned!');
	} else {
		var data = {
			"action" : "updateDieset",
			"id" : $('#txt_idu').val(),
			"Date" : $('#txt_Date').val(),
			"Status" : $('#cmb_Status').val(),
			"DateModified" : $('#txt_DateModified').val(),
			"DateReturned" : $('#txt_DateReturned').val(),
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
			"Remarks" : $('#txt_Remarks').val(),
			"DateManufactured" : $('#txt_DateManufactured').val(),
			"RackNo" : $('#txt_RackNo').val()
		}
		data = $.param(data);
		$.ajax({
			type : "POST",
			dataType : "json",
			url : "ppsdbsHandler.php",
			data : data,
			success : function(result) {
				alert(result['msg']);
				//alert("Successfully updated!");
			}
		});
	}
}

function SaveRecord(){
	if($('#cmb_Status').val() == 2 && $('#txt_DateModified').val() == '') {
		alert('Please specify the date modified!');
	} else if($('#cmb_Status').val() == 3 && $('#txt_DateReturned').val() == '') {
		alert('Please specify the date returned!');
	} else {
		var data = {
			"action" : "saveDieset",
			"Date" : $('#txt_Date').val(),
			"Status" : $('#cmb_Status').val(),
			"DateModified" : $('#txt_DateModified').val(),
			"DateReturned" : $('#txt_DateReturned').val(),
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
			"RackNum" : $('#txt_RackNo').val(),
			"DiesetSize" : $('#txt_DiesetSize').val(),
			"DiesetWeight" : $('#txt_DiesetWeight').val(),
			"UnitWeight" : $('#txt_UnitWeight').val(),
			"SprueWeight" : $('#txt_SprueWeight').val(),
			"ShotWeight" : $('#txt_1ShotWeight').val(),
			"CycleTimePerShot" : $('#txt_CycleTimePerShot').val(),
			"FixedAssetNum" : $('#txt_FixedAssetNo').val(),
			"DateManufactured" : $('#txt_DateManufactured').val(),
			"MoldMake" : $('#txt_MoldMak').val(),
			"ToolLife" : $('#txt_ToolLife').val(),
			"ToolShot" : $('#txt_ToolShot').val(),
			"ToolLifePMI" : $('#txt_ToolLifePMI').val(),
			"MaintenanceCycle" : $('#txt_MaintenanceCycle').val(),
			"InjectionMachine" : $('#txt_InjectionMachine').val(),
			"Material" : $('#txt_Material').val(),
			"ToneColor" : $('#txt_ToneColor').val(),
			"Remarks" : $('#txt_Remarks').val(),
			"Username" : "<?php echo $username; ?>"
		}
		
		data = $.param(data);
		$.ajax({
			type : "POST",
			dataType : "json",
			url : "ppsdbsHandler.php",
			data : data,
			success : function(result) {
				if(result["msg"] == 'Success'){
				alert("Successfully Added!");
				window.location.href = "./addDieset.php";
				}else{
					alert("Record already exist!");
					if(confirm("Do you want to obsolete the previous record?")){
						//obsolete_dieset(result['id']);
					}
				}
			}
		});
	}
}

function check_session_status(){
	var data = {
		"action"	: "session_check"
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: "./session_check.php",
		success	: function(data){
			if(data['username'] == null){
				alert('Session expired, Please relogin.');
				parent.parent.location.reload();
			}else{
				if($('#cmb_Status').val() == "2"){
					obsolete_dieset($('#txt_idu').val());
				}else{
					UpdateRecord();
				}
			}
		} 
	});
}

function goBack() {
	window.history.back()
}

function obsolete_dieset(id){
	var data = {
		"action"	: "add_obsolete_dieset",
		"id"		: id
	}
	
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: "ppsdbsHandler.php",
		success	: function(data){
			alert(data['msg']);
			SaveRecord();
		}
	});
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
				$result = PPSDBS::getInstance()->getDiesetTransferMaterial($R3Code,$Rev);
				while($row = mysqli_fetch_array($result)) {
					$id = $row['id'];
					$YECMoldCtrlNo = $row['YECMoldCtrlNo'];
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
					$DateModified = $row['DateModified'];
					$DateReturned = $row['DateReturned'];
					$RackNo = $row['RackNum'];
				}
				echo '<label id="lbl_Date">Date:</label><input type="date" id="txt_Date" value="'.$date.'" style="width:20%;">';
				?>
				<label id="lbl_DiesetStatus">DIE-SET NEW / RETURN:</label>
				<select id="cmb_Status" style="width:20%;">
					<?php
						if($DiesetStatus == 1) {
							echo '<option value="1" selected>NEW DIE-SET</option>
								  <option value="2">MODIFIED DIE-SET</option>
								  <option value="3">RETURN DIE-SET</option>';
						} else if($DiesetStatus == 2) {
							echo '<option value="1">NEW DIE-SET</option>
								  <option value="2" selected>MODIFIED DIE-SET</option>
								  <option value="3">RETURN DIE-SET</option>';
						} else if($DiesetStatus == 3) {
							echo '<option value="1">NEW DIE-SET</option>
								  <option value="2">MODIFIED DIE-SET</option>
								  <option value="3" selected>RETURN DIE-SET</option>';
						}
					?>
				</select>
				<input type="text" id="txt_idu" value="<?php echo $id; ?>" style="width:20%;display:none;" readOnly>
				<label id="lbl_YECControlNum">YEC Molding Prd'n Control No:</label><input type="text" id="txt_YECControlNum" value="<?php echo $YECMoldCtrlNo; ?>" style="width:20%;">
				<label id="lbl_DateModified">Date Modified:</label><input type="date" id="txt_DateModified" value="<?php echo $DateModified; ?>" style="width:20%;height:30px;"><br>
				<label id="lbl_EXPCode">EXP Code:</label><input type="text" id="txt_EXPCode" value="<?php echo $EXPCode; ?>" style="width:20%;">
				<label id="lbl_DateReturned">Date Returned:</label><input type="date" id="txt_DateReturned" value="<?php echo $DateReturned; ?>" style="width:20%;height:30px;"><br>
				<label id="lbl_R3Code">R/3 Code:</label><input type="text" id="txt_R3Code" value="<?php echo $R3Code; ?>" style="width:20%;" >
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
				</select>
				<label id="lbl_RackNum">Rack No/Dieset Location:</label><input type="text" id="txt_RackNo" value="<?php echo $RackNo; ?>"><br>
				<label id="lbl_DiesetSize">Dieset Size:</label><input type="text" id="txt_DiesetSize" value="<?php echo $DiesetSize; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">Dieset Weight:</label><input type="text" id="txt_DiesetWeight" value="<?php echo $DiesetWgt; ?>" style="width:8%;"><br>
				<label id="lbl_UnitWgt">Unit Weight:</label><input type="text" id="txt_UnitWeight" value="<?php echo $UnitWgt; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">Sprue Weight:</label><input type="text" id="txt_SprueWeight" value="<?php echo $SprueWgt; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">1Shot Weight:</label><input type="text" id="txt_1ShotWeight" value="<?php echo $ShotWgt; ?>" style="width:8%;"><br>
				<label id="lbl_CycleTime">Cycle time/ 1shot:</label><input type="text" id="txt_CycleTimePerShot" value="<?php echo $CycleShot; ?>" style="width:8%;">
				<label style="margin-right:5%;width:10%;">Fixed Asset No:</label><input type="text" id="txt_FixedAssetNo" value="<?php echo $FixedAssetNo; ?>" style="width:20%;"><br>
				<label id="DataManufactured">Date Manufactured:</label><input type="date" id="txt_DateManufactured" value="<?php echo $DateManufactured; ?>" min='<?php echo $dateToday; ?>'><br><br>
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