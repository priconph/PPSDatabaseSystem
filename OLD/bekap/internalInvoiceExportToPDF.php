<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
//$dateReset = '2000-01-01';
$controlNumber = $_GET['controlNumber'];
$getInternalInvoiceInfo = PPSDBS::getInstance()->getInternalInvoiceInfo($controlNumber);
while($info = mysqli_fetch_array($getInternalInvoiceInfo)) {
	$date = $info['Date'];
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
		<table id="tbl_InternalInvoiceMain" class="table" border="1" style="font-size:12px;">
			<thead>
				<tr>
					<th>Item #</th>
					<th>PRODUCT CODE</th>
					<th>DESCRIPTION</th>
					<th>PO Number</th>
					<th>QUANTITY</th>
					<th>U/PRICE</th>
					<th>TOTAL</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					$getItemTransaction = PPSDBS::getInstance()->getInternalInvoiceItemTransaction($controlNumber);
					while($rowInfo = mysqli_fetch_array($getItemTransaction)) {
						$qty  = $qty + $rowInfo['Quantity'];
						$grandTotal  = $grandTotal + $rowInfo['Total'];
						echo '
							<tr>
								<td>'.$counter.'</td>
								<td>'.$rowInfo['ProductCode'].'</td>
								<td>'.$rowInfo['Description'].'</td>
								<td>'.$rowInfo['PONumber'].'</td>
								<td>'.$rowInfo['Quantity'].' pcs</td>
								<td>$ '.$rowInfo['UnitPrice'].'</td>
								<td>$ '.$rowInfo['Total'].'</td>
							</tr>
						';
						$counter++;
					}
				?>
				<tr>
					<td colspan='3' align='right'>TOTAL</td>
					<td></td>
					<td><?php echo $qty; ?>&nbsp; pcs</td>
					<td></td>
					<td>$&nbsp;<?php echo $grandTotal; ?></td>
				</tr>
				<tr>
					<td colspan="7">
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
								<label style="margin-left:20%;">___________________<br>name here</label>
							</div>
							<div style="position:relative;float:left;">
								<label>__________________<br>name here</label>
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
	window.location.href = "./internalInvoice.php";
});
</script>
</html>