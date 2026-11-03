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
			window.location.href = "poReceived.php";
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
			<th>Supplier</th>
			<th>YEC P.O.</th>
			<th>Item Code</th>
			<th>Name</th>
			<th>Order No.</th>
			<th>Schd. Qty</th>
			<th>Purchase U/Cost</th>
			<th>Purc. Order Amount</th>
			<th>Date Issued</th>
			<th>PPD Reply</th>
			<th>Date Needed</th>
			<th>Remarks</th>
			<th>Drawing No.</th>
		</tr>
		<?php
			$getPOReceived = PPSDBS::getInstance()->viewPOReceived('date','',$startDate, $endDate, '');
			while($row = mysqli_fetch_array($getPOReceived)) {
				echo '
					<tr>
						<td>'.$row['Supplier'].'</td>
						<td>'.$row['YECPO'].'</td>
						<td>'.$row['ItemCode'].'</td>
						<td>'.$row['Name'].'</td>
						<td>'.$row['OrderNo'].'</td>
						<td>'.$row['SchdQty'].'</td>
						<td>'.$row['PurchaseUnitCost'].'</td>
						<td>'.$row['PurchaseOrderAmt'].'</td>
						<td>'.$row['DateIssued'].'</td>
						<td>'.$row['PPDReply'].'</td>
						<td>'.$row['DateNeeded'].'</td>
						<td>'.$row['Remarks'].'</td>
						<td>'.$row['DrawingNo'].'</td>
					</tr>
				';
			}
		?>
		
	</table>
</body>
</html>