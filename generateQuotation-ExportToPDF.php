<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
session_start();
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
		$user = PPSDBS::getInstance()->getNameByUserName($username);
		if($rowName = mysqli_fetch_array($user)) {
			$user=$rowName['name'];
		}
	}
	$To = $_GET['To'];
	$Attn = $_GET['Attn'];
	$Cc = $_GET['Cc'];
	$Subject = $_GET['Subject'];
	$NotedBy = $_GET['NotedBy'];
	$CheckedBy = $_GET['CheckedBy'];
	$ApprovedBy = $_GET['ApprovedBy'];
	$PreparedBy = $user;
	$Date = $_GET['Date'];
	$ControlNumber = $_GET['ControlNumber'];
	$dateToday = date("M. d, Y");
	$dateFileName = date("Y-m-d");
	
	//load the employee position
	$userPosition = PPSDBS::getInstance()->getPositionByName($user);
	if($rowPosition = mysqli_fetch_array($userPosition)) {
		$userPosition=$rowPosition['Position'];
	}
	$NotedByPosition = PPSDBS::getInstance()->getPositionByName($NotedBy);
	if($NotedByrowPosition = mysqli_fetch_array($NotedByPosition)) {
		$NotedByPosition=$NotedByrowPosition['Position'];
	}
	$CheckedByPosition = PPSDBS::getInstance()->getPositionByName($CheckedBy);
	if($CheckedByrowPosition = mysqli_fetch_array($CheckedByPosition)) {
		$CheckedByPosition=$CheckedByrowPosition['Position'];
	}
	$ApprovedByPosition = PPSDBS::getInstance()->getPositionByName($ApprovedBy);
	if($ApprovedByrowPosition = mysqli_fetch_array($ApprovedByPosition)) {
		$ApprovedByPosition=$ApprovedByrowPosition['Position'];
	}
	$PreparedByPosition = PPSDBS::getInstance()->getPositionByName($PreparedBy);
	if($PreparedByrowPosition = mysqli_fetch_array($PreparedByPosition)) {
		$PreparedByPosition=$PreparedByrowPosition['Position'];
	}
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<style>
#tbl_PDF td{
font-size:7;
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
	<table>
		<tr>
			<td>To: </td>
			<td><?php echo $To; ?></td>
		</tr>
		<tr>
			<td>Attn: </td>
			<td><?php echo $Attn; ?></td>
		</tr>
		<tr>
			<td>Cc: </td>
			<td><?php echo $Cc; ?></td>
		</tr>
		<tr>
			<td>Date: </td>
			<td><?php echo $Date; ?></td>
		</tr>
		<tr>
			<td>Subject: </td>
			<td><?php echo $Subject; ?></td>
		</tr>
	</table>
	<br>
	<div>
		<table id="tbl_InternalInvoiceMain" class="table" border="1" style="font-size:12px;">
			<thead>
				<tr>
					<th>Item #</th>
					<th>Device</th>
					<th>Material Name</th>
					<th>Partscode</th>
					<th>Resin Cost</th>
					<th># of Cavity</th>
					<th>Product Wt.(g)</th>
					<th>Sprue Wt.(g)</th>
					<th>Shot Wt.(g)</th>
					<th>Labor Cost/hr($)</th>
					<th>Cycle Time</th>
					<th>Process Cost</th>
					<th>Material Cost(per piece)</th>
					<th>2nd Process Time(secs)</th>
					<th>2nd Process Cost</th>
					<th>Profit</th>
					<th>Molding Product Quotation($)</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$counter = 1;
					$result = PPSDBS::getInstance()->ExportToExcelGenerateQuotation($ControlNumber);
					while($row = mysqli_fetch_array($result)){
						echo '
							<tr>
								<td>'.$ItemNo.'</td>
								<td>'.$row['DeviceName'].'</td>
								<td>'.$row['Material'].'</td>
								<td>'.$row['R3Code'].'</td>
								<td>'.$row['ResinCost'].'</td>
								<td>'.$row['NoOfCav'].'</td>
								<td>'.$row['ProductWgt'].'</td>
								<td>'.$row['SprueWgt'].'</td>
								<td>'.$row['ShotWgt'].'</td>
								<td>'.$row['LaborCost'].'</td>
								<td>'.$row['CycleTime'].'</td>
								<td>'.$row['ProcessCost'].'</td>
								<td>'.$row['MaterialCost'].'</td>
								<td>'.$row['SecondProcessTime'].'</td>
								<td>'.$row['SecondProcessCost'].'</td>
								<td>'.$row['Profit'].'</td>
								<td>'.$row['MoldingProdQuot'].'</td>
							</tr>
						';
						$counter++;
					}
				?>
				<tr></tr>
				<tr>
					<td colspan="17">
						<table style='width:100%'>
							<tr>
								<td style='width:25%'>Prepared by:</td>
								<td style='width:25%'>Checked by:</td>
								<td style='width:25%'>Noted by:</td>
								<td style='width:25%'>Approved by:</td>
							</tr>
							<tr>
								<td style='width:25%'><?php echo $user; ?></td>
								<td style='width:25%'><?php echo $CheckedBy; ?></td>
								<td style='width:25%'><?php echo $NotedBy; ?></td>
								<td style='width:25%'><?php echo $ApprovedBy; ?></td>
							</tr>
							<tr>
								<td style='width:25%'><?php echo $userPosition; ?></td>
								<td style='width:25%'><?php echo $CheckedByPosition; ?></td>
								<td style='width:25%'><?php echo $NotedByPosition; ?></td>
								<td style='width:25%'><?php echo $ApprovedByPosition; ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
<script>
$('document').ready(function(){
	window.print();
	window.location.href = "./generateQuotation.php";
});
</script>
</html>