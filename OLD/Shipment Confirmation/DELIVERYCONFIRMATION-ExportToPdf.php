<?php
	require_once('ppsdbs-oop.php');
	$startDate = $_GET['startdate'];
	$endDate = $_GET['enddate'];
?>

<html>
<head>
	<script>
		$('document').ready(function(){
			window.print();
			window.location.href = "./deliveryConfirmation.php";
		});
	</script>
	<style>
		table {
			border-collapse: collapse;
		}
		td, th{
			border-style: solid;
			border-width: 1px;
		}
	</style>
</head>
<body onload='print()'>
	<table>
		<tr>
			<th>Date Issued</th>
			<th>Category</th>
			<th>Product P.O. No.</th>
			<th>Item Code</th>
			<th>Item Name</th>
			<th>Order No.</th>
			<th>Order Balance</th>
			<th>Target for SO</th>
			<th>Shipment Date</th>
		</tr>
		<?php
			$getDeliveryConfirmationReport = PPSDBS::getInstance()->getDeliveryConfirmation('date',$startDate,$endDate,'');
			while($row = mysqli_fetch_array($getDeliveryConfirmationReport)) {
				echo '
					<tr>
						<td>'.$row['DateIssued'].'</td>
						<td>'.$row['Category'].'</td>
						<td>'.$row['ProductPONo'].'</td>
						<td>'.$row['ItemCode'].'</td>
						<td>'.$row['ItemName'].'</td>
						<td>'.$row['OrderNo'].'</td>
						<td>'.$row['OrderBalance'].'</td>
						<td>'.$row['TargetSO'].'</td>
						<td>'.$row['ShipmentDate'].'</td>
					</tr>
				';
			}
		?>
		
	</table>
</body>
</html>