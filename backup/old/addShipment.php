<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$dateReset = '2000-01-01';
$username = $_SESSION['username'];
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
if($rowControlNumber = mysqli_fetch_array($ControlNumberQuery)) {
	$ControlNumber = $rowControlNumber['ControlNumber'];
	if($ControlNumber == null) {
		$ControlNumber = "PPS-".date("Ym")."-0001";
	} else {
		$ctr = substr($ControlNumber, 11, 15) + 1;
		if($ctr > 1 && $ctr < 10) {
			$ctr = "000".$ctr;
		} else if($ctr > 9 && $ctr < 100) {
			$ctr = "00".$ctr;
		} else if($ctr > 99 && $ctr < 1000) {
			$ctr = "0".$ctr;
		} else if($ctr > 999) {
			$ctr = $ctr;
		}
		$ControlNumber = "PPS-".date("Ym")."-".$ctr;
	}
}
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
				<label style="margin-right:5%;width:20%;">Control Number:</label><input type="text" id="txt_ControlNumber" style="width:20%;" value="<?php echo $ControlNumber; ?>" readOnly><br>
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
				  <button id="btn_AddItem" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD PO</button>
				  <button id="btn_ImportCSV" type="button" class="btn btn-default glyphicon glyphicon-import">IMPORT</button>
				</div><br><br>
				<table id="tbl_Shipment" class="table" border="1">
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
							<th>REMARKS</th>
							<th>(X)</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				<div align="right"><label style="width:10%;">Grand Total:</label><input type="text" id="txt_GrandTotal" value="0" readOnly></div>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body" align="right">
				<button id="btn_Save" class="btn btn-success" style="width:10%;">Save</button>
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
					<label style="margin-right:5%;width:30%;">SHIPOUT QTY:</label><input onkeypress="CheckNumeric()" type="text" id="txt_ShipoutQtyI" style="width:60%;" readOnly><br>
					<label style="margin-right:5%;width:30%;">UNIT PRICE:</label><input onkeypress="CheckNumeric()" type="text" id="txt_UnitPriceI" style="width:60%;" readOnly><br>
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
</body>
<script>

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
			
			//$("#txt_TotalAM").val(parseFloat($("#txt_QuantityAM").val()*$('#txt_UPriceAM').val()).toFixed(4));
			
			if(POChecker==0){
				//add an internal po
				var amt = $("#txt_AmountI").val();
				$('#tbl_Shipment tbody').append(
				//'<tr><td>'+$("#txt_CategoryI").val()+
				//'</td><td>'+$("#txt_ProductPOI").val()+
				'<tr><td>'+$("#txt_ItemCodeI").val()+
				'</td><td>'+$("#txt_ItemNameI").val()+
				'</td><td>'+$("#txt_OrderNoI").val()+
				'</td><td>'+$("#txt_ShipoutQtyI").val()+
				'</td><td>'+parseFloat($("#txt_UnitPriceI").val()).toFixed(4)+
				'</td><td>'+parseFloat(amt.replace("$","")).toFixed(4)+
				'</td><td>'+$("#txt_RemarksI").val()+
				'</td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>'); //APPEND NEW DATA INTO THE TABLE
			}else{
				//add an external po
				var amt = $("#txt_AmountE").val();
				$('#tbl_Shipment tbody').append(
				//'<tr><td>'+$("#txt_CategoryE").val()+
				//'</td><td>'+$("#txt_ProductPOE").val()+
				'<tr><td>'+$("#txt_ItemCodeE").val()+
				'</td><td>'+$("#txt_ItemNameE").val()+
				'</td><td>'+$("#txt_OrderNoE").val()+
				'</td><td>'+$("#txt_ShipoutQtyE").val()+
				'</td><td>'+parseFloat($("#txt_UnitPriceE").val()).toFixed(4)+
				'</td><td>'+parseFloat(amt.replace("$","")).toFixed(4)+
				'</td><td>'+$("#txt_RemarksE").val()+
				'</td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>'); //APPEND NEW DATA INTO THE TABLE
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
	
	var ControlNoG=0;
	$('#btn_Save').click(function(){
		var dateToday = '<?php echo $dateToday; ?>';
		if(confirm('Do you really want to save your data?')) {
			if($("#txt_ControlNumber").val() == "" || $("#txt_RevNo").val() == "" ||  $("#txt_SoldTo").val() == "" || $("#txt_ShippedBy").val()  == "") {
				alert("Please fill out all required fields.");
			} else {
				var data = {
					"action" : "addNewRecordShipmentInvoice",
					"ControlNumber" : $("#txt_ControlNumber").val(),
					"Date" : $("#txt_Date").val(),
					"RevNo" : $("#txt_RevNo").val(),
					"SoldTo" : $("#txt_SoldTo").val(),
					"ShippedBy" : $("#txt_ShippedBy").val(),
					"GrandTotal" : $("#txt_GrandTotal").val(),
					"Username" : "<?php echo $username; ?>"
				}
				ControlNoG = data['ControlNumber'];
				data = $.param(data);
				$.ajax({
					type:"post",
					dataType:"json",
					data:data,
					url:"./ppsdbsHandler",
					success:function(result){		
						if(result["msg"] == "Control Number already exists!") {
							alert(result["msg"]);
						} else {
							if($('#txt_GrandTotal').val() == 0) {
								alert("No item added!");
							} else {
								//Loop to all rows of table in internalinvoice
								row=0;
								$("#tbl_Shipment tr").each(function(row,tr){
									if(row>0){ //this first row is the label kaya di sya isasama
										totalPrice = parseFloat(totalPrice) + parseFloat($(tr).find('td:eq(6)').text());
										var data = {
											"action" : "addNewRecordShipment",
											"controlNumber" : ControlNoG,
											"dateIssued" : dateToday,
											"orderNo" : $(tr).find('td:eq(2)').text(),
											"shipOut" : $(tr).find('td:eq(3)').text(),
											"unitPrice" : $(tr).find('td:eq(4)').text(),
											"amount" : $(tr).find('td:eq(5)').text(),
											"remarks" : $(tr).find('td:eq(6)').text(),
											"Username" : "<?php echo $username; ?>"
										}
										data = $.param(data);
										$.ajax({
											type:"post",
											dataType:"json",
											data:data,
											url:"./ppsdbsHandler",
											success:function(){
											}
										});
									} 
									row++;
								});
								alert('Successfully added!');
								window.location.href = './addShipment.php';
							}
						}
					}
				});
			}
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
				
				amount = parseFloat(result['ActualSO'] * result['Price']).toFixed(4);
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
		alert('alerts');
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
</script>
</html>