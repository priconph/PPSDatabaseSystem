<?php
//require_once('./includes.php');
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<link href="./packaged/css/semantic.css" rel="stylesheet">
<script src="./packaged/javascript/semantic.js" type="text/javascript" language="javascript" ></script>
<style>
.summary label{
display:inline-block;
}
</style>
<script>
$('document').ready(function(){
	var oTable = $('#TableID HERE').dataTable({
			paginate:false,
			"autoWidth": false
	});
	alert("test");
	$('#test').change(function(){
		alert($("#test").html());
	});
	
});
</script>
</head>
<body>

<div class="ui menu">
  <div class="menu">
    <a class="active item">
      <i class="users icon"></i>
      Friends
    </a>
    <a class="item">
      <i class="grid layout icon"></i> Browse
    </a>
    <a class="item">
      <i class="mail icon"></i> Messages
    </a>
   </div>
  <div class="ui sub menu">
    <a class="item">Add</a>
    <a class="item">Remove</a>
  </div>
</div>

<h4>SAMPLE HEADER</h4>
<div class="ui feed segment">
  <div class="event">
    <div class="label">
      <img src="/images/demo/avatar.jpg">
    </div>
    <div class="content">
      <div class="summary">
			<div class="ui input">
				<label style="margin-right:5%;width:20%;">Workcenter</label><input type="text" style="width:20%;" id="workcenter">
				<label style="margin-right:5%;width:20%;">W/C Name</label><input type="text" style="width:20%;" id="wcName"><br>
				<label style="margin-right:5%;width:20%;">Supplier</label><input type="text" style="width:20%;" id="supplier">
				<label style="margin-right:5%;width:20%;">Supplier Name</label><input type="text" style="width:20%;" id="supplierName"><br>
				<label style="margin-right:5%;width:20%;">YEC P.O.</label><input type="text" style="width:20%;" id="yecPO">
				<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode"><br>
				<label style="margin-right:5%;width:20%;">Name</label><input type="text" style="width:20%;" id="name">
				<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo"><br>
				<label style="margin-right:5%;width:20%;">Scheduled Quantity</label><input type="text" style="width:20%;" id="schedQty">
				<label style="margin-right:5%;width:20%;">Purchase U/Cost</label><input type="text" style="width:20%;" id="purchaseUCost"><br>
				<label style="margin-right:5%;width:20%;">Purchase Order Amount</label><input type="text" style="width:20%;" id="purchaseOrderAmount">
				<label style="margin-right:5%;width:20%;">Currency</label><input type="text" style="width:20%;" id="currency"><br>
				<label style="margin-right:5%;width:20%;">Deli. Location</label><input type="text" style="width:20%;" id="deliLocation">
				<label style="margin-right:5%;width:20%;">Deli. Loc. Name</label><input type="text" style="width:20%;" id="deliLocName"><br>
				<label style="margin-right:5%;width:20%;">Date Issued</label><input type="text" style="width:20%;" id="dateIssued">
				<label style="margin-right:5%;width:20%;">PPD Reply</label><input type="text" style="width:20%;" id="ppdReply"><br>
				<label style="margin-right:5%;width:20%;">Date Needed</label><input type="text" style="width:20%;" id="dateNeeded">
				<label style="margin-right:5%;width:20%;">Remarks</label><input type="text" style="width:20%;" id="remarks"><br>
				<label style="margin-right:5%;width:20%;">Drawing No</label><input type="text" style="width:20%;" id="drawingNo">
				<label style="margin-right:5%;width:20%;">Classification</label><input type="text" style="width:20%;" id="classification">
				<div class="ui horizontal divider">
					SAVE OR CANCEL
				</div>
				<center>
					<div class="ui buttons">
					  <div class="ui button">Cancel</div>
					  <div class="or"></div>
					  <div class="ui positive button">Save</div>
					</div>
				</center>
			</div>	
			
	  </div>
    </div>
  </div>
</div>
<!--
<div class="panel panel-primary">
	<div class="panel-heading">SAMPLE HEADER</div>
	<div class="panel-body">
		<div class="ui buttons">
		  <div class="ui button">Cancel</div>
		  <div class="or"></div>
		  <div class="ui positive button">Save</div>
		</div>
	</div>
</div>
-->

</body>
</html>