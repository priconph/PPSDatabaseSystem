<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date('Y-m-d');
session_start();
//NOTES FOR CODING
/*
Add a validation to the selected Control Number, Load COntrol Number
first if the ctrl number is already existing in the database
*/
$getShipmentControlNumberList = PPSDBS::getInstance()->getShipmentControlNumberList();
echo "<datalist id='ControlNumberList'>";
echo '<option>-VIEW ALL-</option>';
while($rowControlNumberList = mysqli_fetch_array($getShipmentControlNumberList)) {
	echo '<option>'.$rowControlNumberList['ControlNumber'].'</option>';
}
echo "</datalist>";
// echo 'Under Maintenance';
// exit;
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var oTable = $('#tbl_InternalInvoiceMain').dataTable({
		"bProcessing": true,
		"bServerSide": true,
		"aaSorting"	 : [],
		"sAjaxSource"		: "./server_side_scripts/shipment_invoice.php"
	});
	
	//click functions
	$('#btn_AddItem').click(function(){
		window.location.href = "./addShipment.php";
	});
	
	$('#btn_ExportToPDF').click(function(){
		var controlNumber = $('#txt_ControlNumber').val();
		if(controlNumber == '-VIEW ALL-' || controlNumber == '') {
			alert('Please select control number.');
		} else {
			window.location.href = "./shipmentExportToPDF.php?controlNumber="+controlNumber;
		}
	});
	
	/* Updating Shipment */
	$('#tbl_InternalInvoiceMain tbody').on('dblclick', 'tr', function(){
		var id = $(this).find('td:eq(0) input').val();
		window.location.href = "./updateShipment.php?id="+id;
	});
	
	$('#btn_GenerateExcelReport').click(function(){
		$('#modal_generate_exel_report').modal('show');
	});
	
	$('#btn_GenerateExcelReportPerProduct').click(function(){
		$('#modal_generate_exel_report_per_product').modal('show');
	});
	
	$('#btn_generate_report_normal').click(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = "./shipmentExportToExcelDate.php?startDate="+startDate+"&endDate="+endDate;
	});
	
	$('#btn_generate_report_ypics').click(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = "./shipmentExportToExcelYPICS2.php?startDate="+startDate+"&endDate="+endDate;		
	});
	
	$('#btn_generate_report_per_product').click(function(){
		var itemcode = $('#txt_itemcode').val();
		window.location.href = "./shipmentExportToExcelProduct.php?itemcode="+itemcode;		
	});
	
});
</script>
</head>
<body>
<div class="panel panel-primary">
	<div class="panel-heading">INTERNAL INVOICE</div>
	<div class="panel-body">
		<div style="padding-bottom:20px;">
			<button id="btn_AddItem" type="button" class="btn btn-default glyphicon glyphicon-plus"> NEW INVOICE</button>
			<button id="btn_GenerateExcelReport" type="button" class="btn btn-default glyphicon glyphicon-download"> GENERATE REPORT</button>
			<button id="btn_GenerateExcelReportPerProduct" type="button" class="btn btn-default glyphicon glyphicon-download"> REPORT PER PRODUCT</button>
	    </div>		
		<table id="tbl_InternalInvoiceMain" class="table" border="1">
			<thead>
				<tr>
					<th>Ctrl #</th>
					<th>Pre-shipment Ctrl #</th>
					<th>Date</th>
					<th>Rev #</th>
					<th>Sold to</th>
					<th>Shipped By</th>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	</div>
</div>

	</div>
</div>

<?php
	if($_SESSION['date']==""){
		//echo "ginawa nya to!";
		if(isset($_POST['txtStartDate'])){
			$_SESSION['date'] = $_POST['txtStartDate'];
			$_SESSION['date_out'] = $_POST['txtEndDate'];
		}else{
			$_SESSION['date'] = $dateToday;
			$_SESSION['date_out'] = $dateToday;
		}
	}else{
		if($_POST['txtStartDate']!=""){
			$_SESSION['date'] = $_POST['txtStartDate'];
			$_SESSION['date_out'] = $_POST['txtEndDate'];
		}else{
			
		}
	}
	
	if(!isset($_POST['txt_ControlNumber'])) {
		$controlNumber = '';
	} else {
		$controlNumber = $_POST['txt_ControlNumber'];
	}
?>
<!--Modal-->
<div class="modal fade" tabindex="-1" role="dialog" id="modal_generate_exel_report">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Generate Report</h4>
      </div>
      <div class="modal-body">
		<form class="form-inline" role="form" method="POST" action="#" id="frm_generate_excel_report">
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
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btn_generate_report_normal">Generate (Report)</button>
		<button type="button" class="btn btn-success" id="btn_generate_report_ypics">Excel (YPICS)</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="modal_generate_exel_report_per_product">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Generate Report</h4>
      </div>
      <div class="modal-body">
		<form class="form-inline" role="form" method="POST" action="#" id="frm_generate_excel_report_per_product">
		  <div class="form-group">
			<label class="">Itemcode</label>
			<?php 
				echo '<input type="text" class="form-control" id="txt_itemcode">';
			?>
		  </div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="btn_generate_report_per_product">Generate (Report)</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div id="modal_AddInternalInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:650px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Internal Invoice</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:20%;">Control Number:</label><input type="text" id="txt_ControlNumberAM" style="width:20%;"><br>
				<label style="margin-right:5%;width:20%;">Date:</label><input type="date" id="txt_DateAM" style="width:20%;" value="<?php echo $dateToday; ?>">
				<label style="margin-right:5%;width:20%;">Rev No:</label><input type="text" id="txt_RevNoAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">Sold To:</label><input type="text" id="txt_SoldToAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">Shipped By:</label><input type="text" id="txt_ShippedByAM" style="width:20%;"><br><br>
				<label style="margin-right:5%;width:20%;">PRODUCT CODE:</label><input type="text" id="txt_ProductCodeAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">DESCRIPTION:</label><input type="text" id="txt_DescriptionAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">PO NUMBER:</label><input type="text" id="txt_PONumAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">QUANTITY:</label><input type="text" id="txt_QuantityAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">U/PRICE:</label><input type="text" id="txt_UPriceAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">TOTAL:</label><input type="text" id="txt_TotalAM" style="width:20%;">
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_AddII" class="btn btn-success">ADD</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>