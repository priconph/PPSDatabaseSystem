<?php
require_once('./includes.php');
require_once('../rapid-oop2.php');
session_start();
$username = $_SESSION["username"];
//SET IP RESTRICTIONS
/*
$PCIP = $_SERVER['REMOTE_ADDR'];
if($PCIP != '192.168.20.103') {
header('Location: ../');
}
*/
?>
<html>
<title>PPS Database System</title>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<style>

</style>
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
function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.attr('src',url);   
        return false;
    }
    return true;
}
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
      <a class="navbar-brand" href="./index.php">PPS</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
		<?php
			//load the assigned modules to the user
			$userModules = RAPID::getInstance()->loadUserModules($username);
			while($rowModules = mysqli_fetch_array($userModules)) {
				echo '<li class=""><a href="'.$rowModules['Module_path'].'" onclick=\'return loadIframe("iFrm_Main", this.href)\'>'.$rowModules['Module_name'].'</a></li>';
			}
		?>
       <!-- <li class="" id="li_DiesetList"><a href="#"><img src='./Images/Folder.png' style='width:20px;'>DIE-SET</a></li>
        <li class="" id="li_OrderReceive"><a href="#"><img src='./Images/Folder.png' style='width:20px;'>P.O. RECEIVE</a></li>
        <li class="" id="li_DeliveryConfirmation"><a href="#"><img src='./Images/Folder.png' style='width:20px;'>DELIVERY CONFIRMATION</a></li>
        <li class="" id="li_Shipment"><a href="#"><img src='./Images/Folder.png' style='width:20px;'>SHIPMENT</a></li>
        <li class="dropdown">
          
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

<iframe src="./home.php" id="iFrm_Main" style="padding:1%;"></iframe>

</body>
</html>