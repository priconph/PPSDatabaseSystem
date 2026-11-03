<?php
require_once('./includes.php');
//require_once('./ppsdbs-oop.php'); //the real thing
require_once('./ppsdbs-oop.php'); //for testing
$dateToday = date("m-d H:i");
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var oTable = $('#TableID HERE').dataTable({
			paginate:false,
			"autoWidth": false
	});
	//START DATE VALIDATION
	$('#txtStartDate').change(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (startDate>endDate){
				$('#txtStartDate').val(endDate);  
			}
	});
	//END DATE VALIDATION
	$('#txtEndDate').change(function(){
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
			if (endDate<startDate){
				$('#txtEndDate').val(startDate);
			}
	});
	
	$('#btn_PRExportToExcel').click(function(){ ExportToExcel() });
	
});
function SavePrTransaction(){
	$(document).ready(function(){
		
	});
}

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

function ExportToExcel(){
	window.location.href = "./generateForLoadingPR-ExcelExport.php";
}
</script>
</head>
<body>

<div class="panel panel-primary">
	<div class="panel-heading">FOR LOADING MONITORING</div>
	<div class="panel-body">
		<div class="panel panel-info">
			<div class="panel-heading">FOR LOADING</div>
			<div class="panel-body">
				
				<h4>PR Information</h4>
				<table id="tbl_ResinMain2" class="table" border="1">
					<thead>
						<tr>
							<th>P.O. No.</th>
							<th>Parts Code</th>
							<th>Device Name</th>
							<th>Drawing No. <label style="font-size:12px;">as of <?php echo $dateToday; ?></label></th>
							<th>Revision No.</th>
							<th>Material Type</th>
							<th>P.O. Qty</th>
							<th>WIP</th>
							<th>FGS</th>
							<th>TOTAL<label style="font-size:12px;">TOTAL=WIP+FGS</label></th>
							<th>For Prod'n<label style="font-size:12px;">For Loading=PO Balance-Total</label></th>
							<th>Shot Weight</th>
							<th>Cav. No.</th>
							<th>Cycle Time</th>
						</tr>
					</thead>
					<tbody>
						<?php
							//$TotalItemForPR = 0;
							//$TotalAddKgsNeeded = 0;
							$DeviceFGSList = array();
							$ForPR = 0;
							$MaterialNameOld = "";
							$result = PPSDBS::getInstance()->MaterialsForLoading();
							 while($row = mysqli_fetch_array($result)){
								$total = $row["wip"] + $row["fgs"];
								$forLoading = $row["POBalance"] - $total;
								echo '<tr>';
								echo '<td>'.$row["OrderNo"].'</td>';
								echo '<td>'.$row["ItemCode"].'</td>';
								echo '<td>'.$row["DeviceName"].'</td>';
								echo '<td>'.$row["DrawingNo"].'</td>';
								echo '<td>'.$row['Rev'].'</td>';
								echo '<td>'.$row['Material'].'</td>';
								echo '<td>'.$row['POBalance'].'</td>';
								echo '<td>'.$row["wip"].'</td>';
								echo '<td>'.$row["fgs"].'</td>';
								echo '<td>'.$total.'</td>';
								echo '<td>'.$forLoading.'</td>';
								echo '<td>'.number_format($row["ShotWgt"], 4,'.','').'</td>';
								echo '<td>'.$row["CavNo"].'</td>';
								echo '<td>'.$row["CycleShot"].'</td>';
								echo '</tr>';					
						 }
							
						?>
					</tbody>
				<button id="btn_PRExportToExcel" class="btn btn-success" style="float:right;">EXPORT TO EXCEL</button>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modalNameHere" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:scroll">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">TITLE</h4>
			</div>
			<div class="modal-body">
				<p>CONTENT</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btnYes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>