<?php
require_once('./includes.php');
$dateToday = date('Y-m-d');
$username = "marlope";
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
		
		//alert(workcenter+wcName+supplier+supplierName+yecPO+itemCode+name+orderNo+schedQty+purchaseUCost+purchaseOrderAmount+currency+deliLocation+deliLocName+dateIssued+ppdReply+dateNeeded+remarks+drawingNo+classification);
		var data = {
			"action" : "addNewPOReceived",
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
					alert(result['msg']);
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
		<div class="panel-heading" style="width:auto;overflow:hidden;">Add New P.O. Receive</div>
			<div class="panel-body" style="width:auto;">
				<div class="panel panel-primary">
					<div class="panel-body">
						<label style="margin-right:5%;width:20%;">Supplier</label><input type="text" style="width:20%;" id="supplier">
						<label style="margin-right:5%;width:20%;">YEC P.O.</label><input type="text" style="width:20%;" id="yecPO">
						<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode">
						<label style="margin-right:5%;width:20%;">Name(Device Name)</label><input type="text" style="width:20%;" id="name">
						<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo">
						<label style="margin-right:5%;width:20%;">Scheduled Quantity</label><input type="text" style="width:20%;" id="schedQty">
						<label style="margin-right:5%;width:20%;">Purchase U/Cost</label><input type="text" style="width:20%;" id="purchaseUCost">
						<label style="margin-right:5%;width:20%;">Purchase Order Amount</label><input type="text" style="width:20%;" id="purchaseOrderAmount">
						<label style="margin-right:5%;width:20%;">Date Issued</label><input type="date" style="width:20%;height:4%;" id="dateIssued">
						<label style="margin-right:5%;width:20%;">PPD Reply</label><input type="text" style="width:20%;" id="ppdReply">
						<label style="margin-right:5%;width:20%;">Date Needed</label><input type="date" style="width:20%;height:4%;" id="dateNeeded">
						<label style="margin-right:5%;width:20%;">Drawing No</label><input type="text" style="width:20%;" id="drawingNo">
						<label style="margin-right:5%;width:20%;">Remarks</label><input type="text" style="width:20%;" id="remarks">
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