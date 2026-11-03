<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
session_start();
//$username = $_SESSION['username'];
$username = "TEST";
$dateToday = date("Y-m-d H-i-s");
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/addGenerateQuotation_stylesheet.css" rel="stylesheet">
<script>
var DiesetID;
$('document').ready(function(){
	var oTable = $('#TableID HERE').dataTable({
			paginate:false,
			"autoWidth": false
	});
		
	$("#container_QuotationGenerator").hide();
	$("#btn_HideDiv").hide();
	
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
	
	//change functions
	$("#txt_DeviceName").change(function(){
		var data = {
			"action" : "GetDiesetByDeviceName",
			"DeviceName" : $("#txt_DeviceName").val()
		}
		data = $.param(data);
		$.ajax({
			type : "post",
			dataType : "json",
			data : data,
			url : "./ppsdbsHandler.php",
			success : function(data){
				DiesetID = data["id"];
				//alert(DiesetID);
				$("#txt_Partscode").val(data["PartsCode"]);
				$("#txt_MaterialName").val(data["Material"]);
				$("#txt_NumOfCavity").val(data["NoOfCav"]);
				$("#txt_UnitWeight").val(data["UnitWgt"]);
				$("#txt_SprueWeight").val(data["SprueWgt"]);
				$("#txt_1ShotWeight").val(data["ShotWgt"]);
				$("#txt_CycleTimePerShot").val(data["CycleShot"]);
				computeQuotation();
			}
		});
	});
	
	$("#txt_Partscode").change(function(){
		var data = {
			"action" : "GetDiesetByPartsCode",
			"PartsCode" : $("#txt_Partscode").val()
		}
		data = $.param(data);
		$.ajax({
			type : "post",
			dataType : "json",
			data : data,
			url : "./ppsdbsHandler.php",
			success : function(data){
				DiesetID = data["id"];
				//alert(DiesetID);
				$("#txt_DeviceName").val(data["DeviceName"]);
				$("#txt_MaterialName").val(data["Material"]);
				$("#txt_NumOfCavity").val(data["NoOfCav"]);
				$("#txt_UnitWeight").val(data["UnitWgt"]);
				$("#txt_SprueWeight").val(data["SprueWgt"]);
				$("#txt_1ShotWeight").val(data["ShotWgt"]);
				$("#txt_CycleTimePerShot").val(data["CycleShot"]);
				computeQuotation();
			}
		});
	});
	
	
	$('#txt_ResinCostGQ').change(function(){
		var resincost = (parseFloat($('#txt_ResinCostGQ').val()) * parseFloat(.10))+ parseFloat($('#txt_ResinCostGQ').val());
		$('#txt_ResinCostGQ').val(resincost.toFixed(4));
		
		computeQuotation();
	});
	$('#txt_NumOfCavity').change(function(){
		$('#txt_NumOfCavityGQ').val($('#txt_NumOfCavity').val());
		computeQuotation();
	});
	
	$('#txt_UnitWeight').change(function(){
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		var SprueWeightH = parseFloat($('#txt_SprueWeight').val()).toFixed(4);
		$('#txt_ProductWeightGQ').val(UnitWeightH);
		
		var oneshot = parseFloat($('#txt_NumOfCavityGQ').val()) * parseFloat(UnitWeightH) + parseFloat(SprueWeightH);
		$('#txt_1ShotWeight').val(oneshot.toFixed(4));
		computeQuotation();
	});
	
	$('#txt_SprueWeight').change(function(){
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		var SprueWeightH = parseFloat($('#txt_SprueWeight').val()).toFixed(4);
		$('#txt_SprueWeightGQ').val(parseFloat($('#txt_SprueWeight').val()).toFixed(4));
		
		var oneshot = parseFloat($('#txt_NumOfCavityGQ').val()) * parseFloat(UnitWeightH) + parseFloat(SprueWeightH);
		$('#txt_1ShotWeight').val(oneshot.toFixed(4));
		computeQuotation();
	});
	
	$('#txt_CycleTimePerShot').change(function(){
		$('#txt_CycleTimeGQ').val(parseFloat($('#txt_CycleTimePerShot').val()).toFixed(4));
		computeQuotation();
	});
	
	//click functions
	$("#btn_AddNewDevice").click(function(){
		$("#container_QuotationGenerator").show();
		$("#btn_AddNewDevice").hide();
		$("#btn_HideDiv").show();
	});
	
	$("#btn_HideDiv").click(function(){
		$("#container_QuotationGenerator").hide();
		$("#btn_AddNewDevice").show();
		$("#btn_HideDiv").hide();
	});
	var AppendCounter = 0;
	//Append data
	$("#btn_AddQuotationList").click(function(){
		computeQuotation();
		
		var row=0; var checkExist=0;
		$("#tbl_GenerateQuotation tr").each(function(row,tr){
			//alert("moosos");
			if(row>0){ //this first row is the label kaya di sya isasama
				if($(tr).find('td:eq(0)').text()==$("#txt_DeviceName").val()){
					checkExist=1;
				}
			}
			row++;
		});
		
		if($("#txt_DeviceName").val() == "" || $("#txt_ResinCostGQ").val() == "0" || $("#txt_LaborCostGQ").val()== "0" || $("#txt_2ndProcessTimeGQ").val()== "0"){
			alert("Required Fields: Device Name, Resin Cost, Labor Cost and Second Process Time!");
			return false;
		}
		
		if(checkExist!=0){
			alert("This Device already exist in the quotation list!");
			return false;
		}
		
		$("#tbl_GenerateQuotation tbody").append("<tr><td style='display:none;'>"+
		DiesetID+"</td><td>"+
		$("#txt_DeviceName").val()+"</td><td>"+
		$("#txt_MaterialName").val()+"</td><td>"+
		$("#txt_Partscode").val()+"</td><td>"+
		$("#txt_ResinCostGQ").val()+"</td><td>"+
		$("#txt_NumOfCavityGQ").val()+"</td><td>"+
		$("#txt_ProductWeightGQ").val()+"</td><td>"+
		$("#txt_SprueWeightGQ").val()+"</td><td>"+
		$("#txt_ShotWeightGQ").val()+"</td><td>"+
		$("#txt_LaborCostGQ").val()+"</td><td>"+
		$("#txt_CycleTimeGQ").val()+"</td><td>"+
		$("#txt_ProcessCostGQ").val()+"</td><td>"+
		$("#txt_MaterialCostGQ").val()+"</td><td>"+
		$("#txt_2ndProcessTimeGQ").val()+"</td><td>"+
		$("#txt_2ndProcessCostGQ").val()+"</td><td>"+
		$("#txt_ProfitGQ").val()+"</td><td>"+
		$("#txt_MoldingProductQuotationGQ").val()+"</td><td>"+
		$("#txt_RemarksGQ").val()+"</td><td><a href='javascript:void(0);' class='remCF'>Remove</a></td></tr>");
		
		alert("New Device has been added to the Quotation Sheet");
		//reset value to blank or 0
		$('#txt_NumOfCavity').val("0");
		$('#txt_UnitWeight').val("0");
		$('#txt_SprueWeight').val("0");
		$('#txt_1ShotWeight').val("0");
		$('#txt_CycleTimePerShot').val("0");
		//////////////////////
		$('#txt_NumOfCavityGQ').val("0");
		$('#txt_ProductWeightGQ').val("0");
		$('#txt_SprueWeightGQ').val("0");
		$('#txt_CycleTimeGQ').val("0");
		//reset value
		$("#txt_DeviceName").val("");
		$("#txt_MaterialName").val("");
		$("#txt_Partscode").val("");
		$("#txt_ResinCostGQ").val("0");
		$("#txt_NumOfCavityGQ").val("0");
		$("#txt_ProductWeightGQ").val("0");
		$("#txt_SprueWeightGQ").val("0");
		$("#txt_ShotWeightGQ").val("0");
		$("#txt_LaborCostGQ").val("0");
		$("#txt_CycleTimeGQ").val("0");
		$("#txt_ProcessCostGQ").val("0");
		$("#txt_MaterialCostGQ").val("0");
		$("#txt_2ndProcessTimeGQ").val("0");
		$("#txt_2ndProcessCostGQ").val("0");
		$("#txt_ProfitGQ").val("0");
		$("#txt_MoldingProductQuotationGQ").val("0");
		$("#txt_RemarksGQ").val("");
		//$("#container_QuotationGenerator").fadeOut();
		//$("#btn_AddNewDevice").hide();
		//$("#btn_HideDiv").hide();
		AppendCounter++;
	});
	
	$("#btn_Save").click(function(){
		clearData($("#txt_ControlNumber").val(),AppendCounter);
	});
	
	$("#tbl_GenerateQuotation").on('click','.remCF',function(){ //TABLE ID = tblMain AND TABLE CLASS = remCF
		$(this).parent().parent().remove(); //REMOVES THE ROW
		AppendCounter--;
	});
	/*
	$('#test').change(function(){
		alert($(this).find('option:selected').text());
	});
	*/
	$('#txt_ControlNumber').change(function(){
		appendExistingQuotation($('#txt_ControlNumber').val());
	});
	
});

