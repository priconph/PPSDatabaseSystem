<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$dateReset = '2000-01-01';
session_start();
$username = $_SESSION['username'];
echo '<input type="hidden" id="hd_username" value="'.$username.'">';
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
$ControlNumberQuery = PPSDBS::getInstance()->getControlNumber();
// if($rowControlNumber = mysqli_fetch_array($ControlNumberQuery)) {
// 	$ControlNumber = $rowControlNumber['ControlNumber'];
// 	if($ControlNumber == null) {
// 		$ControlNumber = "PPS-".date("Ym")."-0001";
// 	} else {
// 		$ctr = substr($ControlNumber, 11, 15) + 1;
// 		if($ctr > 1 && $ctr < 10) {
// 			$ctr = "000".$ctr;
// 		} else if($ctr > 9 && $ctr < 100) {
// 			$ctr = "00".$ctr;
// 		} else if($ctr > 99 && $ctr < 1000) {
// 			$ctr = "0".$ctr;
// 		} else if($ctr > 999 && $ctr < 10000 ) {
// 			$ctr = $ctr;
// 		}
// 		$ControlNumber = "PPS-".date("Ym")."-".$ctr;
// 	}
// }

	if ($rowControlNumber = mysqli_fetch_array($ControlNumberQuery)) {
		$ControlNumber = $rowControlNumber['ControlNumber'];

		if ($ControlNumber == null) {
			// $ControlNumber = "PPS-" . date("Ym") . "-0001";
			$ControlNumber = "PPS-" . date("Ym") . "- *Auto Generated*";
		} else {
			$ctr = (int)substr($ControlNumber, 11) + 1;

			$ctr = str_pad($ctr, 5, "0", STR_PAD_LEFT);

			// $ControlNumber = "PPS-" . date("Ym") . "-" . $ctr;
			$ControlNumber = "PPS-" . date("Ym") . "- *Auto Generated*";
		}
	}//  CHAN - 10-23-2025
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
		<div class="row">
			<div class="col-sm-offset-6 col-sm-6" style="margin-bottom:10px;">
				<div class="form-inline">
					<p style="text-align:right;">
						<label>Pre-Shipment Ctrl# :</label>
						<datalist id="list_preship_ctrl"></datalist>
						<input type="text" class="form-control" id="txt_preshipment_ctrl" list="list_preship_ctrl">
						<button class="btn btn-primary" id="btn_load_preshipment_list_data">Load</button>
					</p>
				</div>
			</div>
		</div>
				
		<div class="panel panel-primary">
			
			<div class="panel-body">
				
				<div class="row">
					<div class="col-sm-2">
						<label>Control Number:</label><br><br>
						<label>Date:</label><br><br>
						<!-- <datalist id="list_sold_to"> -->
							<!-- <option>CN-YF</option>
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
							<option>CN</option>
							<option>CN - Flexicon</option>
							<option>CN - Connectors</option>
							<option>CN - Card Connector</option>
							<option>CN - FPC Connector</option>
							<option>CN - IC Sockets</option>
							<option>CN - FAKRA/HSD Connector</option> -->
						<!-- </datalist> -->
						<label >Sold To:</label>
					</div>
					<div class="col-sm-4">
						<!-- <input type="text" id="txt_ControlNumber" class="form-control" value="<?php echo $ControlNumber; ?>" readOnly> -->
						<input type="text" id="txt_ControlNumber" class="form-control" value="<?php echo $ControlNumber; ?>" readOnly>
						<input type="date" id="txt_Date" class="form-control" value="<?php echo $dateToday; ?>">
						<!-- <input type="text" id="txt_SoldTo" class="form-control" list="list_sold_to"> -->
						 <select name="" id="txt_SoldTo" class="form-control"></select>
					</div>
					<div class="col-sm-2">
						<label>Rev No:</label><br><br>
						<label>Shipped By:</label>
						<label style="padding-top:12px;">Preshipment Control No:</label>
					</div>
					<div class="col-sm-4">
						<input type="text" id="txt_RevNo" class="form-control" value="0">
						<input type="text" id="txt_ShippedBy" class="form-control" value="PPS">
						<input type="text" id="txt_PreshipCtrl" class="form-control" value="">
					</div>
				</div>
				<div class="row" style="padding-top:3px;">
					<div class="col-sm-2">
						<label>Sales Cut-off:</label>
					</div>
					<div class="col-sm-4">
						<select id="txt_sales_cutoff" class="form-control">
							<option value="-">-</option>
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
					</div>
				</div>
			</div>
		  </div>
		<div class="panel panel-primary">
			<div class="panel-body">
				<?php
					//echo '<label >Date:</label><input type="date" id="txt_Date" value="'.$dateToday.'" >';
				?>
				<div class="btn-group">
				  <button id="btn_AddItem" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD PO</button>
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
							<th>(X)</th>
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
				<button id="btn_Save" class="btn btn-success" style="width:10%;">Save!</button>
				<?php
				// if($username == 'cpagtalunan' || $username == 'cbretusto')
					// echo '<button id="btn_Save_new" class="btn btn-success" style="width:10%;">Save New</button>';
				?>
				<a href="./shipment.php"><button class="btn btn-danger" id="btn_Cancel" style="width:10%;">Cancel</button></a>
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
let saleCutoff = null;
$('document').ready(function(){
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
	//Tab for modal internal and external po
	$('#container_ExternalPO').hide(); //hide external category
	var POChecker = 0;
	$('#li_InternalPO').click(function(){
		POChecker = 0;
		hideInternalandExternalPO(); //call function to reset tab settings
		$('#container_InternalPO').show(); //show external po
		$('#li_InternalPO').attr('class','active'); //add an active class to externalPO
	});
	$('#li_ExternalPO').click(function(){
		POChecker = 1;
		hideInternalandExternalPO(); //call function to reset tab settings
		$('#container_ExternalPO').show(); //show external po
		$('#li_ExternalPO').attr('class','active'); //add an active class to externalPO
	});
	
	//some variables
	var ControlNoG=0;
	//change functions
	
	/*
	$("#txt_QuantityAM").change(function(){
		$("#txt_TotalAM").val($("#txt_QuantityAM").val()*$('#txt_UPriceAM').val());
	});
	$("#txt_UPriceAM").change(function(){
		$("#txt_TotalAM").val($("#txt_QuantityAM").val()*$('#txt_UPriceAM').val());
	});
	//click functions
	*/	
	$('#btn_AddItem').click(function(){
		$('#modal_AddInternalInvoice').modal('show');
	});
	
	var totalPrice = 0;
	var row=0;
	
	$('#btn_AddData').click(function(){ //open modal and append a row
		if($('#txt_ShipoutQtyI').val() == '0' || $('#txt_ShipoutQtyE').val() == '0') {
			alert('No quantity entered.');
			$('#txt_QuantityAM').focus();
		} else if($('#txt_AmountI').val() == "" && $('#txt_AmountE').val() == "") {
			alert('Invalid Order No.');
			$('#txt_OrderNoI').focus();
		} else {
			var ctr = 0;
			
			$('#tbl_Shipment tr').each(function(row, tr){
				if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
					//GET VALUES HERE
					ctr =  parseInt($(tr).find('td:eq(0)').text());
				}
				ctr = parseInt(ctr) + parseInt(1);
				row++;	
			});
			
			if(POChecker==0){
				/* add an internal po */
				var amt = parseFloat($("#txt_ShipoutQtyI").val()) * parseFloat($("#txt_UnitPriceI").val());
					amt = amt.toFixed(4);
				/* create a row */
				var html  = '<tr>';
					html += '	<td>' + $("#txt_ItemCodeI").val() + '</td>';
					html += '	<td>' + $("#txt_ItemNameI").val() + '</td>';
					html += '	<td>' + $("#txt_OrderNoI").val() + '</td>';
					html += '	<td>' + $("#txt_ShipoutQtyI").val() + '</td>';
					html += '	<td>' + parseFloat($("#txt_UnitPriceI").val()).toFixed(4) + '</td>';
					html += '	<td>' + parseFloat(amt.replace("$","")).toFixed(4) + '</td>';
					html += '	<td></td>';
					html += '	<td>' + $("#txt_RemarksI").val() + '</td>';
					html += '	<td><a href="#">Remove</a></td>';
					html += '</tr>';
				/* append new data into the table */
				$('#tbl_Shipment tbody').append(html);
			}else{
				/* add an external po */
				var amt = $("#txt_AmountE").val();
					amt = amt.toFixed(4);
				/* create a row */
				var html  = '<tr>';
					html += '	<td>' + $("#txt_ItemCodeE").val() + '</td>';
					html += '	<td>' + $("#txt_ItemNameE").val() + '</td>';
					html += '	<td>' + $("#txt_OrderNoE").val() + '</td>';
					html += '	<td>' + $("#txt_ShipoutQtyE").val() + '</td>';
					html += '	<td>' + parseFloat($("#txt_UnitPriceE").val()).toFixed(4) + '</td>';
					html += '	<td>' + parseFloat(amt.replace("$","")).toFixed(4) + '</td>';
					html += '	<td></td>';
					html += '	<td>' + $("#txt_RemarksE").val() + '</td>';
					html += '	<td><a href="#">Remove</a></td>';
					html += '</tr>';
				/* append new data into the table */
				$('#tbl_Shipment tbody').append(html);
			}
			
			/*ctr++;*/
			totalPrice = parseFloat(parseFloat($("#txt_GrandTotal").val()) + parseFloat($("#txt_ShipoutQtyE").val() * $("#txt_UnitPriceE").val()) + parseFloat($("#txt_ShipoutQtyI").val() * $("#txt_UnitPriceI").val())).toFixed(4);
			parseFloat($('#txt_GrandTotal').val(totalPrice)).toFixed(4);
			
			$("#txt_CategoryI").val('');
			$("#txt_ProductPOI").val('');
			$("#txt_ItemCodeI").val('');
			$("#txt_ItemNameI").val('');
			$("#txt_OrderNoI").val('');
			$("#txt_ShipoutQtyI").val('');
			$("#txt_UnitPriceI").val(0);
			$("#txt_UnitPriceI").val(0);
			$("#txt_AmountI").val('');
			$("#txt_RemarksI").val('');
			$("#txt_CategoryI").focus();
			
			$("#txt_CategoryE").val('');
			$("#txt_ProductPOE").val('');
			$("#txt_ItemCodeE").val('');
			$("#txt_ItemNameE").val('');
			$("#txt_OrderNoE").val('');
			$("#txt_ShipoutQtyE").val('');
			$("#txt_UnitPriceE").val(0);
			$("#txt_UnitPriceE").val(0);
			$("#txt_AmountE").val('');
			$("#txt_RemarksE").val('');
			$("#txt_CategoryE").focus();
		}
	});
	
	// $('#txt_SoldTo').keyup(function() {
		// if($(this).val() != '') { 
			return_sold_to_list('txt_SoldTo', $(this).val());
		// }
	// });
	
	function return_sold_to_list(list_id, pattern) {		
		var data = {
			"action"	: "return_sold_to_list",
			"pattern"	: pattern
		}
		data = $.param(data);
		$.ajax({
			type 	 : "post",
			dataType : "json",
			data	 : data,
			url		 : "./ppsdbsHandler.php",
			success	: function(result) {
				$('#'+list_id).empty();
				$('#'+list_id).append(result['html_select']);
			}
			
		});
	}
	
	var ControlNoG=0;
	// $('#btn_Save').click(function(){
	// 	var dateToday = '<?php echo $dateToday; ?>';

	// 	if($("#txt_ControlNumber").val() == "" || $("#txt_RevNo").val() == "" ||  $("#txt_SoldTo").val() == "" || $("#txt_ShippedBy").val()  == "") {
	// 		alert("Please fill out all required fields.");
	// 	} else {
	// 		if(confirm("Is this for grinding?")){
	// 			var ControlNum_New = $("#txt_ControlNumber").val()+"-G";
	// 		}else{	
	// 			var ControlNum_New = $("#txt_ControlNumber").val();
	// 		}
			 
	// 		 var data = {
	// 			 "action" : "addNewRecordShipmentInvoice",
	// 			 "ControlNumber" : ControlNum_New,
	// 			 "Date" : $("#txt_Date").val(),
	// 			 "RevNo" : $("#txt_RevNo").val(),
	// 			 "SoldTo" : $("#txt_SoldTo").val(),
	// 			 "ShippedBy" : $("#txt_ShippedBy").val(),
	// 			 "GrandTotal" : $("#txt_GrandTotal").val(),
	// 			 "Username" : $('#hd_username').val(),
	// 			 "ctrno_preship" : $('#txt_PreshipCtrl').val()
	// 		 }
			 
	// 		 ControlNoG = data['ControlNumber'];
			 
	// 		 data = $.param(data);
	// 		 $.ajax({
	// 			 type:"post",
	// 			 dataType:"json",
	// 			 data:data,
	// 			 url:"./ppsdbsHandler.php",
	// 			 success:function(result){		
	// 				 if(result["msg"] == "Control Number already exists!") {
	// 					 alert(result["msg"]);
	// 				 } else {
	// 					if($('#txt_GrandTotal').val() == 0) {
	// 						alert("No item added!");
	// 					} else {
							
	// 						//Loop to all rows of table in internalinvoice
	// 						var rec_list = {};
	// 							rec_list.dateIssued = [];
	// 							rec_list.orderNo = [];
	// 							rec_list.shipOut = [];
	// 							rec_list.unitPrice = [];
	// 							rec_list.amount = [];
	// 							rec_list.lotNo = [];
	// 							rec_list.remarks = [];
								
	// 						$("#tbl_Shipment tbody tr").each(function(){
	// 							totalPrice = parseFloat(totalPrice) + parseFloat($(this).find('td:eq(6)').text());
	// 							rec_list.orderNo.push($(this).find('td:eq(2)').text());
	// 							rec_list.shipOut.push($(this).find('td:eq(3)').text());
	// 							rec_list.unitPrice.push($(this).find('td:eq(4)').text());
	// 							rec_list.amount.push($(this).find('td:eq(5)').text());
	// 							rec_list.lotNo.push($(this).find('td:eq(6)').text());
	// 							rec_list.remarks.push($(this).find('td:eq(7)').text());
	// 						});
							
	// 						var data = {
	// 							"action" : "addNewRecordShipment_new",
	// 							"controlNumber" : ControlNum_New,
	// 							"dateIssued" 	: dateToday,
	// 							"rec_list"		: rec_list,
	// 							"Username" 		: "<?php echo $username; ?>"
	// 						}
							
	// 						data = $.param(data);
							
	// 						 $.ajax({
	// 							 type:"post",
	// 							 dataType:"json",
	// 							 data:data,
	// 							 url:"./ppsdbsHandler.php",
	// 							 success:function(result){
	// 								alert(result['msg']);
	// 								window.location.href = "shipment.php";
	// 							 },error(){
	// 								alert('error ajax!');
	// 							 }
	// 						 });
							 
	// 					}
	// 				 }
	// 			 }
	// 		 });
	// 		return false;
	// 	} 
	// });

	$('#btn_Save').click(function(){
		var dateToday = '<?php echo $dateToday; ?>';
		var isGrinding = false;
		if($("#txt_ControlNumber").val() == "" || $("#txt_RevNo").val() == "" ||  $("#txt_SoldTo").val() == "" || $("#txt_ShippedBy").val()  == "") {
			alert("Please fill out all required fields.");
		} else {
			if(confirm("Is this for grinding?")){
				// var ControlNum_New = $("#txt_ControlNumber").val()+"-G";
				isGrinding = true;
			}
			 
			 var data = {
				 "action" : "addNewRecordShipmentInvoice",
				//  "ControlNumber" : ControlNum_New,
				 "Date" : $("#txt_Date").val(),
				 "RevNo" : $("#txt_RevNo").val(),
				 "SoldTo" : $("#txt_SoldTo").val(),
				 "ShippedBy" : $("#txt_ShippedBy").val(),
				 "GrandTotal" : $("#txt_GrandTotal").val(),
				 "Username" : $('#hd_username').val(),
				 "ctrno_preship" : $('#txt_PreshipCtrl').val(),
				 "isGrinding" : isGrinding,
				 "saleCutoff" : saleCutoff
			 }
			 
			 ControlNoG = data['ControlNumber'];
			 
			 data = $.param(data);
			 $.ajax({
				 type:"post",
				 dataType:"json",
				 data:data,
				 url:"./ppsdbsHandler.php",
				 success:function(result){		
					if(result["msg"] == "Control Number already exists!") {
						alert(result["msg"]);
					} else {
						if($('#txt_GrandTotal').val() == 0) {
							alert("No item added!");
						} else {
							
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
								totalPrice = parseFloat(totalPrice) + parseFloat($(this).find('td:eq(6)').text());
								rec_list.orderNo.push($(this).find('td:eq(2)').text());
								rec_list.shipOut.push($(this).find('td:eq(3)').text());
								rec_list.unitPrice.push($(this).find('td:eq(4)').text());
								rec_list.amount.push($(this).find('td:eq(5)').text());
								rec_list.lotNo.push($(this).find('td:eq(6)').text());
								rec_list.remarks.push($(this).find('td:eq(7)').text());
							});
							
							var data = {
								"action" : "addNewRecordShipment_new",
								"controlNumber" : result['ctrl_no'],
								"dateIssued" 	: dateToday,
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
									alert(result['msg']);
									window.location.href = "shipment.php";
									},error(){
									alert('error ajax!');
									}
								});
								
						}
					}
				 }
			 });
			return false;
		} 
	});
	
	totalPrice = 0; //reset current total price
	row = 0; //RESET ROW COUNT
	//FUNCTION FOR REMOVING A ROW INTO THE TABLE 
	$("#tbl_Shipment").on('click','.remCF',function(){ //TABLE ID = tblMain AND TABLE CLASS = remCF
		$(this).parent().parent().remove(); //REMOVES THE ROW
		//RECOMPUTE TOTAL AFTER REMOVING A ROW
		totalPrice = 0;
		$('#tbl_InternalInvoiceMain tr').each(function(row, tr){
			if(row>0){
				totalPrice = parseFloat(totalPrice) + parseFloat($(tr).find('td:eq(5)').text());
			}
			row++;
		});
		$('#txt_GrandTotal').val(totalPrice.toFixed(4));
	});
	

	$('#txt_OrderNoI').change(function () {
		var orderNo = $('#txt_OrderNoI').val();
		var amount = 0;
		var data = {
			"action" : "exportShipmentRecord",
			"OrderNo" : orderNo
		}
		data = $.param(data);
		$.ajax({
			type : "POST",
			dataType : "json",
			url: "ppsdbsHandler.php",
			data: data,
			success: function(result) {
				
				//amount = parseFloat(result['ActualSO'] * result['Price']).toFixed(4);
				amount = parseFloat(result['ActualSO'] * result['Price']).toFixed(2);
				$('#txt_CategoryI').val(result['Category']);
				$('#txt_ProductPOI').val(result['ProductPONo']);
				$('#txt_ItemCodeI').val(result['ItemCode']);
				$('#txt_ItemNameI').val(result['ItemName']);
				$('#txt_ShipoutQtyI').val(result['ActualSO']);
				$('#txt_UnitPriceI').val(result['Price']);
				$('#txt_AmountI').val("$" + amount);
				$('#txt_RemarksI').val(result['Remarks']);
				if(amount == "NaN") {
					$('#txt_AmountI').val("");
				}
			}
		});
	});
	
	$('#txt_AmountI').change(function () {
		//alert('alerts');
	});
	
	$('#txt_OrderNoE').change(function () {
		var orderNo = $('#txt_OrderNoE').val();
		var amount = 0;
		var data = {
			"action" : "exportShipmentRecord",
			"OrderNo" : orderNo
		}
		data = $.param(data);
		$.ajax({
			type : "POST",
			dataType : "json",
			url: "ppsdbsHandler.php",
			data: data,
			success: function(result) {
				amount = parseFloat(result['ActualSO'] * result['Price']).toFixed(4);
				$('#txt_CategoryE').val(result['Category']);
				$('#txt_ProductPOE').val(result['ProductPONo']);
				$('#txt_ItemCodeE').val(result['ItemCode']);
				$('#txt_ItemNameE').val(result['ItemName']);
				$('#txt_ShipoutQtyE').val(result['ActualSO']);
				$('#txt_UnitPriceE').val(result['Price']);
				$('#txt_AmountE').val("$" + amount);
				$('#txt_RemarksE').val(result['Remarks']);
				if(amount == "NaN") {
					$('#txt_AmountE').val("");
				}
			}
		});
	});
	
	$('#btn_ImportCSV').click(function () {
		$('#hiddenControlNumber').val($('#txt_ControlNumber').val());
		var ControlNumber = $('#txt_ControlNumber').val();
		if(ControlNumber == '') {
			alert('Invalid Control Number!');
		} else {
			//check if the Control number already exists.
			$('#ModalImportShipmentCSV').modal('show');
		}
	});
	
	$('form#data').submit(function (event) {
		//disable the default form submission
		event.preventDefault();
		
		//grab all form data
		var formData = new FormData($(this)[0]);
		var ctr = 0;
		$.ajax({
			url : './SHIPMENT-ImportCSV.php',
			type: 'POST',
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			processData: false,
			success: function (result) {
				$("#tbl_Shipment tbody").append(result);
				$('#tbl_Shipment tr').each(function(row, tr){
					row++;	
					if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
						//GET VALUES HERE
						var str = $(tr).find('td:eq(5)').text();
						if(str.replace(',','') != '') {
							ctr =  parseFloat(ctr) + 0 + parseFloat(str.replace(',',''));
						} 
					}
				});
				parseFloat($('#txt_GrandTotal').val(ctr));
			}
		});
	});
	
});

