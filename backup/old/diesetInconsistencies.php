<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date('Y-m-d');
session_start();
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/diesetInconsistencies_stylesheet.css" rel="stylesheet">
<script>
var Handler = './ppsdbsHandler.php';
$('document').ready(function(){
	
	var oTable = $('#tbl_DiesetInconsistencies').dataTable({
		paginate:false
	});
	
	$('#btn_AddDieset').click(function(){
		window.location.href = "./addDieset.php";
	});
	$('#btn_GenerateQuotation').click(function(){
		window.location.href = "./generateQuotation.php";
	});
	$('#btn_ExportToExcel').click(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = "./DIESET-ExportToExcel.php?sDate="+startDate+"&eDate="+endDate;
	});
	$('#btn_ImportCSV').click(function () {
		$('#ModalImportDiesetCSV').modal('show');
	});
	$('#tbl_DiesetMain tr').dblclick(function(){
		var yecControlNo = $(this).children("#yec").map(function() {
			return $(this).text();
		}).get();
		//alert(yecControlNo);
		window.location.href = "./updateDieset.php?yecno="+yecControlNo;
	});
	
	$('#tbl_DiesetInconsistencies tbody tr').dblclick(function(){
		var R3Code = $(this).children(".R3Code").map(function() {
			return $(this).text();
		}).get();
		
		var DeviceName = $(this).children(".DeviceName").map(function() {
			return $(this).text();
		}).get();
		
		var rev = $(this).children(".Rev").map(function() {
			return $(this).text();
		}).get();
		
		if(R3Code == ""){
			alert("This dieset doesn't have an R3Code please add R3Code to edit");
			$('#txt_DeviceName_Update').val(DeviceName);
			$('#modal_update_dieset_r3code').modal('show');
			return false;
		}
		
		window.location.href = "./updateDieset.php?r3code="+R3Code+"&rev="+rev;
	});
	
	$('#btn_update_r3code').click(function(){
		var DeviceName = $('#txt_DeviceName_Update').val();
		var R3Code = $('#txt_R3Code_Update').val();
		if(R3Code == ""){
			alert("Invalid R3Code");
			return false;
		}
		update_dieset_r3code(DeviceName,R3Code);
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
});

function update_dieset_r3code(DeviceName,R3Code){
	var data = {
		"action"	: "updateDiesetPartCode",
		"tbl_Name"	: "tbl_dieset",
		"DeviceName": DeviceName,
		"R3Code"	: R3Code
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: Handler,
		success	: function(data){
			alert(data['result']);
			window.location.reload();
		}
	});
}
</script>
</head>
<body>
<!--
<button class="btn btn-default" id="btn_AddNewDieset">ADD DIESET</button>
<button class="btn btn-default" id="btn_UpdateDieset">UPDATE DIESET</button>
-->
<div>
	<div class="panel panel-primary">
	<div class="panel-heading">DIE-SET MATERIAL - NOT EXISTING IN WAREHOUSE INVENTORY</div>
		<div class="panel-body">
			</div><h5 style="float:right;border: 2px solid;padding:.5%;color:red;">Note: Double Click Row to Edit</h5>
			<br><br>
			<label>Warning: The following Dieset doesn't have an equivalent material in the warehouse inventory list!</label>
			<table id="tbl_DiesetInconsistencies" border="1" style="font-size:12;">
				<thead>
					<tr>
						<th>Rack No.</th>
						<!--<th>Die-set Count</th>
						<th>Item Count</th>
						<th>For Evaluation Item</th>-->
						<th>R/3 Code</th>
						<!--<th>EXP CODE</th>-->
						<th>DEVICE NAME</th>
						<th>DRAWING NO</th>
						<th>REV</th>
						<th>MATERIAL</th>
						<th>REMARKS</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$getAllDieset = PPSDBS::getInstance()->getDiesetInconsistencies();
						while($row = mysqli_fetch_array($getAllDieset)) {
							echo "	
								<tr>
									<td id='yec' class='yec'>".$row['RackNum']."</td>
									<td class='R3Code'>".$row['R3Code']."</td>
									<td class='DeviceName'>".$row['DeviceName']."</td>
									<td>".$row['DrawingNo']."</td>
									<td class='Rev'>".$row['Rev']."</td>
									<td>".$row['Material']."</td>
									<td>".$row['Remarks']."</td>
								</tr>";
						}
					?>
				</tbody>
			</table>
			
		</div>
	</div>
</div>

<!--MODALS-->
<div id="modal_" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Modal1</h4>
			</div>
			<div class="modal-body">
				<p>YOU ARE ABOUT TO DELETE AN ACCOUNT</p>
				<p>ARE YOU SURE?</p>
				<p>PROCEED?</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btnDeleteUserAccountC" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
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

<div id="modal_update_dieset_r3code" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">UPDATE R3Code</h4>
			</div>
			<div class="modal-body">
				<label style="width:40%;">Device Name:</label><input type="text" id="txt_DeviceName_Update" style="width:60%;" readonly><br>
				<label style="width:40%;">R3 Code:</label><input type="text" id="txt_R3Code_Update" style="width:60%;">
			</div>
			<div class="modal-footer">
				<a href="#" id="btn_update_r3code" class="btn btn-success">UPDATE</a>
				<a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>