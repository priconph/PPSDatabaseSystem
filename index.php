<?php
require_once('./includes.php');
//SET IP RESTRICTIONS
/*
$PCIP = $_SERVER['REMOTE_ADDR'];
if($PCIP != '192.168.20.103') {
header('Location: ../');
}
*/
session_start();
if(!isset($_SESSION['username'])){
	echo 'Do not access direct path! Re-login!';
	exit;
}
?>
<html>
<title>PPS Database System</title>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	$('#li_DiesetList').click(function(){
		$('#iFrm_Main').attr('src', "./dieset.php");
		$('#li_DiesetList').attr('class', "active");
		$('#li_OrderReceive').attr('class', "");
		$('#li_DeliveryConfirmation').attr('class', "");
		$('#li_Shipment').attr('class', "");
	});
	$('#li_OrderReceive').click(function(){
		$('#iFrm_Main').attr('src', "./poReceived.php");
		$('#li_DiesetList').attr('class', "");
		$('#li_OrderReceive').attr('class', "active");
		$('#li_DeliveryConfirmation').attr('class', "");
		$('#li_Shipment').attr('class', "");
	});
	$('#li_DeliveryConfirmation').click(function(){
		$('#iFrm_Main').attr('src', "./deliveryConfirmation.php");
		$('#li_DiesetList').attr('class', "");
		$('#li_OrderReceive').attr('class', "");
		$('#li_DeliveryConfirmation').attr('class', "active");
		$('#li_Shipment').attr('class', "");
	});
	$('#li_Shipment').click(function(){
		$('#iFrm_Main').attr('src', "./shipment.php");
		$('#li_DiesetList').attr('class', "");
		$('#li_OrderReceive').attr('class', "");
		$('#li_DeliveryConfirmation').attr('class', "");
		$('#li_Shipment').attr('class', "active");
	});
});

</script>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./index.php">PPD</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="" id="li_DiesetList"><a href="#">DIE-SET</a></li>
        <li class="" id="li_OrderReceive"><a href="#">P.O. RECEIVE</a></li>
        <li class="" id="li_DeliveryConfirmation"><a href="#">DELIVERY CONFIRMATION</a></li>
        <li class="" id="li_Shipment"><a href="#">SHIPMENT</a></li>
        <li class="dropdown">
          <!--
		  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
            <li class="divider"></li>
			<li><a href="#">One more separated link</a></li>
          </ul>
		  -->
        </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">RESERVED</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<p style="text-align:center;">
	<iframe sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-popups-to-escape-sandbox allow-downloads allow-modals" src="./home.php" id="iFrm_Main" frameborder="0"></iframe>
  
</p>

</body>
</html>