<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$dateReset = '2000-01-01';
$username = $_SESSION['username'];
echo '<input type="hidden" id="hd_username">';
echo '<input type="date" id="txt_dateReset" style="display:none;" value="'.$dateReset.'">';
//REMINDER: MAG ADD KA NG DELETE NA AJAX SA MISMONG REMOVE
echo "<datalist id='InternalOrderNo'>";
$getInternalOrderNo = PPSDBS::getInstance()->getOrderNumberShipment('internal');
while($rowInternalOrderNo = mysqli_fetch_array($getInternalOrderNo)) {
	echo '<option>'.$rowInternalOrderNo['OrderNo'].'</option>';
}
echo "</datalist>";
echo "<datalist id='ExternalOrderNo'>";
$getExternalOrderNo = PPSDBS::getInstance()->getOrderNumberShipment('external');
while($rowExternalOrderNo = mysqli_fetch_array($getExternalOrderNo)) {
	echo '<option>'.$rowExternalOrderNo['OrderNo'].'</option>';
}
echo "</datalist>";
echo '<input type="hidden" id="hd_id" value="'.$_GET['id'].'">';

?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>

//nasa ilalim yung ibang script kase may niloload sya na base sa database tapos mag cocompute ng data basta yun na yun
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
</script>
</head>
<body>

	<div class="panel panel-primary" style="width:100%;margin:auto;">
	  <div class="panel-heading">ADD SHIPMENT</div>
	  <div class="panel-body">
		<div class="panel panel-primary">
			
			<div class="panel-body">
				
				<div class="row">
					<div class="col-sm-2">
						<label>Control Number:</label><br><br>
						<label>Date:</label><br><br>
						<datalist id="list_sold_to">
							<option>CN-YF</option>
							<option>CN-MIL</option>
							<option>CN-NFP</option>
							<option>CN-CSIC</option>
							<option>Burn in Sockets</option>
							<option>Grinding</option>
							<option>CN-137</option>
							<option>CN-FUS</option>
							<option>SIIX</option>
							<option>CN-FPS</option>
							<option>YF</option>
						</datalist>
						<label >Sold To:</label>
					</div>
					<div class="col-sm-4">
							<div class="row">
								<div class="col-sm-8">
									<input type="text" id="txt_ControlNumber" class="form-control" readOnly>
								</div>
								<div class="col-sm-1" style="padding-left:0px;padding-right:0px;paddin-top:10px;">
									<b>-</b>
								</div>
								<div class="col-sm-2" style="padding-left:0px;padding-right:0px;">
									<input type="text" id="txt_Grinding" class="form-control" maxlength="1">
								</div>
							</div>
						<input type="date" id="txt_Date" class="form-control">
						<input type="text" id="txt_SoldTo" class="form-control" list="list_sold_to">
					</div>
					<div class="col-sm-2">
						<label>Rev No:</label><br><br>
						<label>Shipped By:</label>
						<label style="padding-top:12px;">Preshipment Control No:</label>
					</div>
					<div class="col-sm-4">
						<input type="text" id="txt_RevNo" class="form-control" value="0">
						<input type="text" id="txt_ShippedBy" class="form-control" value="PPS">
						<input type="text" id="txt_PreshipCtrl" class="form-control" value="" disabled>
					</div>
				</div>
			</div>
		  </div>
		<div class="panel panel-primary">
			<div class="panel-body">
				<div class="btn-group">
				</div><br><br>
				<table id="tbl_Shipment" class="table table-striped" border="1">
					<thead>
						<tr>
							<!-- <th>CATEGORY</th>
							<th>PRODUCT PO#</th>-->
							<th>ITEM CODE</th>
							<th>ITEM NAME</th>
							<th>ORDER #</th>
							<th>SHIPOUT QTY</th>
							<th>UNIT PRICE($)</th>
							<th>AMOUNT($)</th>
							<th>LOT NO</th>
							<th>REMARKS</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				
				<div class="form-inline">
					<p style="text-align:right;">
						<label style="width:10%;">Grand Total:</label> <input type="text" class="form-control" id="txt_GrandTotal" value="0" readOnly>
					</p>
				</div>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<button id="btn_ExportToPDF" class="btn btn-info glyphicon glyphicon-print" style="width:10%;"> PDF</button>
				<button id="btn_ExportToExcelCN" class="btn btn-info glyphicon glyphicon-print" style="width:10%;"> Excel</button>
				<a href="./shipment_view.php"><button class="btn btn-danger" id="btn_Cancel" style="width:10%;">Close</button></a>
			</div>
		</div>
	  </div>
	</div>
	
