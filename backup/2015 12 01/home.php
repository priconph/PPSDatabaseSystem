<?php
require_once('./includes_home.php');
?>
<html>
<title>PPS Database System</title>
<head>
<!--<link href="./css/ppsStyleSheet.css" rel="stylesheet">-->
<style>
#div-1 {
 position:relative;
}
#div-1a {
 float:left;
 top:0;
 right:0;
 width:33%;
 height:80%;
 <!--border: 2px solid;
 border-radius:3%;-->
}
#div-1a div{
width:auto;
margin:auto;
}
#div-1b {
 float:left;
 top:0;
 left:0;
 width:33%;
 height:80%;
 <!--border: 2px solid;
 border-radius:3%;-->
}
#div-1b div{
width:auto;
margin:auto;
}
#div-1c {
 float:left;
 top:0;
 left:0;
 width:34%;
 height:80%;
 <!--border: 2px solid;
 border-radius:3%;-->	
}
#div-1c div{
width:auto;
margin:auto;
}

#div-2 {
 position:relative;
}
#div-2a {
 float:left;
 top:0;
 right:0;
 width:33%;
 height:80%;
 <!--border: 2px solid;
 border-radius:3%;-->
}
#div-2a div{
width:auto;
margin:auto;
}
#div-2b {
 float:left;
 top:0;
 left:0;
 width:33%;
 height:80%;
 <!--border: 2px solid;
 border-radius:3%;-->
}
#div-2b div{
width:auto;
margin:auto;
}
#div-2c {
 float:left;
 top:0;
 left:0;
 width:34%;
 height:80%;
 <!--border: 2px solid;
 border-radius:3%;-->	
}
#div-2c div{
width:auto;
margin:auto;
}

</style>
<script>
$(function() {
    $( "#" ).draggable(); 
 });
$('document').ready(function(){
	var oTable = $('#TableID HERE').dataTable({
			paginate:false,
			"autoWidth": false
	});
});
</script>
</head>
<body>

<!--
	<div id="mySystemsContainer" class="panel panel-primary">
		<div class="panel-heading"><b>Announcement:</b>
			<label id="toggle_announcement" style="float:right;" class="glyphicon glyphicon-remove"></label>
		</div>
		<div style="" class="panel-body">
			<p>Dear all,</p>
			<p>This is a sample!</p>
			<p>Regards,</p>
			<p>Admin</p>
		</div>
	</div>
-->

<div id="div-1">
	<div id="div-1a">
		<center>
			<div id="div_Dieset_Thumbnail"> 
				<img src="./Images/Dieset" style="width:242px;height:200px;">
			</div>
			<div id="div_Dieset_Header">
				<h3>Dieset</h3>
			</div>
			<div id="div_Dieset_Content">
				<p>Content Here</p>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
					Action
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="./dieset.php">Go to this page</a></li>
				  </ul>
				</div>
				
			</div>
		</center>	
	</div>
	<div id="div-1b">
		<center>
			<div id="div_Quotation_Thumbnail"> 
				<img src="./Images/DiesetQuotation" style="width:242px;height:200px;">
			</div>
			<div id="div_Quotation_Header">
				<h3>Generate Quotation</h3>
			</div>
			<div id="div_Quotation_Content">
				<p>Content Here</p>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
					Action
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="./generateQuotation.php">Go to this page</a></li>
					
				  </ul>
				</div>
				
			</div>
		</center>
	</div>
	<div id="div-1c">
		<center>
			<div id="div_PO_Receive_Thumbnail"> 
				<img src="./Images/POReceived" style="width:242px;height:200px;">
			</div>
			<div id="div_PO_Receive_Header">
				<h3>PO Receive</h3>
			</div>
			<div id="div_PO_Receive_Content">
				<p>Content Here</p>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
					Action
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="./poReceived.php">Go to this page</a></li>
					
				  </ul>
				</div>
				
			</div>
		</center>
	</div>
</div>
	
<div id="div-2">
	<div id="div-2a">
		<center>
			<div id="div_Resin_PR_Thumbnail"> 
				<img src="./Images/Resin" style="width:242px;height:200px;">
			</div>
			<div id="div_Resin_PR_Header">
				<h3>Inventory Consideration</h3>
			</div>
			<div id="div_Resin_PR_Content">
				<p>Content Here</p>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
					Action
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="./generateResinPR.php">Go to this page</a></li>
					
				  </ul>
				</div>
				
			</div>
		</center>	
	</div>
	<div id="div-2b">
		<center>
			<div id="div_Shipment_Confirmation_Thumbnail"> 
				<img src="./Images/ShipmentConfirmation" style="width:242px;height:200px;">
			</div>
			<div id="div_Shipment_Confirmation_Header">
				<h3>Delivery Confirmation</h3>
			</div>
			<div id="div_Shipment_Confirmation_Content">
				<p>Content Here</p>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
					Action
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="./deliveryConfirmation.php">Go to this page</a></li>
					
				  </ul>
				</div>
				
			</div>
		</center>
	</div>
	<div id="div-2c">
		<center>
			<div id="div_Internal_Invoice_Thumbnail"> 
				<img src="./Images/InternalInvoice" style="width:242px;height:200px;">
			</div>
			<div id="div_Internal_Invoice_Header">
				<h3>Shipment</h3>
			</div>
			<div id="div_Internal_Invoice_Content">
				<p>Content Here</p>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
					Action
					<span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="./internalInvoice.php">Go to this page</a></li>
					
				  </ul>
				</div>
				
			</div>
		</center>
	</div>
</div>

	
<!--Modal-->
<div id="modalNameHere" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">TITLE</h4>
			</div>
			<div class="modal-body">
				<p>CONTENT</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btnYes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>

</body>
</html>