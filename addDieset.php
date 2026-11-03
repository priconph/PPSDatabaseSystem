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
<script>
$('document').ready(function(){
	$('#btn_Checksheet').click(function(){ window.location.href = "./addDiesetExportToPDF"; });
	$('#btn_Cancel').click(function() {	window.location.href = "./dieset.php"; 	});
	$('#txt_NumOfCavity').change(function(){});
	$('#txt_UnitWeight').change(function(){
		$('#txt_UnitWeight').val(parseFloat($('#txt_UnitWeight').val()).toFixed(4));
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		var SprueWeightH = parseFloat($('#txt_SprueWeight').val()).toFixed(4);
		$('#txt_ProductWeightGQ').val(UnitWeightH);
		var oneshot = parseFloat($('#txt_NumOfCavity').val()) * parseFloat(UnitWeightH) + parseFloat(SprueWeightH);
		$('#txt_1ShotWeight').val(oneshot.toFixed(4));
	});
	$('#txt_SprueWeight').change(function(){
		$('#txt_SprueWeight').val(parseFloat($('#txt_SprueWeight').val()).toFixed(4));
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		var SprueWeightH = parseFloat($('#txt_SprueWeight').val()).toFixed(4);
		$('#txt_SprueWeightGQ').val(parseFloat($('#txt_SprueWeight').val()).toFixed(4));
		
		var oneshot = parseFloat($('#txt_NumOfCavity').val()) * parseFloat(UnitWeightH) + parseFloat(SprueWeightH);
		$('#txt_1ShotWeight').val(oneshot.toFixed(4));
	});
	$('#txt_CycleTimePerShot').change(function(){
		$('#txt_CycleTimeGQ').val(parseFloat($('#txt_CycleTimePerShot').val()).toFixed(4));
	});
	$('#txt_DateManufactured').focusout(function () {
		var dateToday = '<?php echo $dateToday; ?>';
		var dateSelected = $('#txt_DateManufactured').val();
		if(dateSelected > dateToday) {
			alert('Invalid date manufactured!');
			$('#txt_DateManufactured').focus();
		} 
	});
	$('#btn_SaveDieset').click(function(){
		check_session_status();
	}); 
	
	$(function() {
		$('#check_plus').bootstrapToggle({
		  on: 'On',
		  off: 'Off'
		});
		$('#check_minus').bootstrapToggle({
		  on: 'On',
		  off: 'Off'
		});
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
function SaveRecord(status,date_mod){
	/* Validations */
	if($('#txt_R3Code').val()==""){
		alert("R3Code Required");
		$('#txt_R3Code').focus();
		return false;
	}
	if($('#txt_DeviceName').val()==""){
		alert("DeviceName Required");
		$('#txt_DeviceName').focus();
		return false;
	}
	if($('#txt_Rev').val()==""){
		alert("Rev Required");
		$('#txt_Rev').focus();
		return false;
	}
	
	var check_plus = $('#check_plus').prop('checked') ? 1 : 0;
	var check_minus = $('#check_minus').prop('checked') ? 1 : 0;
	
	var data = {
		"action" : "saveDieset",
		"Date" : $('#txt_Date').val(),
		"Status" : status,
		"DateModified" : date_mod,
		"DateReturned" : "",
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
		"Percentage" : $('#txt_percentage').val(),
		"Plus" : check_plus,
		"Minus" : check_minus,
		"InjectionMachine" : $('#txt_InjectionMachine').val(),
		"MaterialCode" : $('#txt_Material_code').val(), //07162025 by Nessa
		"Material" : $('#txt_Material').val(), 
		"MaterialCost" : $('#txt_Material_cost').val(), //07162025 by Nessa
		"MaterialType" : $('#txt_Material_type').val(), //07162025 by Nessa
		"ToneColor" : $('#txt_ToneColor').val(),
		"Remarks" : $('#txt_Remarks').val(),
		"Username" : "<?php echo $username; ?>",

		"NoOfPage" : $('#txtNumOfPage').val()
	}
	data = $.param(data);
	
	$.ajax({
		type : "POST",
		dataType : "json",
		url: "ppsdbsHandler.php",
		data: data,
		success: function(result) {
			if(result["msg"] == 'Success'){
				alert("Successfully Added!");
				window.location.href = "./addDieset.php";
			}else{
				alert("Record already exist!");
				if(confirm("Do you want to obsolete the previous record?")){
					obsolete_dieset(result['id']);
				}
			}
			
		}
	});
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
				SaveRecord(1,"");
			}
		} 
	});
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
			SaveRecord(2,"<?php echo date("Y-m-d"); ?>");
		}
	});
}
</script>
</head>
<body>
	<datalist id="list_MaterialInfo"></datalist>

	<div class="panel panel-primary" id="Container_Main">
	  <div class="panel-heading">Die-set Transfer and Material</div>
	  <div class="panel-body">
		<div class="panel panel-primary">
			<div class="panel-body">
				<div>
				  <!-- Nav tabs -->
				  <ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#details" aria-controls="details" role="tab" data-toggle="tab">DETAILS</a></li>
					<li role="presentation"><a href="#tool_life" aria-controls="tool_life" role="tab" data-toggle="tab">TOOL LIFE</a></li>
					<li role="presentation"><a href="#material" aria-controls="material" role="tab" data-toggle="tab">MATERIAL</a></li>
				  </ul>

				  <!-- Tab panes -->
				  <div class="tab-content">
					<!--START TAB 1-->
					<div role="tabpanel" class="tab-pane active" id="details" style="padding-top:20px;">
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_Date">Date:</label>
							</div>
							<div class="col-sm-4">  
								<input type="date" id="txt_Date" class="form-control">
							</div>
							<div class="col-sm-2">
								<label id="lbl_DiesetStatus" style="padding-top:5px;">DIE-SET NEW / RETURN:</label>
							</div>
							<div class="col-sm-4">
								<select id="cmb_Status" class="form-control">
									<option value="" selected disabled>-- SELECT --</option>
									<option value="1">NEW DIE-SET</option>
									<option value="2">MODIFIED DIE-SET</option>
									<option value="3">RETURNED DIE-SET</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_YECControlNum">YEC Molding Prd'n Control No:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_YECControlNum" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_DateModified">Date Modified:</label>
							</div>
							<div class="col-sm-4">
								<input type="date" id="txt_DateModified" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_EXPCode">EXP Code:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_EXPCode" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_DateReturned">Date Returned:</label>
							</div>
							<div class="col-sm-4">
								<input type="date" id="txt_DateReturned" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_R3Code">R/3 Code:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_R3Code" class="form-control" >
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_DeviceName">Device Name:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_DeviceName" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_DrawingNum">Drawing Number:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_DrawingNum" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_Rev">Rev:</label>
							</div>
							<div class="col-sm-2">
								<input type="text" id="txt_Rev" class="form-control">
							</div>

							<!-- Added for No of Page 10/11/2022 => Chris -->
							<div class="col-sm-1" style="padding-top:5px;">
								<label>No of Page:</label>
							</div>
							<div class="col-sm-1">
								<input type="number" id="txtNumOfPage" class="form-control text-center">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_MoldClass">Mold Classification:</label>
							</div>
							<div class="col-sm-4">  
								<select id="cmb_MoldClass" class="form-control">
									<option value="" selected disabled>-- SELECT --</option>
									<option value="Present Mold">Present Mold</option>
									<option value="Renewal Mold">Renewal Mold</option>
									<option value="Additional Mold">Additional Mold</option>
								</select>
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_DieNo">Die No:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_DieNo" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_NoOfCav">Number of Cavity:</label>
							</div>
							<div class="col-sm-2">  
								<input type="text" id="txt_NumOfCavity" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_CavNo">Cav No:</label>
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control" id="cmb_CavNum">
								<!--<select id="cmb_CavNum" class="form-control">
									<option value="" selected></option> 
									<option value="A">A</option>
									<option value="B">B</option>
									<option value="C">C</option>
									<option value="D">D</option>
								</select>-->
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_ChangingPart">Changing Part:</label>
							</div>
							<div class="col-sm-2">
								<select id="cmb_ChangingPart" class="form-control">
									<option value="" selected disabled></option>
									<option value="Have">Have</option>
									<option value="None" selected>None</option>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_RackNum">Rack No/Dieset Location:</label>
							</div>
							<div class="col-sm-2">  
								<input type="text" id="txt_RackNo" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_DiesetSize">Dieset Size:</label>
							</div>
							<div class="col-sm-2">
								<input type="text" id="txt_DiesetSize" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label style="margin-right:5%;width:10%;">Dieset Weight:</label>
							</div>
							<div class="col-sm-2">
								<input type="text" id="txt_DiesetWeight" class="form-control">
							</div>
						</div>						
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_UnitWgt">Unit Weight:</label>
							</div>
							<div class="col-sm-2">  
								<input type="text" id="txt_UnitWeight" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label style="margin-right:5%;width:10%;">Sprue Weight:</label>
							</div>
							<div class="col-sm-2">
								<input type="text" id="txt_SprueWeight" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label style="margin-right:5%;width:10%;">1Shot Weight:</label>
							</div>
							<div class="col-sm-2">
								<input type="text" id="txt_1ShotWeight" class="form-control">
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_CycleTime">Cycle time/ 1shot:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_CycleTimePerShot" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label>Fixed Asset No:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_FixedAssetNo" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="DataManufactured">Date Manufactured:</label>
							</div>
							<div class="col-sm-4">  
								<input type="date" id="txt_DateManufactured" min='<?php echo $dateToday; ?>' class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_MoldMak">Mold Make:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_MoldMak" class="form-control">
							</div>
						</div>
					</div>
					<!--END TAB 1-->
					<!--START TAB 2-->
					<div role="tabpanel" class="tab-pane" id="tool_life" style="padding-top:20px;">
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								
							</div>
							<div class="col-sm-4">  
								
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_ToolLife">Tool Life:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_ToolLife" class="form-control">
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_ToolShot">Tool Shot:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_ToolShot" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_ToolLifePMI">Tool Life in PMI:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_ToolLifePMI" class="form-control">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_MaintenanceCycle">Maintenance Cycle:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_MaintenanceCycle" class="form-control">
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_MaintenanceCycle">Percentage:</label>
							</div>
							<div class="col-sm-2">  
								<input type="number" id="txt_percentage" class="form-control">
							</div>
							<div class="col-sm-2">  
								<input type="checkbox" id="check_plus">
								<label id="">Plus</label><br/>
								<input type="checkbox" id="check_minus">
								<label id="">Minus</label>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_InjectionMachine">Injection Machine:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_InjectionMachine" class="form-control">
							</div>
						</div>						
						<div class="row">
							<div class="col-sm-12">
								<label id="lbl_Remarks">Remarks:</label>
								<textarea rows="3" id="txt_Remarks" style="width:100%;" class="form-control"><?php echo $Remarks; ?></textarea>
							</div>
						</div>
					</div>
					<!--END TAB 2-->
					<!--START TAB 3-->
					<div role="tabpanel" class="tab-pane" id="material" style="padding-top:20px;">
						<div class="row">
						<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_Material">Material Name:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_Material" class="form-control">
							</div>

							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_Material_cost">Material Code:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_Material_code" class="form-control" readonly>
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_Material_cost">Material Cost:</label>
							</div>
							<div class="col-sm-4">  
								<input type="text" id="txt_Material_cost" class="form-control" readonly>
							</div>
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_Material_cost">Material Type:</label>
							</div>
							<div class="col-sm-4">  
								<select id="txt_Material_type" class="form-control">
									<option value="" selected disabled>-- SELECT --</option>
									<option value="resin">RESIN</option>
									<option value="metal">METAL</option>
								</select>
							</div>
							
							<div class="col-sm-2" style="padding-top:5px;">
								<label id="lbl_ToneColor">Tone Color:</label>
							</div>
							<div class="col-sm-4">
								<input type="text" id="txt_ToneColor" class="form-control">
							</div>
						</div>
					</div>
					<!--END TAB 3-->
				  </div>
				  
				</div>
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

	<script>
	var Handler = 'ppsdbsHandler.php';
	$('document').ready(function(){

		$('#txt_Material').change(function(){ 
			var Material = $('#txt_Material').val();
			GetAllResinDetails(Material);
		});

		

	});

		GetAllResinFromPPDMISV2();
		function GetAllResinFromPPDMISV2(){
			var data = {
				"action"		: "GetAllResinFromPPDMISV2"
				// "MaterialName"	: MaterialName
			}
			data = $.param(data);
			
			$.ajax({
				type	: "post",
				dataType: "json",
				data	: data,
				url		: Handler,
				success	: function(data){
					// $('#txt_Material_code').val(data['PartNumber']);
					
					$('#list_MaterialInfo').empty();
					var ctr = 0;
					while(ctr < data['ctr']){
						var MaterialType = "<option>"+data['MaterialType'][ctr]+"</option>";
						$('#list_MaterialInfo').append(MaterialType);
						ctr++;
					}
						
						$('#txt_Material').removeAttr('list');	
						$('#txt_Material').attr('list','list_MaterialInfo');
				}
			});
		}

		function GetAllResinDetails(Material){
			var data = {
				"action"	: "GetAllResinDetails",
				"Material"	: Material
			}
			data = $.param(data);
			$.ajax({
				type	: "post",
				dataType: "json",
				data	: data,
				url		: Handler,
				success	: function(data){
					$('#txt_Material_code').val(data['PartNumber']);
					$('#txt_Material_cost').val(data['Cost']);
				}
			});
		}

	
	</script>





</body>
</html>