<!--Modal-->
<div id="modal_AddInternalInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:scroll">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:500px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Internal Invoice</h4>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs" role="tablist">
				  <li class="active" id="li_InternalPO"><a href="#">Internal</a></li>
				  <li class="" id="li_ExternalPO"><a href="#">External</a></li>
				</ul>
				<div id="container_InternalPO">
					<label style="margin-right:5%;width:30%;">ORDER #:</label><input type="text" id="txt_OrderNoI" style="width:60%;" list='InternalOrderNo' autocomplete='off'><br>
					<label style="margin-right:5%;width:30%;">CATEGORY:</label><input type="text" id="txt_CategoryI" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">PRODUCT PO#:</label><input type="text" id="txt_ProductPOI" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">ITEM CODE:</label><input type="text" id="txt_ItemCodeI" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">ITEM NAME:</label><input type="text" id="txt_ItemNameI" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">SHIPOUT QTY:</label><input onkeypress="CheckNumeric()" type="text" id="txt_ShipoutQtyI" style="width:60%;" ><br>
					<label style="margin-right:5%;width:30%;">UNIT PRICE:</label><input onkeypress="CheckNumeric()" type="text" id="txt_UnitPriceI" style="width:60%;" ><br>
					<label style="margin-right:5%;width:30%;">AMOUNT:</label><input type="text" id="txt_AmountI" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">REMARKS #:</label><input type="text" id="txt_RemarksI" style="width:60%;"><br>
				</div>
				<div id="container_ExternalPO">
					<label style="margin-right:5%;width:30%;">ORDER #:</label><input type="text" id="txt_OrderNoE" style="width:60%;" list='ExternalOrderNo' autocomplete='off'><br>
					<label style="margin-right:5%;width:30%;">CATEGORY:</label><input type="text" id="txt_CategoryE" style="width:60%;" placeholder='' readOnly><br>
					<label style="margin-right:5%;width:30%;">PRODUCT PO#:</label><input type="text" id="txt_ProductPOE" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">ITEM CODE:</label><input type="text" id="txt_ItemCodeE" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">ITEM NAME:</label><input type="text" id="txt_ItemNameE" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">SHIPOUT QTY:</label><input onkeypress="CheckNumeric()" type="text" id="txt_ShipoutQtyE" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">UNIT PRICE:</label><input onkeypress="CheckNumeric()" type="text" id="txt_UnitPriceE" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">AMOUNT:</label><input type="text" id="txt_AmountE" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">REMARKS #:</label><input type="text" id="txt_RemarksE" style="width:60%;"><br>
				</div>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_AddData" class="btn btn-success">ADD</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>

<div id="loading-image"  style="width:100%;position:fixed;top:30%;"><center><img src="../media/loading_gifs/iron_man.gif"></center></div>

<!--Modal-->
<div id="modal_DeleteData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:scroll">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">DELETE</h4>
			</div>
			<div class="modal-body">
				<p>WARNING! DATA WILL BE PERMANENTLY DELETED!</p>
				<p>PROCEED?</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_Delete_Yes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modal_GenerateReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:scroll">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">GENERATE REPORT</h4>
			</div>
			<div class="modal-body">
				<?php
					echo '<label >Start Date:</label></label><input type="date" id="txt_DateDailyStart" style="width:50%;" value="'.$dateToday.'"><br>';
					echo '<label >End Date:</label></label><input type="date" id="txt_DateDailyEnd" style="width:50%;" value="'.$dateToday.'">';
				?>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_GenereteReportExcel" class="btn btn-success">GENERATE REPORT</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>