function clearData(ControlNo,AppendCounter){
	var data = {
		"action"	: "DeleteAllQuotation",
		"ControlNo" : ControlNo
	}
	data = $.param(data);
		$.ajax({
			type	: "post",
			dataType: "json",
			data	: data,
			url		: "./ppsdbsHandler.php",
			success	: function(data){
				$('#txtRevNo').val(data['RevNo']);
				//alert(data['RevNo']);
				if($("#txt_ControlNumber").val() == ""){
				alert("Please add a Control Number!");
				return false;
			}
			
			
			var row=0; var checkExist=0; var ctr=0;
			$("#tbl_GenerateQuotation tr").each(function(row,tr){
				if(row>0){ //this first row is the label kaya di sya isasama
					if($(tr).find('td:eq(0)').text()==$("#txt_DeviceName").val()){
						checkExist=1;
					}
					ctr++;
				}
				row++;
				
			});
			if(ctr < 1){
				alert("Please add a device!");
				return false;
			}
			
			var row=0;
			$("#tbl_GenerateQuotation tr").each(function(row,tr){
				//alert("moosos");
				if(row>0){ //this first row is the label kaya di sya isasama
					
					var data = {
						"action" : "addQuotation",
						"fkid" : $(tr).find('td:eq(0)').text(),
						"ControlNumber" : $("#txt_ControlNumber").val(),
						"RevNo" : $("#txtRevNo").val(),
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
						}
					});
				
				}
				row++;
			});
			alert("New record has been added!");
			window.location.href="generateQuotation.php"
			}
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

//ComputeQuotation();
function computeQuotation(){
	$('document').ready(function(){
		var UnitWeightH = parseFloat($('#txt_UnitWeight').val()).toFixed(4);
		//$('#txt_NumOfCavityGQ').val($('#txt_NumOfCavity').val());
		//$('#txt_ProductWeightGQ').val(UnitWeightH);
		//$('#txt_SprueWeightGQ').val(parseFloat($('#txt_SprueWeight').val()).toFixed(4));
		//$('#txt_CycleTimeGQ').val(parseFloat($('#txt_CycleTimePerShot').val()).toFixed(4));
		//alert("test");
		var ResinCost=0,NumOfCav=0,ProdWt=0,SprueWt=0,ShotWt=0,LaborCostPerHr=0,CycleTime=0,ProcessCost=0;
		var MaterialCostPerPiece=0,SecProcessTimePerSec=0,SecProcessCost=0,Profit=0,MoldingProdQuot=0;
		ResinCost = $('#txt_ResinCostGQ').val();
		NumOfCav = $('#txt_NumOfCavityGQ').val();
		ProdWt = $('#txt_ProductWeightGQ').val();
		SprueWt = $('#txt_SprueWeightGQ').val();
		ShotWt = $('#txt_ShotWeightGQ').val();
		ShotWt = (parseFloat(NumOfCav) * parseFloat(ProdWt)) + parseFloat(SprueWt);
		LaborCostPerHr = $('#txt_LaborCostGQ').val();
		CycleTime = $('#txt_CycleTimeGQ').val();
		ProcessCost = (((parseFloat(LaborCostPerHr)/parseFloat(60))/parseFloat(60))*parseFloat(CycleTime))/parseFloat(NumOfCav);
		//MaterialCostPerPiece = ((parseFloat(ShotWt) / parseFloat(1000)) * parseFloat(ResinCost) * parseFloat(0.8)) / parseFloat(NumOfCav); 
		MaterialCostPerPiece = ((parseFloat(ShotWt) / parseFloat(1000) / parseFloat(NumOfCav)) * parseFloat(ResinCost)) * parseFloat(0.8); 
		SecProcessTimePerSec = $('#txt_2ndProcessTimeGQ').val();
		SecProcessCost = (parseFloat(SecProcessTimePerSec) / parseFloat(60)) * 0.065;
		//Profit = (parseFloat(ProcessCost)+parseFloat(MaterialCostPerPiece)+parseFloat(SecProcessCost)) * parseFloat(.10);
		//Profit = (parseFloat(ProcessCost)+parseFloat(MaterialCostPerPiece)+parseFloat(SecProcessCost)) * parseFloat(.03);
		Profit = (parseFloat(ProcessCost)+parseFloat(MaterialCostPerPiece)+parseFloat(SecProcessCost)) * parseFloat(.10);
		MoldingProdQuot = parseFloat(ProcessCost) + parseFloat(MaterialCostPerPiece) + parseFloat(SecProcessCost) + parseFloat(Profit);
		
		//set values here
		ShotWt = ShotWt.toFixed(4);
		$('#txt_ShotWeightGQ').val(ShotWt);
		ProcessCost = ProcessCost.toFixed(4);
		$('#txt_ProcessCostGQ').val(ProcessCost);
		MaterialCostPerPiece = MaterialCostPerPiece.toFixed(4);
		$('#txt_MaterialCostGQ').val(MaterialCostPerPiece);
		SecProcessCost = SecProcessCost.toFixed(4);
		$('#txt_2ndProcessCostGQ').val(SecProcessCost);
		Profit = Profit.toFixed(4);
		$('#txt_ProfitGQ').val(Profit);
		MoldingProdQuot = MoldingProdQuot.toFixed(4);
		$('#txt_MoldingProductQuotationGQ').val(MoldingProdQuot);
	});
	
}

function appendExistingQuotation(ControlNumber){
	var data = {
		"action" : "LoadExistingQuotations",
		"ControlNumber" : ControlNumber
	}
	data = $.param(data);
	$.ajax({
		type 	: "post",
		dataType: "json",
		data 	: data,
		url 	: "./ppsdbsHandler.php",
		success : function(data){
			$('#tbl_GenerateQuotation tbody').empty();
			var ctr = 0;
			while (parseInt(ctr) < parseInt(data['ctr'])){
			var HtmlContent = "<tr>";
				HtmlContent 	+= "<td style='display:none;'>"+data['fkid'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['DeviceName'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['Material'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['R3Code'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['ResinCost'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['NoOfCav'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['ProductWgt'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['SprueWgt'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['ShotWgt'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['LaborCost'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['CycleTime'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['ProcessCost'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['MaterialCost'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['SecondProcessTime'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['SecondProcessCost'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['Profit'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['MoldingProdQuot'][ctr]+"</td>";
				HtmlContent 	+= "<td>"+data['Remarks'][ctr]+"</td>";
				HtmlContent 	+= "<td><a href='javascript:void(0);' class='remCF'>Remove</a></td>";
				HtmlContent		+= "</tr>";
				$('#tbl_GenerateQuotation tbody').append(HtmlContent);
				$('#txtRevNo').val(data['RevNo'][ctr]);
				ctr++;
			}
		}
	});
}
</script>
</head>
<body>
<div class="panel panel-primary">
	<div class="panel-heading">GENERATE QUOTATION REPORT</div>
	<div class="panel-body">
		<?php
		$result = PPSDBS::getInstance()->getAllControlNoQuotation();
		echo '<datalist id="list_ControlNo">';
			while($row = mysqli_fetch_array($result)){
				echo '<option>'.$row['ControlNumber'].'</option>';
				//var $ControlNumberArray = explode("","");
				$ControlNumberRecent = $row['ControlNumber'];
			}
		echo '</datalist>';
		?>
		<label id="label_style1">CONTROL NUMBER: </label> <input type="text" id="txt_ControlNumber" style="width:30%;" list="list_ControlNo" AutoComplete="off">
		<button id="btn_NewControl" class="btn btn-default">Generate New Control Number</button>
		<br><br>	
		
		<button id="btn_AddNewDevice" class="btn btn-default">ADD DEVICE</button>
		<button id="btn_HideDiv" class="btn btn-default">CLOSE</button>
		<input type='text' id='txtRevNo' style="display:none;">
		<br><br>
		<div class="panel panel-info" id="container_QuotationGenerator">
			<div class="panel-heading">QUOTATION LIST</div>
			<div class="panel-body">
				<?php
						
					$result = PPSDBS::getInstance()->getAllDeviceName();
					echo '<datalist id="list_DeviceName">';
					while ($row= mysqli_fetch_array($result)){
						echo '<option>'.$row["DeviceName"].'</option>';
					}
					echo '</datalist>';
					$result = PPSDBS::getInstance()->getAllDeviceName();
					echo '<datalist id="list_R3Code">';
					while ($row= mysqli_fetch_array($result)){
						echo '<option>'.$row["R3Code"].'</option>';
					}
					echo '</datalist>';
					
				?>
				<label id="lbl_DeviceName">DEVICE NAME: </label> <input type="text" id="txt_DeviceName" list="list_DeviceName" autocomplete="off">
				<label id="lbl_PartsCode">PARTSCODE: </label> <input type="text" id="txt_Partscode" list="list_R3Code"><br><br>
				<label id="lbl_MaterialName">Material Name: </label> <input type="text" id="txt_MaterialName" style="width:30%;" disabled><br><br>
				<label id="lbl_NoOfCav">Number of Cavity:</label><input value="0" onkeypress="CheckNumeric()" type="text" id="txt_NumOfCavity" disabled>
				<label id="lbl_UnitWeight">Unit Weight:</label><input value="0" onkeypress="CheckNumeric()" type="text" id="txt_UnitWeight" disabled>
				<label id="lbl_SprueWgt">Sprue Weight:</label><input value="0" type="text" onkeypress="CheckNumeric()" id="txt_SprueWeight" disabled><br>
				<label id="lbl_ShotWgt">1Shot Weight:</label><input value="0" type="text" onkeypress="CheckNumeric()" id="txt_1ShotWeight" disabled>
				<label id="lbl_CycleTime">Cycle time/ 1shot:</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_CycleTimePerShot" disabled>
				<br><br>
				<label id="lbl_ResinCost">Buying Cost:</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_ResinCostGQ">
				<label id="lbl_NoOfCav2">Number of Cavity:</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_NumOfCavityGQ" ><br>
				<label id="lbl_ProdWgt">Product Weight (g):</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_ProductWeightGQ" >
				<label id="lbl_SprueWgt2">Sprue Weight (g):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_SprueWeightGQ" ><br>
				<label id="lbl_ShotWgt2">Shot Weight (g):</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_ShotWeightGQ" >
				<label id="lbl_LaborCost">Labor Cost/hr ($):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_LaborCostGQ"><br>
				<label id="lbl_CycleTime2">Cycle Time:</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_CycleTimeGQ">
				<label id="lbl_ProcessCost">Process Cost:</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_ProcessCostGQ" disabled><br>
				<label id="lbl_MaterialCost">Material Cost(per piece):</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_MaterialCostGQ" disabled>
				<label id="lbl_SecondProcessTime">2nd Process Time(secs):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_2ndProcessTimeGQ"><br>
				<label id="lbl_SecondProcessCost">2nd Process Cost:</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_2ndProcessCostGQ" disabled>
				<label id="lbl_Profit">Profit:</label><input type="text" onkeypress="CheckNumeric()" value="0" id="txt_ProfitGQ" disabled><br>
				<label id="lbl_MoldingProdQuot">Molding Product Quotation ($):</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_MoldingProductQuotationGQ"  disabled><br><br>
				<label id="lbl_Remarks">Remarks:</label><input value="" type="text" id="txt_RemarksGQ"><br><br>
				<div align="right">
					<button id="btn_AddQuotationList" class="btn btn-default">ADD TO QUOTATION LIST</button>
				</div>
			</div>
		</div>
		
		<div class="panel panel-info">
			<div class="panel-heading">QUOTATION LIST</div>
			<div class="panel-body" id="tbl_QuotationList">
				<table id="tbl_GenerateQuotation"  class="table" border="1">
					<thead>
						<tr>
							<th style="display:none;">id</th>
							<th>Device</th>
							<th>Material Name</th>
							<th>Partscode</th>
							<th>Resin Cost</th>
							<th># of Cavity</th>
							<th>Product Wt.(g)</th>
							<th>Sprue Wt. (g)</th>
							<th>Shot Wt. (g)</th>
							<th>Labor Cost/hr ($)</th>
							<th>Cycle Time</th>
							<th>Process Cost</th>
							<th>Material Cost (per piece)</th>
							<th>2nd Process Time (secs)</th>
							<th>2nd Process Cost</th>
							<th>Profit</th>
							<th>Molding Product Quotation ($)</th>
							<th>Remarks</th>
							<th>(x)</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
		<div align="center">
			<button id="btn_Save" class="btn btn-success">Save</button>
			<a href="./generateQuotation.php"><button id="btn_Cancel" class="btn btn-danger">Cancel</button></a>
		</div>
	</div>
</div>

<div id="ModalImportDiesetCSV" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<form action="DIESET-ImportToCSV.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
						<p><input type="file" name="file" id="file"></p>
				</div>
				<div class="modal-footer">
				   <input type="submit" class="btn btn-success" value="SUBMIT">
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
				</div>
			</form>
		</div>
	</div>
</div>

</body>
</html>
<script>
var RecentCtrlNo = "<?php echo $ControlNumberRecent; ?>";
$(document).ready(function(){
	$('#btn_NewControl').click(function(){
		if($('#txt_ControlNumber').val() != "") {
		} else {
			var NewControlNo = RecentCtrlNo.split("-");
			if(typeof NewControlNo[2] === "undefined"){
				var UpdatedControlNo = 0001;
			}else{
				var UpdatedControlNo = parseFloat(NewControlNo[2]) + parseFloat(1);
			}
			
			var Year = "<?php echo date("Y"); ?>";
			var Month = "<?php echo date("m"); ?>";
			var CtrlNo;
			//CtrlNo = NewControlNo[2]+"-"+Year+Month+"-000"+UpdatedControlNo;
			//alert(UpdatedControlNo);
			if(parseFloat(UpdatedControlNo) >= 0 && parseFloat(UpdatedControlNo) < 10 ){
				CtrlNo = "PMI"+"-"+Year+Month+"-000"+UpdatedControlNo;
			}
			if(parseFloat(UpdatedControlNo) >= 10 && parseFloat(UpdatedControlNo) < 100){
				CtrlNo = "PMI"+"-"+Year+Month+"-00"+UpdatedControlNo;
			}
			if(parseFloat(UpdatedControlNo) >= 100 && parseFloat(UpdatedControlNo) < 1000){
				CtrlNo = "PMI"+"-"+Year+Month+"-0"+UpdatedControlNo;
			}
			if(UpdatedControlNo >= 1000 && UpdatedControlNo < 9999 ){
				CtrlNo = "PMI"+"-"+Year+Month+"-"+UpdatedControlNo;
			}
			$('#txt_ControlNumber').val(CtrlNo);
		}
	});
});
</script>