function getProductCodeInfo() {
	var data = {
			"action" : "getItemCodeInfoByItemCodeReal",
			"ItemCode" : $("#txt_ProductCodeAM").val()
		}
		data = $.param(data);
		$.ajax({
			type:"post",
			dataType:"json",
			data:data,
			url:"./ppsdbsHandler",
			success:function(result){	
				$("#txt_DescriptionAM").val(result['Desc']);
				$("#txt_PONumAM").val(result['PONum']);
				$("#txt_UPriceAM").val(result['UnitPrice']);
			}
		});
}

function hideInternalandExternalPO(){
	$('#container_InternalPO').hide();
	$('#li_InternalPO').attr('class','');
	$('#container_ExternalPO').hide();
	$('#li_ExternalPO').attr('class','');
}
$('#loading-image').hide();
/* display a loading image */
$('#loading-image').bind('ajaxStart', function(){
    $(this).show();
}).bind('ajaxStop', function(){
    $(this).hide();
});

/* Pre-shipment Loading Function */
$(document).ready(function(){
	/* initial loading of preshipment control no */
	load_preshipment_control_no('');
	/* Change the recommended control as text pre shipment changes */
	$('#txt_preshipment_ctrl').keypress(function(){
		load_preshipment_control_no($(this).val());
	});
	
	/* Append Pre-Shipment Data to table */
	$('#btn_load_preshipment_list_data').click(function(){
		var ctrno = $('#txt_preshipment_ctrl').val();
		load_preshipment_information(ctrno);
		$('#txt_PreshipCtrl').val(ctrno);
	});
	
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
	
	$('#txt_ShipoutQtyP,#txt_UnitPriceP').keyup(function(){
		var new_amount = ($('#txt_ShipoutQtyP').val() * $('#txt_UnitPriceP').val()).toFixed(4);
		$('#txt_AmountP').val(new_amount);
	});

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
	
});

