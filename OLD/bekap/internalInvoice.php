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
$getInternalInvoiceControlNumber = PPSDBS::getInstance()->getInternalInvoiceControlNumber();
echo "<datalist id='ControlNumberList'>";
echo '<option>-VIEW ALL-</option>';
while($rowControlNumberList = mysqli_fetch_array($getInternalInvoiceControlNumber)) {
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
	var oTable = $('#tbl_InternalInvoiceMain').dataTable({
			paginate:false,
			"autoWidth": false
	});
	
	//click functions
	$('#btn_AddItem').click(function(){
		window.location.href = "./addInternalInvoice.php";
	});
	
	$('#btn_ExportToPDF').click(function(){
		var controlNumber = $('#txt_ControlNumber').val();
		if(controlNumber == '-VIEW ALL-') {
			alert('Please select control number.');
		} else {
			window.location.href = "./internalInvoiceExportToPDF.php?controlNumber="+controlNumber;
		}
	});
	
	$('#btn_ExportToExcel').click(function () {
		var controlNumber = $('#txt_ControlNumber').val();
		if(controlNumber == '-VIEW ALL-') {
			alert('Please select control number.');
		} else {
			window.location.href = "./internalInvoiceExportToExcel.php?ControlNumber="+controlNumber;
		}
	});
	
	$('#btn_AddII').click(function(){
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
	
	//double click functions
	$('#tbl_InternalInvoiceMain tbody tr').dblclick(function(){
		var CtrlNoEdit = $(this).find('.CtrlNo').html();
		window.location.href = "./updateInternalInvoice.php?CtrlNo="+CtrlNoEdit;
	});
	
});
</script>
</head>
<body>
<div class="panel panel-primary">
	<div class="panel-heading">INTERNAL INVOICE</div>
	<div class="panel-body">
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
		<form class="form-inline" role="form" method="POST" action="#">
			<label>CONTROL NUMBER: </label> <input type="text" class="form-control" id="txt_ControlNumber" name='txt_ControlNumber' placeholder='-Control Number-' list='ControlNumberList' autocomplete="off" value='<?php echo $controlNumber; ?>'> 
			<button id="btn_Select" class="btn btn-default">Select</button>
		</form>
		
		<div class="btn-group">
		  <button id="btn_AddItem" type="button" class="btn btn-default glyphicon glyphicon-plus">NEW INVOICE</button>
		  <div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-export" data-toggle="dropdown">
				EXPORT
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
				<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL</a></li>
			</ul>
		  </div>
		</div>		
		<table id="tbl_InternalInvoiceMain" class="table" border="1">
			<thead>
				<tr>
					<th>CONTROL #</th>
					<th>DATE</th>
					<th>REV #</th>
					<th>SOLD TO</th>
					<th>SHIPPED BY</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if($controlNumber != "") {
						if($controlNumber == '-VIEW ALL-') {
							$getAllInternalInvoice = PPSDBS::getInstance()->viewInternalInvoice('VIEWALL' , $controlNumber, $_POST['txtStartDate'], $_POST['txtEndDate']);
						} else {
							$getAllInternalInvoice = PPSDBS::getInstance()->viewInternalInvoice('CONTROLNUMBER' , $controlNumber, $_POST['txtStartDate'], $_POST['txtEndDate']);
						}
					} else {
						if(!isset($_POST['txtStartDate']) || !isset($_POST['txtEndDate'])) {
							$getAllInternalInvoice = PPSDBS::getInstance()->viewInternalInvoice('DATE' , $controlNumber, $dateToday, $dateToday);
						} else {
							$getAllInternalInvoice = PPSDBS::getInstance()->viewInternalInvoice('DATE' , $controlNumber, $_POST['txtStartDate'], $_POST['txtEndDate']);
						}
					}
					while($row = mysqli_fetch_array($getAllInternalInvoice)) {
						echo '
							<tr>
								<td class="CtrlNo">'.$row['ControlNumber'].'</td>
								<td>'.$row['Date'].'</td>
								<td>'.$row['RevNo'].'</td>
								<td>'.$row['SoldTo'].'</td>
								<td>'.$row['ShippedBy'].'</td>
							</tr>
						';
					}
				?>
			</tbody>
		</table>
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