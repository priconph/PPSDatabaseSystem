<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$orderNo = $_GET['OrderNo'];
$getDeliveryConfirmation = PPSDBS::getInstance()->getDeliveryConfirmation('orderno','','',$orderNo);
while($row= mysqli_fetch_array($getDeliveryConfirmation)) {
	$dateIssued = $row['DateIssued'];
	$category = $row['Category'];
	$poNo = $row['ProductPONo'];
	$itemCode = $row['ItemCode'];
	$itemName = $row['ItemName'];
	$orderQty = $row['OrderQty'];
	$orderBalance = $row['OrderBalance'];
	$targetSO = $row['TargetSO'];
	$shipmentDate = $row['ShipmentDate'];
	$remarks = $row['Remarks'];
	echo $pkid = $row['id'];
}
$dateToday = date('Y-m-d');
session_start();
$username = $_SESSION['username'];
$username = 'rona';
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#btn_Save').click(function(){
		var orderNo = $('#orderNo').val();
		var targetSO = $('#targetSO').val();
		var orderBalance = $('#orderBalance').val();
		var shipmentDate = $('#shipmentDate').val();
		var remarks = $('#remarks').val();
		var pkid = "<?php echo $pkid; ?>";
		if(parseFloat(orderBalance) < parseFloat(targetSO)) {
			alert('Target for Ship out is greater that Order Balance!');
		} else {
			var data = {
				"action" : "updateDeliveryConfirmation",
				"orderNo" : orderNo,
				"pkid" : pkid,
				"orderBalance" : orderBalance,
				"targetSO" : targetSO,
				"shipmentDate" : shipmentDate,
				"remarks" : remarks,
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
						window.location.href = "./deliveryConfirmation.php";
					}
				});
		}
		//alert(supplier+yecPO+itemCode+name+orderNo+schedQty+purchaseUCost+purchaseOrderAmount+dateIssued+ppdReply+dateNeeded+remarks+drawingNo);
		
	});
	$('#btn_Cancel').click(function(){
		window.location.href = "./poReceived.php";
	});
	
	$('#targetSO').change(function () {
		var targetSO = $('#targetSO').val();
		var orderBalance = $('#orderBalance').val();
		if(parseFloat(orderBalance) < parseFloat(targetSO)) {
			alert('Target for Ship out is greater that Order Balance!');
			$('#targetSO').focus();
		}
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
						<label style="margin-right:5%;width:20%;">Date Issued</label><input type="date" style="width:20%;height:7%;" id="dateIssued" value='<?php echo $dateIssued; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Category</label><input type="text" style="width:20%;" id="supplier" value='<?php echo $category; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Product P.O. No.</label><input type="text" style="width:20%;" id="yecPO" value='<?php echo $poNo; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode" value='<?php echo $itemCode; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Item Name</label><input type="text" style="width:20%;" id="itemName" value='<?php echo $itemName; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo" value='<?php echo $orderNo; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Order Balance</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="orderBalance" value='<?php echo $orderBalance; ?>'>
						<label style="margin-right:5%;width:20%;">Target for S/O</label><input type="text" onkeypress="CheckNumeric()" style="width:20%;" id="targetSO" value='<?php echo $targetSO; ?>'>
						<label style="margin-right:5%;width:20%;">Shipment Date</label><input type="date" style="width:20%;" id="shipmentDate" value='<?php echo $shipmentDate; ?>' >
						<label style="margin-right:5%;width:20%;">Remarks</label><input type="text" style="width:20%;" id="remarks" value='<?php echo $remarks; ?>' >
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