<?php
	require_once('ppsdbs-oop.php');
	$controlNumber = $_GET['controlNumber'];
	session_start();
	if(isset($_SESSION['username'])) {
		$username = $_SESSION['username'];
	} else {
		//echo "Please log in!";
		$username = 'tempUser';
	}
	$transactionInfo = PPSDBS::getInstance()->generateExcelReport($controlNumber);
	if($rowTransaction = mysqli_fetch_array($transactionInfo)) {
		$customer = "CUSTOMER: " . $rowTransaction['Customer'];
		$date = Date('l, F d,Y ',strtotime($rowTransaction['Date']));
	}
?>

<html>
<head>
	<style type="text/css">
		#transTable td{
			border: 1px solid black;
		}
	</style>
	<script>
		function pdfCreator() {
			window.print();
			window.location.href = './shipmentConfirmation.php';
		}
	</script>
</head>
<body onload="pdfCreator();">
	<table style="width:100%;border:1px solid black;" id="tbl_PDF">
		<tr>
			<td colspan='6'>
				<div>
					<label>TS - MOLDING</label>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='6'>
				<div>
					<label>Shipment Confirmation</label>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='6'>
				<div>
					<label><?php echo $date; ?></label>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan='6'>
				<br>
			</td>
		</tr>
		<tr>
			<td colspan ='2'>
				<label><?php echo $customer; ?></label>
			</td>
			<td colspan='4'>
				<label>DEC. SALES</label>
			</td>
		</tr>
		<tr>
			<td colspan='6'>
				<br>
			</td>
		</tr>
		<tr border='1'>
			<td colspan='6'>
				<table style="width:100%;border-collapse: collapse;" id="transTable">
					<tr>
						<td style="width:16%;" align="center">PR No.</td>
						<td style="width:16%;" align="center">Parts Code</td>
						<td style="width:16%;" align="center">Parts Name</td>
						<td style="width:16%;" align="center">Actual</td>
						<td style="width:16%;" align="center"></td>
						<td style="width:16%;" align="center">Total</td>
					</tr>
					<?php
						$getAllShipmentTransaction = PPSDBS::getInstance()->generateExcelTransactionReport($controlNumber);
						while($row = mysqli_fetch_array($getAllShipmentTransaction)) {
							$qty = $qty + $row['Quantity'];
							$total = $total + $row['Total'];
							$grandTotal = $grandTotal + $row['Total'];
							echo "<tr>";
								echo '
									<td style="width:16%;" align="center">'.$row['PRNo'].'</td>
									<td style="width:16%;" align="center">'.$row['Partscode'].'</td>
									<td style="width:16%;" align="center">'.$row['Partsname'].'</td>
									<td style="width:16%;" align="center">'.$row['Quantity'].'</td>
									<td style="width:16%;" align="center">'.$row['Price'].'</td>
									<td style="width:16%;" align="center">'.$row['Total'].'</td>
								';
							echo "</tr>";
						}
					?>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td align="center"><strong><?php echo $qty; ?></strong></td>
						<td></td>
						<td align="center"><strong><?php echo $total; ?></strong></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan='6'>
				<br>
			</td>
		</tr>
	</table>
</body>
</html>