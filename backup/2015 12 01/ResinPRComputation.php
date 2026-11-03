<?php
require_once('includes.php');
?>
<html>
<title></title>
<head>
<script>
$(document).ready(function(){
	//alert("test");
	$('#btn_Compute').click(function(){
		computeKgsNeeded();
	});
});
function computeKgsNeeded(){
	var OrderQty = $('#txt_OrderQty').val();
	var NumOfCavC = $('#txt_NoOfCav').val();
	var GmsPerShotC = $('#txt_GramPerShot').val();
	var MinOrderQty = $('#txt_MinOrder').val();
	
	var AdditionalKgsC = (parseFloat(0.8)*(parseFloat(180)+(parseFloat(34)*parseFloat(GmsPerShotC))))/parseFloat(1000);
	AdditionalKgsC = AdditionalKgsC.toFixed(2);
	$('#txt_AdditionalKgs').val(AdditionalKgsC);
	
	var neededKgs = 0, Recycled = 0;
	
	if(parseFloat(OrderQty) > parseFloat(MinOrderQty)){
		neededKgs = (parseFloat(OrderQty) * parseFloat(GmsPerShotC) * parseFloat(1.05) * parseFloat(0.8)) / (parseFloat(NumOfCavC) * parseFloat(1000));
	}
	else{
		neededKgs = ((parseFloat(OrderQty) * parseFloat(GmsPerShotC) * parseFloat(0.8)) / (parseFloat(NumOfCavC) * parseFloat(1000))) + parseFloat(AdditionalKgsC);
	}
	neededKgs = parseFloat(neededKgs).toFixed(2);
	Recycled = (parseFloat(neededKgs) * parseFloat(0.2)) / parseFloat(0.8);
	Recycled = parseFloat(Recycled).toFixed(2);
	
	var totalKgs = parseFloat(neededKgs) + parseFloat(Recycled);
	totalKgs = parseFloat(totalKgs).toFixed(2);
	//alert(neededKgs + " + " + Recycled)
	$('#txt_TotalKgsNeeded').val(totalKgs);
	var TotalPrice = parseFloat($("#txt_CostKgs").val()) * parseFloat($("#txt_TotalKgsNeeded").val());
	TotalPrice = TotalPrice.toFixed(2);
	$('#txt_TotalPrice').val(TotalPrice);
}
</script>
</head>
<body>
	<label style="width:30%;margin-right:2%;">Order Qty</label><input type ="text" id ="txt_OrderQty">
	<label style="width:30%;margin-right:2%;">Device Name</label><input type ="text" id ="txt_DeviceName">
	<label style="width:30%;margin-right:2%;">Die No</label><input type ="text" id ="txt_DieNo">
	<label style="width:30%;margin-right:2%;">No. of Cavity</label><input type ="text" id ="txt_NoOfCav">
	<label style="width:30%;margin-right:2%;">Cycle Time</label><input type ="text" id ="txt_CycleTime">
	<label style="width:30%;margin-right:2%;">Unit Wgt</label><input type ="text" id ="txt_UnitWgt">
	<label style="width:30%;margin-right:2%;">Gram per shot</label><input type ="text" id ="txt_GramPerShot">
	<label style="width:30%;margin-right:2%;">Units per day</label><input type ="text" id ="txt_UnitPerDay">
	<label style="width:30%;margin-right:2%;">Additional Kgs</label><input type ="text" id ="txt_AdditionalKgs">
	<label style="width:30%;margin-right:2%;">Maintenance Cycle</label><input type ="text" id ="txt_MaintenanceCycle">
	<label style="width:30%;margin-right:2%;">Mach Alloc</label><input type ="text" id ="txt_MachAlloc">
	<label style="width:30%;margin-right:2%;">Material Type</label><input type ="text" id ="txt_MaterialType">
	<label style="width:30%;margin-right:2%;">Min order</label><input type ="text" id ="txt_MinOrder">
	<label style="width:30%;margin-right:2%;">Cost/kgs($)</label><input type ="text" id ="txt_CostKgs">
	<label style="width:30%;margin-right:2%;">True Value</label><input type ="text" id ="txt_TrueValue"><br><br><br>
	<h2>Total kgs needed:</h2>
	<label style="width:30%;margin-right:2%;">KGS Needed</label><input type ="text" id ="txt_TotalKgsNeeded">
	<h2>Total Price	:</h2>
	<label style="width:30%;margin-right:2%;">Total Price($):</label><input type ="text" id ="txt_TotalPrice">
	<button id="btn_Compute">Compute</button>
</body>
</html>