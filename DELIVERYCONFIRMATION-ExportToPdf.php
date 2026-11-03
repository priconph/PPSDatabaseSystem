<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
//$dateReset = '2000-01-01';
$startDate = $_GET['startdate'];
$endDate = $_GET['enddate'];
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
	<div>
		<table id="tbl_PDF" class="table" border="1" style="font-size:12px;">
			<thead>
				<tr>
					<th>DATE ISSUED</th>
					<th>CATEGORY</th>
					<th>PRODUCT PO NO</th>
					<th>ITEM CODE</th>
					<th>ITEM NAME</th>
					<th>ORDER NO</th>
					<th>SHIP OUT QTY</th>
					<th>UNIT PRICE</th>
					<th>AMOUNT</th>
					<th>REMARKS</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$getAllDeliveryConfirmation = PPSDBS::getInstance()->exportShipmentRecord('date',$startDate, $endDate, '');
					while($row = mysqli_fetch_array($getAllDeliveryConfirmation)) {
						$amount = number_format($row['ActualSO'] * $row['Price'],2,'.','');
						echo '
							<tr>
								<td>'.$row['DateIssued'].'</td>
								<td>'.$row['Category'].'</td>
								<td>'.$row['ProductPONo'].'</td>
								<td>'.$row['ItemCode'].'</td>
								<td>'.$row['ItemName'].'</td>
								<td>'.$row['OrderNo'].'</td>
								<td>'.$row['ActualSO'].'</td>
								<td>'.$row['Price'].'</td>
								<td>$'.$amount.'</td>
								<td>'.$row['Remarks'].'</td>
							</tr>
						';
					}
				?>
			</tbody>
		</table>
	</div>
</body>
<script>
$('document').ready(function(){
	window.print();
	window.location.href = "./deliveryConfirmation.php";
});
</script>
</html>