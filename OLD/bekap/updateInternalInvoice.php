<?php
require_once('./includes.php');
$dateToday = date("Y-m-d");
$dateReset = '2000-01-01';
echo '<input type="date" id="txt_dateReset" style="display:none;" value="'.$dateReset.'">';
//REMINDER: MAG ADD KA NG DELETE NA AJAX SA MISMONG REMOVE
$CtrlNo = $_GET['CtrlNo'];
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
	  <div class="panel-heading">ADD FGS RECORD</div>
	  <div class="panel-body">
		<div class="panel panel-primary">
			<div class="panel-body">
				<label style="margin-right:5%;width:20%;">Control Number:</label><input type="text" id="txt_ControlNumber" style="width:20%;"><br>
				<label style="margin-right:5%;width:20%;">Date:</label><input type="date" id="txt_Date" style="width:20%;" value="<?php echo $dateToday; ?>">
				<label style="margin-right:5%;width:20%;">Rev No:</label><input type="text" id="txt_RevNo" style="width:20%;">
				<label style="margin-right:5%;width:20%;">Sold To:</label><input type="text" id="txt_SoldTo" style="width:20%;">
				<label style="margin-right:5%;width:20%;">Shipped By:</label><input type="text" id="txt_ShippedBy" style="width:20%;">
			</div>
		  </div>
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<?php
					//echo '<label style="margin-right:5%;width:20%;">Date:</label><input type="date" id="txt_Date" value="'.$dateToday.'" style="width:20%;">';
				?>
				<div class="btn-group">
				  <button id="btn_AddItem" type="button" class="btn btn-default">ADD ITEM</button>
				  <button id="btn_ExportToExcel" type="button" class="btn btn-default">EXPORT TO EXCEL</button>
				  <button id="btn_ExportToExcel" type="button" class="btn btn-default">EXPORT TO PDF</button>
				</div><br><br>
				<table id="tbl_InternalInvoiceMain" class="table" border="1">
					<thead>
						<tr>
							<th>Item #</th>
							<th>PRODUCT CODE</th>
							<th>DESCRIPTION</th>
							<th>PO Number</th>
							<th>QUANTITY</th>
							<th>U/PRICE</th>
							<th>TOTAL</th>
							<th>(X)</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<button id="btn_Save" class="btn btn-success" style="width:10%;">Save</button>
				<a href="./internalInvoice.php"><button class="btn btn-danger" id="btn_Cancel" style="width:10%;">Cancel</button></a>
			</div>
		</div>
	  </div>
	</div>
	
<!--Modal-->
<div id="modal_AddInternalInvoice" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:650px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Internal Invoice</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:20%;">PRODUCT CODE:</label><input type="text" id="txt_ProductCodeAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">DESCRIPTION:</label><input type="text" id="txt_DescriptionAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">PO NUMBER:</label><input type="text" id="txt_PONumAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">QUANTITY:</label><input type="text" id="txt_QuantityAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">U/PRICE:</label><input type="text" id="txt_UPriceAM" style="width:20%;">
				<label style="margin-right:5%;width:20%;">TOTAL:</label><input type="text" id="txt_TotalAM" style="width:20%;">
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_AddData" class="btn btn-success">ADD</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
<!--Modal-->
<div id="modal_DeleteData" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
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
<div id="modal_GenerateReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">GENERATE REPORT</h4>
			</div>
			<div class="modal-body">
				<?php
					echo '<label style="margin-right:5%;width:20%;">Start Date:</label></label><input type="date" id="txt_DateDailyStart" style="width:50%;" value="'.$dateToday.'"><br>';
					echo '<label style="margin-right:5%;width:20%;">End Date:</label></label><input type="date" id="txt_DateDailyEnd" style="width:50%;" value="'.$dateToday.'">';
				?>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_GenereteReportExcel" class="btn btn-success">GENERATE REPORT</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
</body>
<script>

$('document').ready(function(){
	//onload functions
	var CtrlNoEdit = "<?php echo $CtrlNo; ?>";
	$('#txt_ControlNumber').val(CtrlNoEdit);
	//click functions
	$('#btn_AddItem').click(function(){
		$('#modal_AddInternalInvoice').modal('show');
	});
	
	$('#btn_Save').click(function(){
		alert("Data has been added!");
	});
	
	$('#btn_AddData').click(function(){
		var ctr = 0;
		$('#tbl_InternalInvoiceMain tr').each(function(row, tr){
			if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
				//GET VALUES HERE
				ctr =  parseInt($(tr).find('td:eq(0)').text());
			}
			ctr = parseInt(ctr) + parseInt(1);
			row++;	
		});
		
		$('#tbl_InternalInvoiceMain tbody').append(
		'<tr><td>'+ctr+
		'</td><td>'+$("#txt_ProductCodeAM").val()+
		'</td><td>'+$("#txt_DescriptionAM").val()+
		'</td><td>'+$("#txt_PONumAM").val()+
		'</td><td>'+$("#txt_QuantityAM").val()+
		'</td><td>'+$("#txt_UPriceAM").val()+
		'</td><td>'+$("#txt_TotalAM").val()+
		'</td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>'); //APPEND NEW DATA INTO THE TABLE
		ctr++;
	});
});
</script>
</html>