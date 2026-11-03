<?php
require_once('./includes.php');
$dateToday = date('Y-m-d');
session_start();
$username = $_SESSION['username'];
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
var Handler = 'ppsdbsHandler.php';
$('document').ready(function(){
	
	$('#btn_Save').click(function(){
		check_session_status();
	});
	
	$('#shipOut').change(function () {
		var orderQty = $('#orderQty').val();
		var shipOut = $('#shipOut').val();
		if(shipOut > orderQty) {
			alert('Ship out quantity is greater that ordered quantity!');
			$('#shipOut').focus();
		}
		
	});
	
	$('#btn_Cancel').click(function(){
		window.location.href = "./poReceived.php";
	});
	
	$('#price').change(function () {
		var orderQty = $('#orderQty').val();
		var price = $('#price').val();
		var amount = parseFloat(orderQty * price).toFixed(4);
		$('#amount').val(amount);
		var shipOut = $('#shipOut').val();
		var poBalance = parseFloat(orderQty - shipOut);
		$('#poBalance').val(poBalance);
	});
	
	$('#orderQty').change(function () {
		var orderQty = $('#orderQty').val();
		var price = $('#price').val();
		var amount = parseFloat(orderQty * price).toFixed(4);
		$('#amount').val(amount);
		var shipOut = $('#shipOut').val();
		var poBalance = parseFloat(orderQty - shipOut);
		$('#poBalance').val(poBalance);
	});
	
	$('#shipOut').change(function () {
		var orderQty = $('#orderQty').val();
		var shipOut = $('#shipOut').val();
		var poBalance = parseFloat(orderQty - shipOut);
		$('#poBalance').val(poBalance);
	});
	
	$('#itemName').change(function(){ 
		var DeviceName = $('#itemName').val();
		GetDiesetByDeviceName(DeviceName);
	});
	
});

function SaveRecord(){
	var category = $('#category').val();
	var poNo = $('#poNo').val();
	var itemCode = $('#itemCode').val();
	var itemName = $('#itemName').val();
	var orderNo = $('#orderNo').val();
	var orderQty = $('#orderQty').val();
	var price = $('#price').val();
	var amount = $('#amount').val();
	var dateIssued = $('#dateIssued').val();
	var shipOut = $('#shipOut').val();
	var poBalance = $('#poBalance').val();
	var drawingNo = $('#drawingNo').val();
	
	//alert(workcenter+wcName+supplier+supplierName+yecPO+itemCode+name+orderNo+schedQty+purchaseUCost+purchaseOrderAmount+currency+deliLocation+deliLocName+dateIssued+ppdReply+dateNeeded+remarks+drawingNo+classification);
	if(category == '' || poNo == '' || itemCode == '' || itemName == '' || orderNo == '' || price == 0 || amount == '' || poBalance  == '' || drawingNo  == '') {
		alert('Please check all required fields.');
	} else {
		if(shipOut > orderQty) {
			alert('Ship out quantity is greater that ordered quantity!');
		} else {
			var data = {
				"action" 	: "addNewPOReceived",
				"category" 	: category,
				"poNo" 		: poNo,
				"itemCode" 	: itemCode,
				"itemName" 	: itemName,
				"orderNo" 	: orderNo,
				"orderQty" 	: orderQty,
				"price" 	: price,
				"amount" 	: amount,
				"dateIssued" : dateIssued,
				"shipOut" 	: shipOut,
				"poBalance" : poBalance,
				"drawingNo" : drawingNo,
				"username" 	: "<?php echo $username; ?>"
			}
			
			data = $.param(data);
			$.ajax({
				type	:"POST",
				dataType:"json",
				url 	: Handler,
				data	:data,
				success	:function(result) {
					alert(result['msg']);
					window.location.href = "./poReceived.php";
				}
			});
		}
	}
}

function CheckNumeric(){
  // Get ASCII value of key that user pressed
   var key = window.event.keyCode;
  // Was key that was pressed a numeric character (0-9)?
   if ( key > 45 && key < 58 )
     return; // if so, do nothing
   else
     window.event.returnValue = null; // otherwise, 
	                               // discard character
}

getAllDeviceName(1); //load datalist with devicename
//getAllDeviceName(2); //load datalist with item code or r3code or product code 

function getAllDeviceName(Category){
	//alert("ginawa nya to sa kategoryang "+ Category);
	var data = {
		"action"	: "getAllDeviceName"
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: Handler,
		success	: function(data){
			$('#list_DeviceInfo').empty();
			var ctr = 0;
			while(ctr < data['ctr']){
				if(Category == '1'){
					var Html = "<option>"+data['DeviceName'][ctr]+"</option>";
					$('#list_DeviceInfo').append(Html);
				}else{
					var Html = "<option>"+data['R3Code'][ctr]+"</option>";
					$('#list_DeviceInfo').append(Html);
				}
				ctr++;
			}
				
			if(Category == '1'){
				$('#itemName').removeAttr('list');
				$('#itemName').attr('list','list_DeviceInfo');
			}else{
				$('#itemCode').removeAttr('list');	
				$('#itemCode').attr('list','list_DeviceInfo');
			}
		}
	});
}

function GetDiesetByDeviceName(DeviceName){
	var data = {
		"action"	: "GetDiesetByDeviceName",
		"DeviceName": DeviceName
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: Handler,
		success	: function(data){
			$('#itemCode').val(data['PartsCode']);
		}
	});
}

function check_session_status(){
	var data = {
		"action"	: "session_check"
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: "./session_check.php",
		success	: function(data){
			if(data['username'] == null){
				alert('Session expired, Please relogin.');
				parent.parent.location.reload();
			}else{
				SaveRecord();
			}
		} 
	});
}
</script>
</head>
<body>
	<datalist id="list_DeviceInfo"></datalist>
	
	<div>
		<div class="panel panel-primary" style="overflow:scroll;">
		<div class="panel-heading" style="width:auto;overflow:hidden;">Add New P.O. Receive</div>
			<div class="panel-body" style="width:auto;">
				<div class="panel panel-primary">
					<div class="panel-body">
						<label style="margin-right:5%;width:20%;">Category</label><input type="text" style="width:20%;" id="category">
						<label style="margin-right:5%;width:20%;">Product P.O. No.</label><input type="text" style="width:20%;" id="poNo">
						<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode">
						<label style="margin-right:5%;width:20%;">Item Name</label><input type="text" style="width:20%;" id="itemName">
						<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo">
						<label style="margin-right:5%;width:20%;">Order Qty.</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="orderQty">
						<label style="margin-right:5%;width:20%;">Price</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="price" value='0'>
						<label style="margin-right:5%;width:20%;">Amount</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="amount" readOnly>
						<label style="margin-right:5%;width:20%;">Date Issued</label><input type="date" style="width:20%;height:7%;" id="dateIssued" value='<?php echo $dateToday; ?>'>
						<label style="margin-right:5%;width:20%;">Ship Out</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="shipOut" value='0'>
						<label style="margin-right:5%;width:20%;">PO Balance</label><input type="text" style="width:20%;" id="poBalance" readOnly>
						<label style="margin-right:5%;width:20%;">Drawing Number</label><input type="text" style="width:20%;" id="drawingNo">
					</div>
				</div>
				<div class="panel panel-primary" align="right">
					<div class="panel-body">
						<button class="btn btn-success" style="width:10%;" id="btn_Save">Save</button>
						<button class="btn btn-danger" id="btn_Cancel" style="width:10%;">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>