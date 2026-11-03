<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date('Y-m-d');
/*
Coding
Notes: Change the color of the button generate pr if existing resin will be lower than the amount needed to produce the new P.O.
*/
$getOrderNoList = PPSDBS::getInstance()->getOrderNoListPOReceived();
echo "<datalist id='OrderNoList'>";
echo "<option>-VIEW ALL-</option>";
while($rowOrderNoList = mysqli_fetch_array($getOrderNoList)) {
	echo '<option>'.$rowOrderNoList['OrderNo'].'</option>';
}
echo "</datalist>";
//$checker = 0;

?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<style>
#tbl_POReceivedMain th, td{
font-size:14px;
border: 1px solid black;
}
#tbl_POReceivedMain thead tr th:nth-child(1){
width:2%;
}
#tbl_POReceivedMain thead tr th:nth-child(2){
width:10%;
}
#tbl_POReceivedMain thead tr th:nth-child(3){
width:10%;
}
#tbl_POReceivedMain thead tr th:nth-child(4){
width:10%;
}
#tbl_POReceivedMain thead tr th:nth-child(5){
width:3%;
}
#tbl_POReceivedMain thead tr th:nth-child(6){
width:3%;
}
#tbl_POReceivedMain thead tr th:nth-child(7){
width:3%;
}
#tbl_POReceivedMain thead tr th:nth-child(8){
width:3%;
}
#tbl_POReceivedMain thead tr th:nth-child(9){
width:3%;
}
#tbl_POReceivedMain thead tr th:nth-child(10){
width:3%;
}
#tbl_POReceivedMain thead tr th:nth-child(11){
width:3%;
}
</style>
<script>
$('document').ready(function(){
	var oTable = $('#tbl_POReceivedMain').dataTable({
		paginate:false,
		"autoWidth": false
	});
	
	$('#btn_ImportCSV').click(function(){
		$('#modal_ImportPO').modal('show');
	});
	
	$('#btn_AddNewPO').click(function(){
		window.location.href = "./addPOReceived.php";
	});
	
	$('#btn_ResinComputation').click(function(){
		window.location.href = "./generateResinPR.php"
		//$('#modal_GenerateResinPR').modal('show');
	});
	
	$('#btn_Delete').click(function () {
		alert("Successfully Deleted!");
		window.location.href = "./poReceived.php";
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
	
	$('#tbl_POReceivedMain').on("dblclick","tbody tr", function (event) { 
		var orderNo = $(this).find('.orderNo').html();
		window.location.href = "./updatePOReceived.php?orderNo="+orderNo; 
	});
	
	$('#btn_ExportToExcel').click(function () {
		//export all data for delivery confirmation that has PO balance
		window.location.href = './PORECEIVED-ExportToExcel.php';
	});
	
	$('#btn_ExportToPDF').click(function() {
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = './PORECEIVED-ExportToPDF.php?startdate='+startDate+'&enddate='+endDate;
	});
});
</script>
</head>
<body>
<div>
	<div class="panel panel-primary" style="overflow-x:scroll;">
	<div class="panel-heading" style="width:auto;overflow:hidden;">Non Existing Dieset</div>
		<div class="panel-body" style="width:auto;">
			<table id="tbl_POReceivedMain">
				<thead>
					<tr>
						<th>CATEGORY</th>
						<th>PRODUCT P.O. NUMBER</th>
						<th>ITEM CODE</th>
						<th>ITEM NAME</th>
						<th>ORDER NO.</th>
						<th>ORDER QTY</th>
						<th>PRICE</th>
						<th>AMOUNT</th>
						<th>DATE ISSUED</th>
						<th>SHIPOUT</th>
						<th>PO BALANCE</th>
					</tr>
			</div>
				</thead>
				<tbody>
				<?php 
					
					$getAllPOReceived = PPSDBS::getInstance()->loadNonExistingPOReceived();
					while($row = mysqli_fetch_array($getAllPOReceived)) {
						echo "<tr>";
						echo "
							<td>".$row['Category']."</td>
							<td>".$row['ProductPONo']."</td>
							<td>".$row['ItemCode']."</td>
							<td>".$row['ItemName']."</td>
							<td class='orderNo'>".$row['OrderNo']."</td>
							<td>".$row['OrderQty']."</td>
							<td>".$row['Price']."</td>
							<td>".$row['Amount']."</td>
							<td>".$row['DateIssued']."</td>
							<td>".$row['ShipOutPPS']."</td>
							<td>".$row['POBalance']."</td>
						";
						echo "</tr>";
					}					
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modal_GenerateResinPR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Generate Resin PR</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:40%;">PO Balance Total kgs. needed:</label><input type="text" id="txt_TotalKgsNeededGM" style="width:50%;"><br><br>
				<label style="margin-right:5%;width:40%;">Warehouse Balance:</label><input type="text" id="txt_WarehouseGM" style="width:50%;">
				<label style="margin-right:5%;width:40%;">WIP Balance:</label><input type="text" id="txt_WIPGM" style="width:50%;">
				<label style="margin-right:5%;width:40%;">FGS Balance:</label><input type="text" id="txt_FGSGM" style="width:50%;">
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_Delete_Yes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modal_ImportPO" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Data Upload:</h4>
			</div>
			<form action="PORECEIVED-ImportToCSV.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<label>Please select a file to import:</label><input type="file" name="file" id="file">
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