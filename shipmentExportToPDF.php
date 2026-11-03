<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
//$dateReset = '2000-01-01';
$controlNumber = $_GET['controlNumber'];
$getInternalInvoiceInfo = PPSDBS::getInstance()->getShipmentRecord('controlnumber','','',$controlNumber);
while($info = mysqli_fetch_array($getInternalInvoiceInfo)) {
	$date = $info['DateCreated'];
	$revNo = $info['RevNo'];
	$soldTo = $info['SoldTo'];
	$shippedBy = $info['ShippedBy'];
}
echo '<input type="date" id="txt_dateReset" style="display:none;" value="'.$dateReset.'">';
//REMINDER: MAG ADD KA NG DELETE NA AJAX SA MISMONG REMOVE
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<style>
#tbl_PDF td{
font-size:12;
}
#tbl_PDF div{
  border-bottom: 1px solid;
}
</style>
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
	<?php
		echo '
	<div>
		<h4><b>PRICON MICROELECTRONICS, INC</b></h4><br>
		<h4><center>INTERNAL INVOICE</center></h4>
	</div>
	<div style="margin-left:70%;font-size:12px;">
		Ctrl No: <u>'.$controlNumber.'</u><br>
		Date: <u>'.$date.'</u><br>
		Rev. No: <u>'.$revNo.'</u><br>
	</div>
	<div style="position:static;width:100%;">
		<div style="position:relative;float:left;width:60%;">
			<label style="margin-left:20%;">Sold to:</label>
		</div>
		<div style="position:relative;float:left;">
			<label >Shipped by:</label> 
		</div>
	</div>
	<div style="position:static;width:100%;">
		<div style="position:relative;float:left;width:60%;">
			<label style="margin-left:20%;"><u>'.$soldTo.'</u></label>
		</div>
		<div style="position:relative;float:left;">
			<label><u>'.$shippedBy.'</u></label> 
		</div>
	</div>
	<br><br><br>
	';
	?>
	<div>
		<table id="tbl_InternalInvoiceMain" class="" width="100%" border="1" style="font-size:10px;">
			<thead>
				<tr>
					<th style="width:10px;">Item #</th>
					<!--<th style="width:100px;">CATEGORY</th>-->
					<th>PRODUCT PO #</th>
					<!--<th>ITEM CODE</th>-->
					<th style="width:100px;">PRODUCT CODE</th>
					<!--<th>ITEM NAME</th>-->
					<th style="width:150px;">DESCRIPTION</th>
					<th style="width:100px;">PO NUMBER</th>
					<th style="width:50px;">QUANTITY</th>
					<th style="width:50px;">U/PRICE</th>
					<th style="width:50px;">TOTAL</th>
					<!--<th>REMARKS</th>-->
				</tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					$total_amount = 0;
					$getItemTransaction = PPSDBS::getInstance()->getShipmentRecord('transaction','','',$controlNumber);
					while($rowInfo = mysqli_fetch_array($getItemTransaction)) {
						$qty  = $qty + $rowInfo['ShipOutQty'];
						$grandTotal  = $grandTotal + $rowInfo['Amount'];
						echo '
							<tr>
								<td>'.$counter.'</td>';				
						if($rowInfo['Category'] == "GRINDING-SPECIAL CASE"){
							echo   '<td>'.$rowInfo['Category'].'('.$rowInfo['ProductPONo'].')'.'</td>';
						}else{		
							echo   '<td>'.$rowInfo['ProductPONo'].'</td>';
						}
						
						$shipout_qty = str_replace(',','',$rowInfo['ShipOutQty']);
						$unit_price = str_replace(',','',$rowInfo['UnitPrice']);
						// $amount 	= str_replace(',','',$rowInfo['Amount']);
						$amount     = $shipout_qty * $unit_price;
						$total_amount = $total_amount + $amount;
						echo   '<td>'.$rowInfo['ItemCode'].'</td>
								<td>'.$rowInfo['ItemName'].'</td>
								<td>'.$rowInfo['OrderNo'].'</td>
								<td>'.$rowInfo['ShipOutQty'].'</td>
								<td>'.number_format($unit_price, 4, '.', '').'</td>
								<td>$'.number_format($amount, 2, '.', '').'</td>
								
							</tr>
						';
						//<td>'.$rowInfo['Category'].'</td>
						//<td>'.$rowInfo['Remarks'].'</td>
						$counter++;
					}
				?>
				<tr>
					<td colspan='3' align='right'></td>
					<td></td>
					<td></td>
					<td><?php echo $qty; ?>&nbsp; pcs</td>
					<td></td>
					<td>
					$&nbsp;
					<?php 
						$grandTotal_decimal = explode('.',$grandTotal);
						$decimal_p = $grandTotal_decimal[1];
						// echo number_format($grandTotal_decimal[0]).'.'.$decimal_p[0].$decimal_p[1];
						echo number_format($total_amount, 2, '.', '');
					?>
					</td>
				</tr>
				<tr>
					<td colspan="10">
						<div style="position:static;width:100%;">
							<div style="position:relative;float:left;width:60%;">
								<label style="margin-left:20%;">Prepared by:</label>
							</div>
							<div style="position:relative;float:left;">
								<label>Checked by:</label> 
							</div>
						</div>
						<div style="position:static;width:100%;">
							<div style="position:relative;float:left;width:60%;">
								<?php
									session_start();
									$username = $_SESSION['username'];
									$user = '';
									$position = '';
									if($username == 'marilag'){
										$user 		= 'Marie de Guzman';
										$position 	= 'TS - PPC Clerk';
									}else if($username == 'katrefo'){
										$user 		= 'Kate Reforma';
										$position 	= 'PPS-PPC MH';
									}else{
										$user 		= 'No Logged in User';
										$position 	= 'NOT SET';
									}
								?>
								<label style="margin-left:20%;">___________________<br><?php echo $user; ?><br><?php echo $position; ?></label>
							</div>
							<div style="position:relative;float:left;">
								<label>__________________<br>Rosalinda Sunga<br>PPC General Manager</label>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
<script>
$('document').ready(function(){
	window.print();
	window.location.href = "./shipment.php";
});
</script>
</html>