function load_preshipment_control_no(pattern){
	var data = {
		"action"	: "load_preshipment_control_no",
		"pattern"	: pattern
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: 'ppsdbsHandler.php',
		success	: function(result){
			$('#list_preship_ctrl').empty();
			$('#list_preship_ctrl').append(result['html']);
		},error	: function(){
			alert('error handler');
		}
	});
}

function load_preshipment_information(ctrno){
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: "action=load_preshipment_information&ctrno="+escape(ctrno),
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

var GrandTotal_New = 0;
function get_grand_total(){
	$('#tbl_Shipment tbody tr').each(function(){
		GrandTotal_New = parseFloat(GrandTotal_New) + parseFloat( $(this).find('td:eq(5)').text() );
	});
}
var month 	= new Date().getMonth() + 1;

$('#txt_sales_cutoff').change(function() {
	var ctrl_no = $('#txt_ControlNumber').val();
	var series 	= ctrl_no.split('-').pop();
	var year 	= new Date().getFullYear();
	var month 	= $(this).val();
	// var ctrl_no_new = "PPS-"+year+month+"-"+series;
	var ctrl_no_new = "PPS-"+year+month+"- *Auto Generated*";
	$('#txt_ControlNumber').val(ctrl_no_new);
	saleCutoff = year+month;
});
</script>
</html>