<div id="ModalImportShipmentCSV" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<!-- <form action="SHIPMENT-ImportCSV.php" method="POST" enctype="multipart/form-data"> -->
			<form id='data'>
				<div class="modal-body">
						<p><input type="file" name="file" id="file"></p>
				</div>
				<div class="modal-footer">
				   <input type="submit" class="btn btn-success" value="SUBMIT">
			</form>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
			<input type='text' id='hiddenControlNumber' style='display:none;'>
		</div>
	</div>
</div>

<div id="modal_system_message" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<div class="modal-body">
				<p>Data has been saved</p>
			</div>
			<div class="modal-footer">
				<a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modal_edit_preshipment_details" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Preshipment Information</h4>
      </div>
      <div class="modal-body">
        <div id="container_preship_input">
			<label style="margin-right:5%;width:30%;">ORDER #:</label><input type="text" id="txt_OrderNoP" style="width:60%;" list='InternalOrderNo' autocomplete='off' disabled><br>
			<label style="margin-right:5%;width:30%;">ITEM CODE:</label><input type="text" id="txt_ItemCodeP" style="width:60%;" disabled><br>
			<label style="margin-right:5%;width:30%;">ITEM NAME:</label><input type="text" id="txt_ItemNameP" style="width:60%;" disabled><br>
			<label style="margin-right:5%;width:30%;">SHIPOUT QTY:</label><input onkeypress="CheckNumeric()" type="text" id="txt_ShipoutQtyP" style="width:60%;" ><br>
			<label style="margin-right:5%;width:30%;">UNIT PRICE:</label><input onkeypress="CheckNumeric()" type="text" id="txt_UnitPriceP" style="width:60%;" ><br>
			<label style="margin-right:5%;width:30%;">AMOUNT:</label><input type="text" id="txt_AmountP" style="width:60%;" disabled><br>
			<label style="margin-right:5%;width:30%;">LOT NO:</label><input type="text" id="txt_LotNoP" style="width:60%;" disabled><br>
			<label style="margin-right:5%;width:30%;">REMARKS:</label><input type="text" id="txt_RemarksP" style="width:60%;"><br>
		</div>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btn_update_preship">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
<script>


/*
---------------------------------------------------------------
---------------------------------------------------------------
---------------------------------------------------------------
Rona,

Naka comment yung iba kase wala pa data para mag auto compute sa
on change functions pero inayos ko na ung pag aappend at saka yung 
ajax naka ready na ren, yung handler di pa.. baka kasi may inaayos ka
pa dun,

Meron lang din clarification di ko alam kung pede magkasama sa 
isang control yung internal po at external po sa ngaun pwede by 
default lalagyan lang naman ng if kung sakaling di pede.

Regards,	
----------------------------------------------------------------
----------------------------------------------------------------
----------------------------------------------------------------
*/
	
$('#loading-image').hide();
/* display a loading image */
$('#loading-image').bind('ajaxStart', function(){
    $(this).show();
}).bind('ajaxStop', function(){
    $(this).hide();
});

