<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$dateReset = '2000-01-01';
$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
$yecControlNoOrDeviceName = $_GET['yecControlNoDeviceName'];
$Category = $_GET['Category'];
$getDiesetSearch = PPSDBS::getInstance()->getDiesetSearch($yecControlNoOrDeviceName,$Category);
if($row = mysqli_fetch_array($getDiesetSearch)) {
	$Date = $row['Date'];
	$DeviceStatus = $row['DiesetStatus'];
	$YECControlNo = $row['YECMoldCtrlNo'];
	//$R3Code = $row['R3Code'];
	//$EXPCode = $row['EXPCode'];
	$DeviceName = $row['DeviceName'];
	$DrawingNo = $row['DrawingNo'];
	$Rev = $row['Rev'];
	$MoldClass = $row['MoldClass'];
	$DieNo = $row['DieNo'];
	$NoOfCav = $row['NoOfCav'];
	$CavNo = $row['CavNo'];
	$ChangingPart = $row['ChangingPart'];
	$DiesetSize = $row['DiesetSize'];
	$DiesetWgt = $row['DiesetWgt'];
	$UnitWgt = $row['UnitWgt'];
	$SprueWgt = $row['SprueWgt'];
	$ShotWgt = $row['ShotWgt'];
	$CycleShot = $row['CycleShot'];
	$FixedAssetNo = $row['FixedAssetNo'];
	$DateManufactured = $row['DateManufactured'];
	$MoldMake = $row['MoldMake'];
	$ToolLife = $row['ToolLife'];
	$ToolShot = $row['ToolShot'];
	$ToolLifePMI = $row['ToolLifePMI'];
	$MaintenanceCycle = $row['MaintenanceCycle'];
	$InjectionMachine = $row['InjectionMachine'];
	$Material = $row['Material'];
	$ToneColor = $row['ToneColor'];
	$Remarks = $row['Remarks'];
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
	<table style="width:100%;" border=1 id="tbl_PDF">
		<tr>
			<td colspan=3>
				<div>
					<label>Die-set Transfer and Material Checksheet</label><label style="position:absolute;right:23%;">Date: <?php echo date('Y/m/d',strtotime($Date)); ?></label>
				</div>
				<div>
					<label style="position:absolute;right:12%;font-size:10;" >YEC Molding Prd'n Control No. <?php echo $YECControlNo; ?></label>
				<br>
					<label style="margin-right:2%;">Transfer to </label><input type="checkbox" > Pricon Microelectronics, Inc.</label>
					<div>
									<input type="checkbox" style="margin-left:63%;"  checked> NEW DIE-SET
									<br>
									<input type="checkbox" style="margin-left:63%;" > RETURN DIE-SET
								</div>
					<?php
						/*if($DeviceStatus == 'NEW DIE-SET') {
							echo '
								<div>
									<input type="checkbox" style="margin-left:26.5%;"  checked> NEW DIE-SET
									<br>
									<input type="checkbox" style="margin-left:62.1%;" > RETURN DIE-SET
								</div>
							';
						} else if($DeviceStatus == 'RETURN DIE-SET') {
							echo '
								<div>
									<input type="checkbox" style="margin-left:26.5%;" > NEW DIE-SET
									<br>
									<input type="checkbox" style="margin-left:62.1%;"  checked> RETURN DIE-SET
								</div>
							';
						} else {
							echo '
								<div>
									<input type="checkbox" style="margin-left:26.5%;" > NEW DIE-SET
									<br>
									<input type="checkbox" style="margin-left:62.1%;" > RETURN DIE-SET
								</div>
							';
						}*/
					?></div>
				<div>
					<label style="width:20%;margin-right:2%;">Device Name:</label><label style="width:20%"><?php echo $DeviceName;?></label>
				</div>
				<div>
					<label style="width:20%;margin-right:2%;">Drawing Number:</label><label style="width:20%"><?php echo $DrawingNo;?></label>
					<label style="width:10%;margin-right:2%;">Rev:</label><label style="width:20%"><?php echo $Rev;?></label>
				</div>
				<div>
					<label style="width:18%;margin-right:2%;">Mold Classification:</label>
					<?php
						if($MoldClass == 'Present Mold') {
							echo '
								<input type="checkbox" style=""  checked> Present Mold
								<input type="checkbox" style="" > Renewal Mold
								<input type="checkbox" style="" > Additional Mold
							';
						} else if($MoldClass == 'Renewal Mold') {
							echo '
								<input type="checkbox" style="" > Present Mold
								<input type="checkbox" style=""  checked> Renewal Mold
								<input type="checkbox" style="" > Additional Mold
							';
						} else if($MoldClass == 'Additional Mold') {
							echo '
								<input type="checkbox" style="" > Present Mold
								<input type="checkbox" style="" > Renewal Mold
								<input type="checkbox" style=""  checked> Additional Mold
							';
						} else {
							echo '
								<input type="checkbox" style="" > Present Mold
								<input type="checkbox" style="" > Renewal Mold
								<input type="checkbox" style="" > Additional Mold
							';
						}
					?>
					<label style="width:7%;margin-right:2%;margin-left:5%;">Die No:</label><label style="width:20%"><?php echo $DieNo;?></label>
				</div>
				<div>
					<label style="width:20%;margin-right:2%;">Number of Cavity:</label><label style="width:10%"><?php echo $NoOfCav;?></label>
					<label style="width:10%;margin-right:2%;">Cav No:</label><label style="width:10%"><?php echo $CavNo;?></label>
					<label style="width:15%;margin-right:2%;">Changing Part:</label>
					<?php
						if($ChangingPart == 'Have') {
							echo '
								<input type="checkbox" style=""  checked> Have
								<input type="checkbox" style="" > None
							';
						} else if($ChangingPart == 'None') {
							echo '
								<input type="checkbox" style="" > Have
								<input type="checkbox" style=""  checked> None
							';
						} else {
							echo '
								<input type="checkbox" style="" > Have
								<input type="checkbox" style="" > None
							';
						}
					?>
					
				</div>
				<div>
					<label style="width:20%;margin-right:2%;">Die-set Size:</label><label style="width:10%"><?php echo $DiesetSize; ?></label>
					<label style="width:20%;margin-right:2%;">Die-set Weight:</label><label style="width:10%"><?php echo $DiesetWgt.' kg'; ?></label>
				</div>
				<div>
					<label style="width:20%;margin-right:2%;">Unit Weight:</label><label style="width:10%"><?php echo $UnitWgt; ?></label>
					<label style="width:20%;margin-right:2%;">Sprue Weight:</label><label style="width:10%"><?php echo $SprueWgt; ?></label>
					<label style="width:20%;margin-right:2%;">1Shot Weight:</label><label style="width:10%"><?php echo $ShotWgt.' g'; ?></label>
				</div>
				<div>
					<label style="width:20%;margin-right:2%;">Cycle time/ 1shot:</label><label style="width:10%"><?php echo $CycleShot; ?></label>
					<label style="width:20%;margin-right:2%;">Fixed Asset No:</label><label style="width:10%"><?php echo $FixedAssetNo; ?></label>
				</div>
			</td>
		<tr>
			<td colspan="3">.</td>
		</tr>
		<tr>
			<td style="width:33%;">
				Date Manufactured
			</td>
			<td style="width:33%;">
				<?php echo date('Y/m/d',strtotime($DateManufactured)); ?>
			</td>
			<td style="width:33%;">
				Mold Mak <?php echo $MoldMake; ?>
			</td>
		</tr>
		<tr>
			<td>
				Tool Life
			</td>
			<td>
				<?php echo number_format($ToolLife).' PCS'; ?>
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				Tool Shot
			</td>
			<td>
				<?php echo number_format($ToolShot).' PCS'; ?>
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				Tool Life in PMI
			</td>
			<td>
				<?php echo number_format($ToolLifePMI).' PCS'; ?>
			</td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td>
				Maintenance Cycle
			</td>
			<td>
				<?php echo $MaintenanceCycle. ' S'; ?>
			</td>
			<td>
				<!--box for added input do not forget to assign width for this on coding-->
				<div></div>
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				<?php echo $InjectionMachine; ?>
			</td>
			<td>
				Kind of Injection Machine
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				<?php echo $Material; ?>
			</td>
			<td>
				Kind of Material
			</td>
		</tr>
		<tr>
			<td>
				
			</td>
			<td>
				<?php echo $ToneColor; ?>
			</td>
			<td>
				Tone of Color
			</td>
		</tr>
	</table>
</body>
<script>
$('document').ready(function(){
	window.print();
	window.location.href = "<?php echo $url; ?>";
});
</script>
</html>