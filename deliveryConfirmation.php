<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date('Y-m-d');
session_start();
$username = $_SESSION['username'];
//$username = "rona";
$getOrderNoList = PPSDBS::getInstance()->getOrderNoListDeliveryConfirmation();
echo "<datalist id='OrderNoList'>";
echo '<option>-VIEW ALL-</option>';
while($rowOrderNoList = mysqli_fetch_array($getOrderNoList)) {
	echo '<option>'.$rowOrderNoList['OrderNo'].'</option>';
}
echo "</datalist>";

function executeTable($getAllDeliveryConfirmation) {
	$itemCode = '';
	$ctr = 0;
	while($row = mysqli_fetch_array($getAllDeliveryConfirmation)) {
			$ActualSO_Total = 0;
			echo '<tr>';
				echo '<td>'.$row["ItemCode"].'</td>';
				echo '<td class="ItemName">'.$row["ItemName"].'</td>';
				echo '<td class="OrderNo">'.$row["OrderNo"].'</td>';
				echo '<td class="OrderBalance">'.$row["OrderBalance"].'</td>';
				echo '<td class="TargetSO">'.$row["TargetSO"].'</td>';
				echo '<td class="ShipmentDate">'.$row["ShipmentDate"].'</td>';
				$Variance = $row["TargetSO"] - $ActualSO_Total;
				if($row["ShipmentDate"] <= $dateToday && $row['Variance'] > 0){
					echo '<td style="color:red;" class="">'.$ActualSOLot.'</td>';
					echo '<td style="color:red;">test</td>';
				}else{
					$getSubValues = PPSDBS::getInstance()->getDeliveryConfirmation('orderno2', '','',$row['OrderNo']);
					while($row2 = mysqli_fetch_array($getSubValues)) {
						$ActualSO .= "[".$row2['ActualSO'] . "]" . $row2['LotNumber'] ."<br>";
						$ActualSO_Total += $row2['ActualSO']; 
						//$Variance += $row2["Variance"];
						$Variance = $row2["Variance"];
						// $Remarks .= $row2["Remarks"].", ";
						$Remarks .= $row2["Remarks"]." ";
					}
					//$Variance = $row["TargetSO"] - $ActualSO_Total;
					if($ActualSO == '[]<br>'){
						$ActualSO = "";
					}
					echo '<td class="">'.$ActualSO.'</td>';
					echo '<td>'.$Variance.'</td>';
					echo '<td>'.$Remarks.'</td>';
					$ActualSO = "";
					$Variance = "";
					$Remarks = "";
				}
				echo '<td class="WIP">'.$row["WIP"].'</td>';
				echo '<td class="FGS">'.$row["FGS"].'</td>';
				echo '<td class="actual_so" style="display:none;">'.$ActualSO_Total.'</td>';
			echo '</tr>';
	}
}
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<style>
#tbl_ShipmentConfirmationMain {
font-size:14px;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(1){
width:3%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(2){
width:10%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(3){
width:8%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(4){
width:2%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(5){
width:2%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(6){
width:2%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(7){
width:2%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(8){
width:3%;
}
#tbl_ShipmentConfirmationMain thead tr th:nth-child(9){
width:3%;
}
</style>
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
		var OrderNo = $(this).find('.OrderNo').html();
		var actual_so = $(this).find('.actual_so').html();
		//alert(actual_so);
		window.location.href = "./updateDeliveryConfirmation.php?OrderNo="+OrderNo+"&actual_so="+actual_so;
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
	$('#txtEndDate').focusout(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (endDate<startDate){
				$('#txtEndDate').val(startDate);  
			}
	});
	
	$('#btn_ExportToExcel').click(function () {
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = "./DELIVERYCONFIRMATION-ExportToExcel.php?startdate="+startDate+"&enddate="+endDate;
	});
	
	$('#btn_ExportToExcelShipment').click(function () {
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = "./DELIVERYCONFIRMATION-Shipment-ExportToExcel.php?startdate="+startDate+"&enddate="+endDate;
	});
	
	$('#btn_ExportToPDF').click(function () {
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = "./DELIVERYCONFIRMATION-ExportToPdf.php?startdate="+startDate+"&enddate="+endDate;
	});
	$('#btn_add_new_delivery').click(function(){
		$('#modal_add_PO').modal('show');
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
			if(!isset($_POST['txt_OrderNo'])) {
				$orderNo = '';
			} else {
				$orderNo = $_POST['txt_OrderNo'];
			}
		?>
		  <form class="form-inline" role="form" method="POST" action="#">
			  <label>ORDER NO: </label> <input type='text' class="form-control" id='txt_OrderNo' placeholder = '-Order No-' list='OrderNoList' autocomplete='off' name='txt_OrderNo' tabindex='0' value='<?php echo $orderNo; ?>'> 
			  <button type="submit" class="btn btn-default" id="btn_Select">Select</button><br><br>
		  </form>
		  
		  <div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-transfer" data-toggle="dropdown">
				IMPORT/EXPORT
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" id="btn_ImportCSV" class="glyphicon glyphicon-import">IMPORT CSV</a></li>
				<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
				<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL (by Date)</a></li>
				<li><a href="#" id="btn_ExportToExcelShipment" class="glyphicon glyphicon-export">EXPORT TO EXCEL (for Shipment)</a></li>
			</ul>
		  </div>
		  <button class="btn btn-primary" id="btn_add_new_delivery">Add Delivery</button>
		</div>	
		<table id="tbl_ShipmentConfirmationMain" class="table" border="1">
				<thead>
					<tr>
						<!-- <th>Date Issued</th>
						<th>Category</th>
						<th>Product P.O. No</th> -->
						<th>Item Code</th>
						<th>Item Name</th>
						<th>Order No.</th>
						<th>Order Balance</th>
						<th>Target for S/O</th>
						<th>Shipment Date</th>
						<th>Actual SO</th>
						<th>Variance</th>
						<th>Remarks</th>
						<th>WIP</th>
						<th>FGS</th>
						<th style="display:none;">FGS</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if($orderNo != "") {
							if($orderNo == '-VIEW ALL-') {
								echo "all mode";
								$getAllDeliveryConfirmation = PPSDBS::getInstance()->getDeliveryConfirmation('all', '','','');
								while($rowDelivery = mysqli_fetch_array($getAllDeliveryConfirmation)) {
									$getAllDeliveryConfirmation1 = PPSDBS::getInstance()->getDeliveryConfirmation('orderno', '','',$rowDelivery['OrderNo']);
									executeTable($getAllDeliveryConfirmation1);
								}
							} else {
								echo "order no mode";
								$getAllDeliveryConfirmation1 = PPSDBS::getInstance()->getDeliveryConfirmation('orderno', '','',$orderNo);
								executeTable($getAllDeliveryConfirmation1);
							}
						} else {
							echo "date mode";
							if(!isset($_POST['txtStartDate']) || !isset($_POST['txtEndDate'])) { 
								$getAllDeliveryConfirmation = PPSDBS::getInstance()->getDeliveryConfirmation('date',$dateToday,$dateToday,'');
								while($rowDelivery = mysqli_fetch_array($getAllDeliveryConfirmation)) {
									$getAllDeliveryConfirmation1 = PPSDBS::getInstance()->getDeliveryConfirmation('orderno', '','',$rowDelivery['OrderNo']);
									executeTable($getAllDeliveryConfirmation1);
								}
							} else {
								echo "excel mode";
								$getAllDeliveryConfirmation1 = PPSDBS::getInstance()->getDeliveryConfirmation('excel',$_POST['txtStartDate'], $_POST['txtEndDate'],'');
								executeTable($getAllDeliveryConfirmation1);
							}
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
			<form action="DELIVERYCONFIRMATION-ImportCSV.php" method="POST" enctype="multipart/form-data">
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

<!--Modal-->
<div id="modal_add_PO" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:350px;">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Add New Info:</h4>
			</div>			
			<div class="modal-body">
				<label style="width:100px;">Date Issued</label><input type="date" id="txt_date_issued" style="width:170px;">
				<label style="width:100px;">Category</label><input type="text" id="txt_category">
				<label style="width:100px;">Product PO No</label><input type="text" id="txt_product_pono">
				<label style="width:100px;">Item Code</label><input type="text" id="txt_itemcode">
				<label style="width:100px;">Item Name</label><input type="text" id="txt_itemname">
				<?php
					
				?>
				<label style="width:100px;">Order No</label><input type="text" id="txt_orderno">
				<label style="width:100px;">PO Balance</label><input type="text" id="txt_po_balance">
				<label style="width:100px;">Target for S/O</label><input type="text" id="txt_target_for_so">
				<label style="width:100px;">Shipment Date</label><input type="text" id="txt_shipment_date">
				<label style="width:100px;">Remarks</label><input type="text" id="txt_remarks">
			</div>
			<div class="modal-footer">
				   <input type="button" class="btn btn-success" value="SUBMIT">
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>