/* Pre-shipment Loading Function */
$(document).ready(function(){
	/* Load Invoice Information */
	load_invoice_main_information();
	
	var selected_row = '';
	/* Edit Pre-Shipment Information */
	$('#tbl_Shipment tbody').on('click','.glyphicon-edit',function(){
		selected_row = $(this).closest('tr');
		var ItemCode = selected_row.find('td:eq(0)').text();
		var ItemName = selected_row.find('td:eq(1)').text();
		var OrderNo = selected_row.find('td:eq(2)').text();
		var ShipQty = selected_row.find('td:eq(3)').text();
		var Price = selected_row.find('td:eq(4)').text();
		var Amount = selected_row.find('td:eq(5)').text();
		var LotNo = selected_row.find('td:eq(6)').text();
		var Remarks = selected_row.find('td:eq(7)').text();
		$('#txt_OrderNoP').val(OrderNo);
		$('#txt_ItemCodeP').val(ItemCode);
		$('#txt_ItemNameP').val(ItemName);
		$('#txt_ShipoutQtyP').val(ShipQty);
		$('#txt_UnitPriceP').val(Price);
		$('#txt_AmountP').val(Amount);
		$('#txt_LotNoP').val(LotNo);
		$('#txt_RemarksP').val(Remarks);
		$('#modal_edit_preshipment_details').modal('show');
	});
	
	/* Remove Pre-Shipment Information */
	$('#tbl_Shipment tbody').on('click','.glyphicon-remove',function(){
		selected_row = $(this).closest('tr');
		selected_row.remove();
		GrandTotal_New = 0;
		get_grand_total();
		$('#txt_GrandTotal').val(GrandTotal_New.toFixed(4));
	});
	
	/* computes new amount after typing new values in shipout qty or unit price */
	$('#txt_ShipoutQtyP,#txt_UnitPriceP').keyup(function(){
		var new_amount = ($('#txt_ShipoutQtyP').val() * $('#txt_UnitPriceP').val()).toFixed(4);
		$('#txt_AmountP').val(new_amount);
	});
	
	/* update table value */
	$('#btn_update_preship').click(function(){
		selected_row.find('td:eq(3)').text($('#txt_ShipoutQtyP').val());
		selected_row.find('td:eq(4)').text($('#txt_UnitPriceP').val());
		selected_row.find('td:eq(5)').text($('#txt_AmountP').val());
		selected_row.find('td:eq(7)').text($('#txt_RemarksP').val());
		$('#modal_edit_preshipment_details').modal('hide');
		GrandTotal_New = 0;
		get_grand_total();
		$('#txt_GrandTotal').val(GrandTotal_New.toFixed(4));
	});
	
	/* save updated preshipment */
	$('#btn_Save').click(function(){ 
		validate_empty_fields();
	});
	
	/* export to excel */
	$('#btn_ExportToExcelCN').click(function () {
		window.location.href = "./shipmentExportToExcel.php?ControlNumber="+Current_Ctrl;
	});
	
	/* export to pdf */
	$('#btn_ExportToPDF').click(function(){
		window.location.href = "./shipmentExportToPDF_view.php?controlNumber="+Current_Ctrl;
	});
	
});

/* loads the main information */
var id = $('#hd_id').val();
var Current_Ctrl = '';
function load_invoice_main_information(){
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: "action=load_invoice_main_information&id="+id,
		url		: 'ppsdbsHandler.php',
		success	: function(result){
			$('#txt_ControlNumber').val(result['ControlNumber']);
			$('#txt_ControlNumber').data('id', result['id']);
			$('#txt_Date').val(result['DateCreated']);
			$('#txt_SoldTo').val(result['SoldTo']);
			$('#txt_RevNo').val(result['RevNo']);
			$('#txt_ShippedBy').val(result['ShippedBy']);
			$('#txt_PreshipCtrl').val(result['preship_ctrno']);
			$('#txt_Grinding').val(result['forGrinding']);
			$('#txt_PreshipCtrl').data('id',result['id_tbl_PreShipment']);
			Current_Ctrl = result['Current_Ctrl'];
			/* Load preshipment details */
			load_preshipment_information_update(Current_Ctrl);
		}
	});
}

/* load preshipment data from shipment transactions */
function load_preshipment_information_update(ctrno){
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: "action=load_preshipment_information_update_view&ctrno="+ctrno,
		url		: 'ppsdbsHandler.php',
		success	: function(result){
			$('#tbl_Shipment tbody').empty();
			$('#tbl_Shipment tbody').append(result['html']);
			var GrandTotal = result['GrandTotal'].replace(",","");
			$('#txt_GrandTotal').val( GrandTotal );
		},error	: function(){
			alert('error handler');
		}
	});
}

