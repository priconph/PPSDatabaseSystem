<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
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
</head>
<body>
	<table style="width:100%;" border=1 id="tbl_PDF">
		<tr>
			<th>Date Issued</th>
			<th>Category</th>
			<th>Product P.O. No</th>
			<th>Item Code</th>
			<th>Item Name</th>
			<th>Order No.</th>
			<th>Order Balance</th>
			<th>Target for S/O</th>
			<th>Shipmen Date</th>
		</tr>
		<?php
			$exportNoRecordDeliveryConfirmation = PPSDBS::getInstance()->exportNoRecordDeliveryConfirmation();
			while($row = mysqli_fetch_array($exportNoRecordDeliveryConfirmation)) {
				echo '
					<tr>
						<td>'.$row['DateIssued'].'</td>
						<td>'.$row['Category'].'</td>
						<td>'.$row['ProductPONo'].'</td>
						<td>'.$row['ItemCode'].'</td>
						<td>'.$row['ItemName'].'</td>
						<td>'.$row['OrderNo'].'</td>
						<td>'.$row['POBalance'].'</td>
						<td></td>
						<td></td>
					</tr>
				';
			}
			$exportRecordsDeliveryConfirmation = PPSDBS::getInstance()->exportRecordsDeliveryConfirmation();
			while($row = mysqli_fetch_array($exportRecordsDeliveryConfirmation)) {
				echo '
					<tr>
						<td>'.$row['DateIssued'].'</td>
						<td>'.$row['Category'].'</td>
						<td>'.$row['ProductPONo'].'</td>
						<td>'.$row['ItemCode'].'</td>
						<td>'.$row['ItemName'].'</td>
						<td>'.$row['OrderNo'].'</td>
						<td>'.$row['POBalance'].'</td>
						<td>'.$row['TargetSO'].'</td>
						<td>'.$row['ShipmentDate'].'</td>
					</tr>
				';
			}
		?>
	</table>
</body>
<script>
$('document').ready(function(){
	window.print();
	window.location.href = "<?php echo $url; ?>";
});
</script>
</html>