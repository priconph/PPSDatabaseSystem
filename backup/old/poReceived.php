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
#tbl_POReceivedMain {
font-size:10px;
}
#tbl_POReceivedMain thead tr th:nth-child(1){
width:1%;
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
width:2%;
}
#tbl_POReceivedMain thead tr th:nth-child(6){
width:2%;
}
#tbl_POReceivedMain thead tr th:nth-child(7){
width:2%;
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
#tbl_POReceivedMain thead tr th:nth-child(12){
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
	
	$('#btn_ForLoadingComputation').click(function(){
		window.location.href = "./generateForLoading.php"
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
	<div class="panel-heading" style="width:auto;overflow:hidden;">P.O. RECEIVE</div>
		<div class="panel-body" style="width:auto;">
			<?php
			if($_SESSION['date'] == "") {
				if(isset($_POST['txtStartDate']) && isset($_POST['txtEndDate'])) {
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				} else {
					$_SESSION['date'] = $dateToday;
					$_SESSION['date_out'] = $dateToday;
				}
			} else {
				if($_POST['txtStartDate'] != "") {
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				}
			}

			if(isset($_POST["txtOrderNo"])) {
				$orderNo = $_POST['txtOrderNo'];
			} else {
				$orderNo = "";
			}
			?>
			<form class="form-inline" role="form" method="POST" action="poReceived.php">
			  <div class="form-group" id="divStartDate">
				<label class="sr-only" for="txtStartDate">Start Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtStartDate" name="txtStartDate" placeholder="Start Date" value="'.$_SESSION['date'].'">';	
				?>
			  </div>
			  <div class="form-group" id="divEndDate">
				<label class="sr-only" for="txtEndDate">End Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtEndDate" name = "txtEndDate" placeholder="End Date" value="'.$_SESSION['date_out'].'">';
				?>
			  </div>
			  <button type="submit" class="btn btn-default" id="SetDateRange">SEARCH</button>
			</form>
			<form class="form-inline" role="form" method="POST" action="#">
			  <div class="form-group">
				<label for="txtOrderNo">ORDER NO: </label>
				<?php 
					echo '<input type="text" class="form-control" id="txtOrderNo" list="OrderNoList" autocomplete="off" name = "txtOrderNo" placeholder="-Order No-" value="'.$orderNo.'">';
				?>
				<button type="submit" class="btn btn-default" id="SetItemCode">SEARCH</button>
			  </div>
			</form>
				
			<?php
				$result = PPSDBS::getInstance()->loadNonExistingPOReceived();
				$badgeCtr = 0;
				while($row = mysqli_fetch_array($result)){
					$badgeCtr++;
				}
			?>
			<div class="btn-group">
			  <button id="btn_AddNewPO" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD NEW P.O. RECEIVE</button>
			  <div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-transfer" data-toggle="dropdown">
					IMPORT/EXPORT
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
				<!-- dropdown menu links -->
					<li><a href="#" id="btn_ImportCSV" class="glyphicon glyphicon-import">IMPORT CSV</a></li>
					<li><a href="#" id="btn_ImportTemplate" class="glyphicon glyphicon-download" onClick="window.location = '../media/download.php?filekeyword=import_po_received_template'">TEMPLATE for PO Receive Import</a></li>
					<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
					<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL</a></li>
				</ul>
			  </div>
			  <button id="btn_ResinComputation" type="button" class="btn btn-default glyphicon glyphicon-arrow-right">GENERATE RESIN PR</button>
			  <button id="btn_ForLoadingComputation" type="button" class="btn btn-default glyphicon glyphicon-arrow-right">GENERATE FOR LOADING</button>
			  <a href="./poReceivedNE.php"><button id="btn_NonExistingDieset" type="button" class="btn btn-default glyphicon glyphicon-tasks">DEVICE W/OUT DIE-SET &nbsp; <?php if($badgeCtr!=0){ echo'<span class="badge badge-error">'.$badgeCtr.'</span>';} ?></button></a>
			 
			</div>
			<table id="tbl_POReceivedMain" border="1">
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
						<th>DRAWING NO</th>
					</tr>
			</div>
				</thead>
				<tbody>
				<?php 
					if($orderNo != "") {
						if($orderNo == '-VIEW ALL-') {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('VIEWALL', $_POST['txtItemCode'], $dateToday,$dateToday);
						} else {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('orderno', $_POST['txtOrderNo'], $dateToday,$dateToday);
						}
					} else {
						if(!isset($_POST['txtStartDate']) || !isset($_POST['txtEndDate'])) {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('date', '', $dateToday,$dateToday);
						} else {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('date', '', $_POST['txtStartDate'],$_POST['txtEndDate']);
						}
					} 
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
							<td>".$row['DrawingNo']."</td>
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