/* Get the table grand total base on table records */
var GrandTotal_New = 0;
function get_grand_total(){
	$('#tbl_Shipment tbody tr').each(function(){
		GrandTotal_New = parseFloat(GrandTotal_New) + parseFloat( $(this).find('td:eq(5)').text() );
	});
}

/* validate empty main invoice information */
function validate_empty_fields(){
	if($("#txt_ControlNumber").val() == "" || $("#txt_RevNo").val() == "" ||  $("#txt_SoldTo").val() == "" || $("#txt_ShippedBy").val()  == "") {
		alert("Please fill out all required fields.");
		return false;
	}
	save_invoice_main_information();
}

/* save main invoice information */
function save_invoice_main_information(){
	var data = {
		 "action" : "update_main_invoice_information",
		 "id" 		: $("#txt_ControlNumber").data('id'),
		 "ControlNumber" : control_number(),
		 "Date" : $("#txt_Date").val(),
		 "RevNo" : $("#txt_RevNo").val(),
		 "SoldTo" : $("#txt_SoldTo").val(),
		 "ShippedBy" : $("#txt_ShippedBy").val(),
		 "GrandTotal" : $("#txt_GrandTotal").val(),
		 "Username" : $('#hd_username').val(),
		 "ctrno_preship" : $('#txt_PreshipCtrl').val()
	 }
	 
	 data = $.param(data);

	 $.ajax({
		 type:"post",
		 dataType:"json",
		 data:data,
		 url:"./ppsdbsHandler.php",
		 success:function(result){
			delete_all_current_item_in_invoice();
		 }
	});
}

/* Get Combined CtrlNo and Extension */
function control_number(CtrlNo){
	if($('#txt_Grinding').val() == ''){
		ControlNumber = $("#txt_ControlNumber").val();
	}else{
		ControlNumber = $("#txt_ControlNumber").val() + '-' + $("#txt_Grinding").val();
	}
	return ControlNumber;
}

/* remove current records before adding new values */
function delete_all_current_item_in_invoice(){
	$.ajax({
		 type:"post",
		 dataType:"json",
		 data:"action=delete_all_current_item_in_invoice&fkCtrl="+Current_Ctrl,
		 url:"./ppsdbsHandler.php",
		 success:function(result){
			save_invoice_details_information();
		 },error(){
			alert('error ajax!');
		 }
	 });
}

/* save invoice details information */
function save_invoice_details_information(){
	//Loop to all rows of table in internalinvoice
	var rec_list = {};
		rec_list.dateIssued = [];
		rec_list.orderNo = [];
		rec_list.shipOut = [];
		rec_list.unitPrice = [];
		rec_list.amount = [];
		rec_list.lotNo = [];
		rec_list.remarks = [];
		
	$("#tbl_Shipment tbody tr").each(function(){
		rec_list.orderNo.push($(this).find('td:eq(2)').text());
		rec_list.shipOut.push($(this).find('td:eq(3)').text());
		rec_list.unitPrice.push($(this).find('td:eq(4)').text());
		rec_list.amount.push($(this).find('td:eq(5)').text());
		rec_list.lotNo.push($(this).find('td:eq(6)').text());
		rec_list.remarks.push($(this).find('td:eq(7)').text());
	});
	
	var data = {
		"action" : "addNewRecordShipment_new",
		"controlNumber" : control_number(),
		"rec_list"		: rec_list,
		"Username" 		: "<?php echo $username; ?>"
	}
	
	data = $.param(data);
	 $.ajax({
		 type:"post",
		 dataType:"json",
		 data:data,
		 url:"./ppsdbsHandler.php",
		 success:function(result){
			alert('Data has been updated!');
		 },error(){
			alert('error ajax!');
		 }
	 });
}
</script>
</html>