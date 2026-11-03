<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$itemCode = $_GET['itemCode'];
$getItemCodeInfo = PPSDBS::getInstance()->getItemCodeInfoByItemCodeReal($itemCode);
while($row= mysqli_fetch_array($getItemCodeInfo)) {
	$supplier = $row['Supplier'];
	$yecpo = $row['YECPO'];
	$deviceName = $row['Name'];
	$orderNo = $row['OrderNo'];
	$schedQty = $row['SchdQty'];
	$purchaseUCost = $row['PurchaseUnitCost'];
	$purchaseOrderAmt = $row['PurchaseOrderAmt'];
	$dateIssued = $row['DateIssued'];
	$ppdReply = $row['PPDReply'];
	$dateNeeded = $row['DateNeeded'];
	$remarks = $row['Remarks'];
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
		
		var supplier = $('#supplier').val();
		var yecPO = $('#yecPO').val();
		var itemCode = $('#itemCode').val();
		var name = $('#name').val();
		var orderNo = $('#orderNo').val();
		var schedQty = $('#schedQty').val();
		var purchaseUCost = $('#purchaseUCost').val();
		var purchaseOrderAmount = $('#purchaseOrderAmount').val();
		var dateIssued = $('#dateIssued').val();
		var ppdReply = $('#ppdReply').val();
		var dateNeeded = $('#dateNeeded').val();
		var remarks = $('#remarks').val();
		var drawingNo = $('#drawingNo').val();
		
		alert(supplier+yecPO+itemCode+name+orderNo+schedQty+purchaseUCost+purchaseOrderAmount+dateIssued+ppdReply+dateNeeded+remarks+drawingNo);
		var data = {
			"action" : "updatePOReceived",
			"supplier" : supplier,
			"yecPO" : yecPO,
			"itemCode" : itemCode,
			"name" : name,
			"orderNo" : orderNo,
			"schedQty" : schedQty,
			"purchaseUCost" : purchaseUCost,
			"purchaseOrderAmount" : purchaseOrderAmount,
			"dateIssued" : dateIssued,
			"ppdReply" : ppdReply,
			"dateNeeded" : dateNeeded,
			"drawingNo" : drawingNo,
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
					window.location.href = "./poReceived.php";
				}
			});
	});
	$('#btn_Cancel').click(function(){
		window.location.href = "./poReceived.php";
	});
});
</script>
</head>
<body>
	<div>
		<div class="panel panel-primary" style="overflow:scroll;">
		<div class="panel-heading" style="width:auto;overflow:hidden;">Update New P.O. Receive</div>
			<div class="panel-body" style="width:auto;">
				<div class="panel panel-primary">
					<div class="panel-body">
						<label style="margin-right:5%;width:20%;">Category</label><input type="text" style="width:20%;" id="supplier" value='<?php echo $supplier; ?>'>
						<label style="margin-right:5%;width:20%;">YEC P.O.</label><input type="text" style="width:20%;" id="yecPO" value='<?php echo $yecpo; ?>'>
						<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode" value='<?php echo $itemCode; ?>' readOnly>
						<label style="margin-right:5%;width:20%;">Name(Device Name)</label><input type="text" style="width:20%;" id="name" value='<?php echo $deviceName; ?>'>
						<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo" value='<?php echo $orderNo; ?>'>
						<label style="margin-right:5%;width:20%;">Scheduled Quantity</label><input type="text" style="width:20%;" id="schedQty" value='<?php echo $schedQty; ?>'>
						<label style="margin-right:5%;width:20%;">Purchase U/Cost</label><input type="text" style="width:20%;" id="purchaseUCost" value='<?php echo $purchaseUCost; ?>'>
						<label style="margin-right:5%;width:20%;">Purchase Order Amount</label><input type="text" style="width:20%;" id="purchaseOrderAmount" value='<?php echo $purchaseOrderAmt; ?>'>
						<label style="margin-right:5%;width:20%;">Date Issued</label><input type="date" style="width:20%;height:4%;" id="dateIssued" value='<?php echo $dateIssued; ?>'>
						<label style="margin-right:5%;width:20%;">PPD Reply</label><input type="date" style="width:20%;" id="ppdReply" value='<?php echo $ppdReply; ?>'>
						<label style="margin-right:5%;width:20%;">Date Needed</label><input type="date" style="width:20%;height:4%;" id="dateNeeded" value='<?php echo $dateNeeded; ?>'>
						<label style="margin-right:5%;width:20%;">Drawing No</label><input type="text" style="width:20%;" id="drawingNo" value='<?php echo $drawingNo; ?>'>
						<label style="margin-right:5%;width:20%;">Remarks</label><input type="text" style="width:20%;" id="remarks" value='<?php echo $remarks; ?>'>
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