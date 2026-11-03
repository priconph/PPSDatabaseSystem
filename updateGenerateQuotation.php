<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
session_start();
//$username = $_SESSION['username'];
$username = "TEST";
$dateToday = date("Y-m-d H-i-s");
$pkid = $_GET["pkid"];


$LoadQuotaion = PPSDBS::getInstance()->LoadQuotationInfo($pkid);
if($rowQuotation = mysqli_fetch_array($LoadQuotaion)){
	$ControlNumberU = $rowQuotation["ControlNumber"];
	$DeviceNameU = $rowQuotation["DeviceName"];
	$PartsCodeU = $rowQuotation["R3Code"];
	$MaterialNameU = $rowQuotation["Material"];
	$NoOfCavU = $rowQuotation["NoOfCav"];
	$UnitWgtU = $rowQuotation["ProductWgt"];
	$SprueWgtU = $rowQuotation["SprueWgt"];
	$ShotWgtU = $rowQuotation["ShotWgt"];
	$CycleTimeU = $rowQuotation["CycleTime"];
	$ResinCostU = $rowQuotation["ResinCost"];
	$LaborCostU = $rowQuotation["LaborCost"];
	$ProcessTime2U = $rowQuotation["SecondProcessTime"];
	$RemarksU = $rowQuotation["Remarks"];
}
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/updateGenerateQuotation_stylesheet.css" rel="stylesheet"> <!--Link to Style Sheet-->
<script>
var DiesetID;
$('document').ready(function(){
	var oTable = $('#TableID HERE').dataTable({
			paginate:false,
			"autoWidth": false
	});
		
	//$("#container_QuotationGenerator").hide();
	//$("#btn_HideDiv").hide();
	
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
		$('#txt_ResinCostGQ').val((parseFloat($('#txt_ResinCostGQ').val()) * parseFloat(.10))+ parseFloat($('#txt_ResinCostGQ').val()));
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
	//Update data
	$("#btn_Save").click(function(){
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
		
		var data = {
					"action" : "updateQuotation",
					"pkid" : <?php echo $pkid;?>,
					"ControlNumber" : $("#txt_ControlNumber").val(),
					"ResinCost" : $("#txt_ResinCostGQ").val(),
					"NumOfCavity" : $("#txt_NumOfCavityGQ").val(),
					"ProductWeight" : $("#txt_ProductWeightGQ").val(),
					"SprueWeight" : $("#txt_SprueWeightGQ").val(),
					"ShotWeight" : $("#txt_ShotWeightGQ").val(),
					"LaborCost" : $("#txt_LaborCostGQ").val(),
					"CycleTime" : $("#txt_CycleTimeGQ").val(),
					"ProcessCost" : $("#txt_ProcessCostGQ").val(),
					"MaterialCost" : $("#txt_MaterialCostGQ").val(),
					"SecondTimeProcess" : $("#txt_2ndProcessTimeGQ").val(),
					"SecondProcessCost" : $("#txt_2ndProcessCostGQ").val(),
					"Profit" : $("#txt_ProfitGQ").val(),
					"MoldingQuotation" : $("#txt_MoldingProductQuotationGQ").val(),
					"Remarks" : $("#txt_RemarksGQ").val(),
					"LastUpdate" : "<?php echo $dateToday;?>",
					"Username" : "<?php echo $username;?>"
				}
				alert(data['SecondProcessCost']);
				data = $.param(data);
				$.ajax({
					type : "post",
					dataType : "json",
					data : data,
					url : "./ppsdbsHandler",
					success : function(data){
						alert ("Data Updated!");
						//$('body').append(data['result']);
						window.location.href = './generateQuotation.php';
					}
				});
		
		/*
		$("#tbl_GenerateQuotation tbody").append("<tr><td>"+
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
		$("#txt_MoldingProductQuotationGQ").val()+"</td><td><a href='javascript:void(0);' class='remCF'>Remove</a></td></tr>");
		
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
		//$("#container_QuotationGenerator").fadeOut();
		//$("#btn_AddNewDevice").hide();
		//$("#btn_HideDiv").hide();
		AppendCounter++;
		*/
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
});

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
			}
		echo '</datalist>';
		?>
		<label id="label_style1">CONTROL NUMBER: </label> <input value="<?php echo $ControlNumberU;?>" type="text" id="txt_ControlNumber" style="width:30%;" list="list_ControlNo" disabled><br>
		<br>	
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
				<label id="lbl_DeviceName">DEVICE NAME: </label> <input value="<?php echo $DeviceNameU;?>" type="text" id="txt_DeviceName" list="list_DeviceName" autocomplete="off">
				<label id="lbl_PartsCode">PARTSCODE: </label> <input value="<?php echo $PartsCodeU;?>" type="text" id="txt_Partscode" list="list_R3Code"><br><br>
				<label id="lbl_MaterialName">Material Name: </label> <input value="<?php echo $MaterialNameU;?>" type="text" id="txt_MaterialName" disabled><br><br>
				<label id="lbl_NoOfCav">Number of Cavity:</label><input value="<?php echo $NoOfCavU;?>" onkeypress="CheckNumeric()" type="text" id="txt_NumOfCavity" disabled>
				<label id="lbl_UnitWeight">Unit Weight:</label><input value="<?php echo $UnitWgtU;?>" onkeypress="CheckNumeric()" type="text" id="txt_UnitWeight" disabled>
				<label id="lbl_SprueWgt">Sprue Weight:</label><input value="<?php echo $SprueWgtU;?>" type="text" onkeypress="CheckNumeric()" id="txt_SprueWeight" disabled><br>
				<label id="lbl_ShotWgt">1Shot Weight:</label><input value="<?php echo $ShotWgtU;?>" type="text" onkeypress="CheckNumeric()" id="txt_1ShotWeight" disabled>
				<label id="lbl_CycleTime">Cycle time/ 1shot:</label><input onkeypress="CheckNumeric()" value="<?php echo $CycleTimeU;?>" type="text" id="txt_CycleTimePerShot" disabled>
				<br><br>
				<label id="lbl_ResinCost">Buying Price:</label><input value="<?php echo $ResinCostU;?>" onChange="computeQuotation()" onkeypress="CheckNumeric()" type="text" id="txt_ResinCostGQ">
				<label id="lbl_NoOfCav2">Number of Cavity:</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $NoOfCavU;?>" type="text" id="txt_NumOfCavityGQ"><br>
				<label id="lbl_ProdWgt">Product Weight (g):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $UnitWgtU;?>" type="text" id="txt_ProductWeightGQ">
				<label id="lbl_SprueWgt2">Sprue Weight (g):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $SprueWgtU;?>" type="text" id="txt_SprueWeightGQ"><br>
				<label id="lbl_ShotWgt2">Shot Weight (g):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $ShotWgtU;?>" type="text" id="txt_ShotWeightGQ">
				<label id="lbl_LaborCost">Labor Cost/hr ($):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $LaborCostU;?>" type="text" id="txt_LaborCostGQ"><br>
				<label id="lbl_CycleTime2">Cycle Time:</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $CycleTimeU; ?>" type="text" id="txt_CycleTimeGQ" >
				<label id="lbl_ProcessCost">Process Cost:</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_ProcessCostGQ" disabled><br>
				<label id="lbl_MaterialCost">Material Cost(per piece):</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_MaterialCostGQ" disabled>
				<label id="lbl_SecondProcessTime">2nd Process Time(secs):</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="<?php echo $ProcessTime2U;?>" type="text" id="txt_2ndProcessTimeGQ"><br>
				<label id="lbl_SecondProcessCost">2nd Process Cost:</label><input onChange="computeQuotation()" onkeypress="CheckNumeric()" value="0" type="text" id="txt_2ndProcessCostGQ" disabled>
				<label id="lbl_Profit">Profit:</label><input type="text" onkeypress="CheckNumeric()" value="0" id="txt_ProfitGQ" disabled><br>
				<label id="lbl_MoldingProdQuot">Molding Product Quotation ($):</label><input onkeypress="CheckNumeric()" value="0" type="text" id="txt_MoldingProductQuotationGQ" disabled><br><br>
				<label id="lbl_Remarks">Remarks:</label><input value="<?php echo $RemarksU;?>" type="text" id="txt_RemarksGQ" style="width:20%;"><br><br>
				<div align="right">
					
				</div>
			</div>
		</div>
		<!--
		<div class="panel panel-info">
			<div class="panel-heading">QUOTATION LIST</div>
			<div class="panel-body">
				<table id="tbl_GenerateQuotation"  class="table" border="1" style="font-size:10px;">
					<thead>
						<tr>
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
							<th>(x)</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>-->
		<div align="center">
			<button id="btn_Save" class="btn btn-success">Save</button>
			<?php
			  $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
			  echo "<a href='$url'><button class='btn btn-default'>GO BACK</button></a>"; 
			?>
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
<script>
computeQuotation();
</script>