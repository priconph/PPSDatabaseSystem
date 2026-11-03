<?php
require_once('./includes.php');
$dateToday = date("Y-m-d");
session_start();
$username = $_SESSION['username'];
$username = 'marlope';
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/adddieset_style_sheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#btn_Checksheet').click(function(){
		window.location.href = "./addDiesetExportToPDF";
	});
	
	$('#btn_Cancel').click(function() {
		window.location.href = "./dieset.php";
	});
		
	//change functions
	$('#txt_NumOfCavity').change(function(){
		//computeQuotation();
	});
	
	$('#txt_UnitWeight').change(function(){
		$('#txt_UnitWeight').val(parseFloat($('#txt_UnitWeight').val()).toFixed(4));
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		var SprueWeightH = parseFloat($('#txt_SprueWeight').val()).toFixed(4);
		$('#txt_ProductWeightGQ').val(UnitWeightH);
		
		var oneshot = parseFloat($('#txt_NumOfCavity').val()) * parseFloat(UnitWeightH) + parseFloat(SprueWeightH);
		$('#txt_1ShotWeight').val(oneshot.toFixed(4));
		//computeQuotation();
	});
	
	$('#txt_SprueWeight').change(function(){
		$('#txt_SprueWeight').val(parseFloat($('#txt_SprueWeight').val()).toFixed(4));
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		var SprueWeightH = parseFloat($('#txt_SprueWeight').val()).toFixed(4);
		$('#txt_SprueWeightGQ').val(parseFloat($('#txt_SprueWeight').val()).toFixed(4));
		
		var oneshot = parseFloat($('#txt_NumOfCavity').val()) * parseFloat(UnitWeightH) + parseFloat(SprueWeightH);
		$('#txt_1ShotWeight').val(oneshot.toFixed(4));
		//computeQuotation();
	});
	
	$('#txt_CycleTimePerShot').change(function(){
		$('#txt_CycleTimeGQ').val(parseFloat($('#txt_CycleTimePerShot').val()).toFixed(4));
		//computeQuotation();
	});
	
	$('#txt_DateManufactured').focusout(function () {
		var dateToday = '<?php echo $dateToday; ?>';
		var dateSelected = $('#txt_DateManufactured').val();
		if(dateSelected > dateToday) {
			alert('Invalid date manufactured!');
			$('#txt_DateManufactured').focus();
		} 
	});
	/*
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
	*/
	$('#btn_SaveDieset').click(function(){
		var data = {
			"action" : "saveDieset",
			"Date" : $('#txt_Date').val(),
			"Status" : 1,
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
			url: "ppsdbsHandler.php",
			data: data,
			success: function(result) {
				alert(result["msg"]);
				window.location.href = "./addDieset.php";
			}
		});
		//for checking
		/*
		alert(data['action']+data['Date']+data['YECControlNum']+data['Status']+data['R3Code']+data['ExpCode']+data['DeviceName']+data['DrawingNum']+data['Rev']+data['DieNo']+data['MoldClass']+data['DieNo']+data['NumOfCavity']+data['CavNum']+data['ChangingPart']+data['DiesetSize']+data['DiesetWeight']+data['UnitWeight']+data['SprueWeight']+data['1ShotWeight']+data['CycleTimePerShot']+data['FixedAssetNum']+data['MoldMake']+data['ToolLife']+data['ToolShot']+data['ToolLifePMI']+data['MaintenanceCycle']+data['InjectionMachine']+data['Material']+data['ToneColor']+data['Remarks']);
		*/
	}); 
});
function CheckNumeric(){
  // Get ASCII value of key that user pressed
   var key = window.event.keyCode;
  // Was key that was pressed a numeric character (0-9)?
   if ( key > 45 && key < 58 )
     return; // if so, do nothing
   else
     window.event.returnValue = null; // otherwise, 
	                               // discard character
}
</script>
</head>
<body>
	<div class="panel panel-primary" id="Container_Main">
	  <div class="panel-heading">Die-set Transfer and Material</div>
	  <div class="panel-body">
		<div class="panel panel-primary">
			<div class="panel-body">
				<?php
				echo '<label id="lbl_Date">Date:</label><input type="date" id="txt_Date" value="'.$dateToday.'">';
				?>
				<label id="lbl_DiesetStatus">DIE-SET NEW / RETURN:</label><input type="text" id="cmb_Status" value="NEW DIE-SET" readOnly>
				<label id="lbl_YECControlNum">YEC Molding Prd'n Control No:</label><input type="text" id="txt_YECControlNum">
				<label id="lbl_R3Code">R/3 Code:</label><input type="text" id="txt_R3Code"><br>
				<label id="lbl_EXPCode">EXP Code:</label><input type="text" id="txt_EXPCode">
				<label id="lbl_DeviceName">Device Name:</label><input type="text" id="txt_DeviceName"><br>
				<label id="lbl_DrawingNum">Drawing Number:</label><input type="text" id="txt_DrawingNum">
				<label id="lbl_Rev">Rev:</label><input type="text" id="txt_Rev"><br>
				<label id="lbl_MoldClass">Mold Classification:</label>
				<select id="cmb_MoldClass">
					<option>Present Mold</option>
					<option>Renewal Mold</option>
					<option>Additional Mold</option>
				</select>
				<label id="lbl_DieNo">Die No:</label><input type="text" id="txt_DieNo" ><br>
				<label id="lbl_NoOfCav">Number of Cavity:</label><input value="0" onkeypress="CheckNumeric()" type="text" id="txt_NumOfCavity">
				<label id="lbl_CavNo">Cav No:</label>
				<input type="text" id="cmb_CavNum"><br>
				<!--
				<select id="cmb_CavNum" >
					<option>A</option>
					<option>B</option>
					<option>C</option>
					<option>D</option>
				</select>
				-->
				<label id="lbl_ChangingPart">Changing Part:</label>
				<select id="cmb_ChangingPart" >
					<option>Have</option>
					<option>None</option>
				</select>
				<label id="lbl_RackNum">Rack No/Dieset Location:</label><input type="text" id="txt_RackNo"><br>
				<label id="lbl_DiesetSize">Dieset Size:</label><input type="text" id="txt_DiesetSize">
				<label id="lbl_DiesetWgt">Dieset Weight:</label><input type="text" id="txt_DiesetWeight"><br>
				<label id="lbl_UnitWgt">Unit Weight:</label><input value="0" onkeypress="CheckNumeric()" type="number" id="txt_UnitWeight">
				<label id="lbl_SprueWgt">Sprue Weight:</label><input value="0" type="number" onkeypress="CheckNumeric()" id="txt_SprueWeight">
				<label id="lbl_ShotWgt">1Shot Weight:</label><input value="0" type="number" onkeypress="CheckNumeric()" id="txt_1ShotWeight"><br>
				<label id="lbl_CycleTime">Cycle time/ 1shot:</label><input onkeypress="CheckNumeric()" value="0" type="number" id="txt_CycleTimePerShot">
				<label id="lbl_FixedAsset">Fixed Asset No:</label><input type="text" id="txt_FixedAssetNo"><br>
				<label id="DataManufactured">Date Manufactured:</label><input type="date" id="txt_DateManufactured" min='<?php echo $dateToday; ?>'><br><br>
			</div>
		  </div>
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<label id="lbl_MoldMak">Mold Mak:</label><input type="text" id="txt_MoldMak">
				<label id="lbl_ToolLife">Tool Life:</label><input type="number" id="txt_ToolLife" onkeypress="CheckNumeric()"><br>
				<label id="lbl_ToolShot">Tool Shot:</label><input type="number" id="txt_ToolShot" onkeypress="CheckNumeric()">
				<label id="lbl_ToolLifePMI">Tool Life in PMI:</label><input type="number" onkeypress="CheckNumeric()" id="txt_ToolLifePMI"><br>
				<label id="lbl_MaintenanceCycle">Maintenance Cycle:</label><input type="number" onkeypress="CheckNumeric()" id="txt_MaintenanceCycle"><br>
				<label id="lbl_InjectionMachine">Injection Machine:</label><input type="text" id="txt_InjectionMachine">
				<label id="lbl_Material">Material:</label><input type="text" id="txt_Material"><br>
				<label id="lbl_ToneColor">Tone Color:</label><input type="text" id="txt_ToneColor"><br>
				<label id="lbl_Remarks">Remarks:</label><textarea rows="3" id="txt_Remarks" ></textarea><br>
			</div>
		</div>
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<button id="btn_Checksheet" class="btn btn-default">PDF Checksheet</button>
				<button id="btn_SaveDieset" class="btn btn-success">Save</button>
				<button class="btn btn-danger" id="btn_Cancel">Cancel</button>
			</div>
		</div>
	  </div>
	</div>
</body>
</html>