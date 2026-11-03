<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$orderNo = $_GET['orderNo'];
$getPOReceivedInfoByOrderNo = PPSDBS::getInstance()->viewPOReceived('orderno', $orderNo, '','');
while($row= mysqli_fetch_array($getPOReceivedInfoByOrderNo)) {
	$category = $row['Category'];
	$poNo = $row['ProductPONo'];
	$itemCode = $row['ItemCode'];
	$itemName = $row['ItemName'];
	$orderQty = $row['OrderQty'];
	$price = $row['Price'];
	$amount = $row['Amount'];
	$dateIssued = $row['DateIssued'];
	$shipOut = $row['ShipOutPPS'];
	$poBalance = $row['POBalance'];
	$drawingNo = $row['DrawingNo'];
}
$dateToday = date('Y-m-d');
session_start();
$username = $_SESSION['username'];

?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#btn_Save').click(function(){
		
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
		
		//alert(supplier+yecPO+itemCode+name+orderNo+schedQty+purchaseUCost+purchaseOrderAmount+dateIssued+ppdReply+dateNeeded+remarks+drawingNo);
		var data = {
			"action" : "updatePOReceived",
			"category" : category,
			"poNo" : poNo,
			"itemCode" : itemCode,
			"itemName" : itemName,
			"orderNo" : orderNo,
			"orderQty" : orderQty,
			"price" : price,
			"Amount" : amount,
			"dateIssued" : dateIssued,
			"shipOut" : shipOut,
			"poBalance" : poBalance,
			"drawingNo" : drawingNo,
			"username" : "<?php echo $username; ?>"
		}
		
		data = $.param(data);
			$.ajax({
				type:"POST",
				dataType:"json",
				url:"ppsdbsHandler.php",
				data:data,
				success:function(result) {
					alert('Successfully updated!');
					window.location.href = "./poReceived.php";
				}
			});
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
		var poBalance = parseFloat(amount - shipOut).toFixed(4);
		$('#poBalance').val(poBalance);
	});
	
	$('#orderQty').change(function () {
		var orderQty = $('#orderQty').val();
		var price = $('#price').val();
		var amount = parseFloat(orderQty * price).toFixed(4);
		$('#amount').val(amount);
		var shipOut = $('#shipOut').val();
		var poBalance = parseFloat(amount - shipOut).toFixed(4);
		$('#poBalance').val(poBalance);
	});
	
	$('#shipOut').change(function () {
		var amount = $('#amount').val();
		var shipOut = $('#shipOut').val();
		var poBalance = parseFloat(amount - shipOut).toFixed(4);
		$('#poBalance').val(poBalance);
	});
});
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
</script>
</head>
<body>
	<div>
		<div class="panel panel-primary" style="overflow:scroll;">
		<div class="panel-heading" style="width:auto;overflow:hidden;">Update New P.O. Receive</div>
			<div class="panel-body" style="width:auto;">
				<div class="panel panel-primary">
					<div class="panel-body">
						<label style="margin-right:5%;width:20%;">Category</label><input type="text" style="width:20%;" id="category" value='<?php echo $category; ?>'>
						<label style="margin-right:5%;width:20%;">Product P.O. No.</label><input type="text" style="width:20%;" id="poNo" value='<?php echo $poNo; ?>'>
						<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode" value='<?php echo $itemCode; ?>'>
						<label style="margin-right:5%;width:20%;">Item Name</label><input type="text" style="width:20%;" id="itemName" value='<?php echo $itemName; ?>'>
						<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo" value='<?php echo $orderNo; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Order Quantity</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="orderQty" value='<?php echo $orderQty; ?>'>
						<label style="margin-right:5%;width:20%;">Price</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="price" value='<?php echo $price; ?>'>
						<label style="margin-right:5%;width:20%;">Amount</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="amount" value='<?php echo $amount; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Date Issued</label><input type="date" style="width:20%;height:7%;" id="dateIssued" value='<?php echo $dateIssued; ?>'>
						<label style="margin-right:5%;width:20%;">Ship Out</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="shipOut" value='<?php echo $shipOut; ?>'>
						<label style="margin-right:5%;width:20%;">P.O. Balance</label><input type="text" style="width:20%;height:7%;" id="poBalance" value='<?php echo $poBalance; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Drawing No</label><input type="text" style="width:20%;height:7%;" id="drawingNo" value='<?php echo $drawingNo; ?>'>
					</div>
				</div>
				<div class="panel panel-primary" align="right">
					<div class="panel-body">
						<button class="btn btn-success" style="width:10%;" id="btn_Save">Save</button>
						<?php
						  $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
						  echo "<a href='$url'><button class='btn btn-default'>GO BACK</button></a>"; 
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>