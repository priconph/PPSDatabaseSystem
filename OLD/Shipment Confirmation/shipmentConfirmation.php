<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date('Y-m-d');
session_start();
$username = $_SESSION['username'];
$username = "rona";
$getShipmentControlNumber = PPSDBS::getInstance()->getAllShipmentConfirmationControlNumber();
echo "<datalist id='ControlNumberList'>";
echo '<option>-VIEW ALL-</option>';
while($rowControlNumberList = mysqli_fetch_array($getShipmentControlNumber)) {
	echo '<option>'.$rowControlNumberList['ControlNumber'].'</option>';
}
echo "</datalist>";
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var oTable = $('#tbl_ShipmentConfirmationMain').dataTable({
			paginate:false,
			"autoWidth": false
	});
	
	$('#btn_AddPR').click(function(){
		window.location.href = "./addShipmentConfirmation.php";
		//$('#modal_AddPR').modal('show');
	});
	
	$('#btn_ImportCSV').click(function(){
		$('#modal_ImportPO').modal('show');
	});
	
	$('#btn_AddAM').click(function(){
		$('#tbl_ShipmentConfirmationMain tbody').append(
		'<tr><td>'+$("#txt_PRNumberAM").val()+
		'</td><td>'+$("#txt_PartsCodeAM").val()+
		'</td><td>'+$("#txt_PartsNameAM").val()+
		'</td><td>'+$("#txt_ActualAM").val()+
		'</td><td>'+$("#txt_PriceAM").val()+
		'</td><td>'+$("#txt_TotalPriceAM").val()+
		'</td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>'); //APPEND NEW DATA INTO THE TABLE
	});
	
	$('#tbl_ShipmentConfirmationMain tbody tr').dblclick(function(){
		CtrlNo = $(this).find('.CtrlNo').html();
		window.location.href = "./updateShipmentConfirmation.php?PrNo="+CtrlNo;
		//alert(test);
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
	
	$('#btn_ExportToExcel').click(function () {
		var controlNumber = $('#txt_ControlNumber').val();
		if(controlNumber == '') {
			alert('Please select control number.');
		} else {
			window.location.href = "./SHIPMENTCONFIRMATION-ExportToExcel.php?ControlNumber="+controlNumber;
		}
		
	});
	
	$('#btn_ExportToPDF').click(function () {
		var controlNumber = $('#txt_ControlNumber').val();
		if(controlNumber == '') {
			alert('Please select control number.');
		} else {
			window.location.href = "./SHIPMENTCONFIRMATION-ExportToPdf.php?controlNumber="+controlNumber;
		}
	});
});
</script>
</head>
<body>
<div class="panel panel-primary">
	<div class="panel-heading">DELIVERY CONFIRMATION</div>
	<div class="panel-body">
		<div class="btn-group">
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
		?>
			<form class="form-inline" role="form" method="POST" action="#">
			  <div class="form-group">
				<label class="sr-only" for="txtStartDate">Start Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtStartDate" name="txtStartDate" placeholder="Start Date" value="'.$_SESSION['date'].'">';	
				?>
			  </div>
			  <div class="form-group">
				<label class="sr-only" for="txtEndDate">End Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtEndDate" name = "txtEndDate" placeholder="End Date" value="'.$_SESSION['date_out'].'">';
				?>
			  </div>
			  <button type="submit" class="btn btn-default" id="SetDateRange">SET DATE</button>
			</form> 
		<?php
			if(!isset($_POST['txt_ControlNumber'])) {
				$controlNumber = '';
			} else {
				$controlNumber = $_POST['txt_ControlNumber'];
			}
		?>
		  <form class="form-inline" role="form" method="POST" action="#">
			  <label>CONTROL NUMBER: </label> <input type='text' class="form-control" id='txt_ControlNumber' placeholder = '-Control Number-' list='ControlNumberList' autocomplete='off' name='txt_ControlNumber' tabindex='0' value='<?php echo $controlNumber; ?>'> 
			  <button type="submit" class="btn btn-default" id="btn_Select">Select</button><br><br>
		  </form>
		  
		  <button id="btn_AddPR" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD PR</button>
		  <div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-transfer" data-toggle="dropdown">
				IMPORT/EXPORT
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" id="btn_ImportCSV" class="glyphicon glyphicon-import">IMPORT CSV</a></li>
				<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
				<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL</a></li>
			</ul>
		  </div>
		</div>	
		<!-- <table id="tbl_ShipmentConfirmationMain" class="table" border="1">
			<thead>
				<tr>
					<th>YEC P.O.</th>
					<th>ITEM CODE</th>
					<th>NAME</th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</thead>
		</table> -->
		<table id="tbl_ShipmentConfirmationMain" class="table" border="1">
				<thead>
					<tr>
						<th>Control No.</th>
						<th>Date</th>
						<th>Customer</th>
						<th>Grand Total</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if($controlNumber != "") {
							if($controlNumber == '-VIEW ALL-') {
								$getAllShipmentConfirmation = PPSDBS::getInstance()->viewAllShipmentConfirmation();
							} else {
								$getAllShipmentConfirmation = PPSDBS::getInstance()->viewShipmentConfirmationInfo($controlNumber);
							}
						} else {
							if(!isset($_POST['txtStartDate']) || !isset($_POST['txtEndDate'])) { 
								$getAllShipmentConfirmation = PPSDBS::getInstance()->viewShipmentConfirmation($dateToday,$dateToday);
							} else {
								$getAllShipmentConfirmation = PPSDBS::getInstance()->viewShipmentConfirmation($_POST['txtStartDate'], $_POST['txtEndDate']);
							}
						}
						while($row = mysqli_fetch_array($getAllShipmentConfirmation)) {
							echo '<tr>
									<td class="CtrlNo">'.$row['ControlNumber'].'</td>
									<td>'.$row['Date'].'</td>
									<td>'.$row['Customer'].'</td>
									<td>'.$row['Total'].'</td>
								</tr>';
						}
					?>
				</tbody>
		</table> 
	</div>
</div>


<!--Modal-->
<div id="modal_AddPR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">PR Information</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:30%;">PR Number:</label><input type="text" id="txt_PRNumberAM" style="width:65%;"><br>
				<label style="margin-right:5%;width:30%;">Parts Code:</label><input type="text" id="txt_PartsCodeAM" style="width:65%;"><br>
				<label style="margin-right:5%;width:30%;">Parts Name:</label><input type="text" id="txt_PartsNameAM" style="width:65%;"><br>
				<label style="margin-right:5%;width:30%;">Actual:</label><input type="text" id="txt_ActualAM" style="width:65%;"><br>
				<label style="margin-right:5%;width:30%;">Price:</label><input type="text" id="txt_PriceAM" style="width:65%;"><br>
				<label style="margin-right:5%;width:30%;">Total Price:</label><input type="text" id="txt_TotalPriceAM" style="width:65%;"><br>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_AddAM" class="btn btn-success">ADD</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
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
			<form action="SHIPMENTCONFIRMATION-ImportCSV.php" method="POST" enctype="multipart/form-data">
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