<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date('Y-m-d');
/*
Coding
Notes: Change the color of the button generate pr if existing resin will be lower than the amount needed to produce the new P.O.
*/
$getItemCodeList = PPSDBS::getInstance()->getItemCodeListReal();
echo "<datalist id='ItemCodeList'>";
echo "<option>-VIEW ALL-</option>";
while($rowItemCodeList = mysqli_fetch_array($getItemCodeList)) {
	echo '<option>'.$rowItemCodeList['ItemCode'].'</option>';
}
echo "</datalist>";
//$checker = 0;

?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var oTable = $('#tbl_POReceivedMain').dataTable({
		paginate:false,
		"autoWidth": false
	});
	
	$('#btn_ImportCSV').click(function(){
		$('#modal_ImportPO').modal('show');
	});
	
	$('#btn_AddNewPO').click(function(){
		window.location.href = "./addPOReceived.php";
	});
	
	$('#btn_ResinComputation').click(function(){
		window.location.href = "./generateResinPR.php"
		//$('#modal_GenerateResinPR').modal('show');
	});
	
	$('#btn_UpdatePO').click(function(){
		var supplier = $('#supplier').val();
		var yecPO = $('#yecPO').val();
		var itemCode = $('#itemCode').val();
		var name = $('#name').val();
		var orderNo = $('#orderNo').val();
		var schedQty = $('#schedQty').val();
		var purchaseUCost = $('#purchaseUCost').val();
		var purchaseOrderAmount = $('#purchaseOrderAmount').val();
		var dateIssued = $('#dateIssued').val();
		var ppdReply = $('#ppdReply').val();
		var dateNeeded = $('#dateNeeded').val();
		var drawingNo = $('#drawingNo').val();
		var remarks = $('#remarks').val();
					
		//alert(workcenter+wcName+supplier+supplierName+yecPO+itemCode+name+orderNo+schedQty+purchaseUCost+purchaseOrderAmount+currency+deliLocation+deliLocName+dateIssued+ppdReply+dateNeeded+remarks+drawingNo+classification);
		var data = {
			"action" : updatePOReceive,
			"supplier" : supplier,
			"yecPO" : yecPO,
			"itemCode" : itemCode,
			"name" : name,
			"orderNo" : orderNo,
			"schedQty" : schedQty,
			"purchaseUCost" : purchaseUCost,
			"purchaseOrderAmount" : purchaseOrderAmount,
			"dateIssued" : dateIssued,
			"ppdReply" : ppdReply,
			"dateNeeded" : dateNeeded,
			"drawingNo" : drawingNo,
			"remarks" : remarks,
		}
		
		data = $.param(data);
			$.ajax({
				type:"POST",
				dataType:"json",
				url:"ppsdbsHandler.php",
				data:data,
				success:function(data) {
					alert("Successfully Added!");
					window.location.href = "./poReceived.php";
				}
			});
	});
	
	$('#btn_Delete').click(function () {
		alert("Successfully Deleted!");
		window.location.href = "./poReceived.php";
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
	
	$('#tbl_POReceivedMain').on("dblclick","tbody tr", function (event) { 
		var itemCode = $(this).find('.itemCode').html();
		window.location.href = "./updatePOReceived.php?itemCode="+itemCode; // wala pa yung file bale gagayahin lang yung format ni addPOReceived pag tapos na yung add
	});
	
	$('#btn_ExportToExcel').click(function () {
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = './PORECEIVED-ExportToExcel.php?startdate='+startDate+'&enddate='+endDate;
	});
	
	$('#btn_ExportToPDF').click(function() {
		var startDate = $('#txtStartDate').val();
		var endDate = $('#txtEndDate').val();
		window.location.href = './PORECEIVED-ExportToPDF.php?startdate='+startDate+'&enddate='+endDate;
	});
});
</script>
</head>
<body>
<div>
	<div class="panel panel-primary" style="overflow-x:scroll;">
	<div class="panel-heading" style="width:auto;overflow:hidden;">P.O. RECEIVE</div>
		<div class="panel-body" style="width:auto;">
			<?php
			if($_SESSION['date'] == "") {
				if(isset($_POST['txtStartDate']) && isset($_POST['txtEndDate'])) {
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				} else {
					$_SESSION['date'] = $dateToday;
					$_SESSION['date_out'] = $dateToday;
				}
			} else {
				if($_POST['txtStartDate'] != "") {
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				}
			}

			if(isset($_POST["txtItemCode"])) {
				$itemCode = $_POST['txtItemCode'];
			} else {
				$itemCode = "";
			}
			?>
			<form class="form-inline" role="form" method="POST" action="poReceived.php">
			  <div class="form-group" id="divStartDate">
				<label class="sr-only" for="txtStartDate">Start Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtStartDate" name="txtStartDate" placeholder="Start Date" value="'.$_SESSION['date'].'">';	
				?>
			  </div>
			  <div class="form-group" id="divEndDate">
				<label class="sr-only" for="txtEndDate">End Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtEndDate" name = "txtEndDate" placeholder="End Date" value="'.$_SESSION['date_out'].'">';
				?>
			  </div>
			  <button type="submit" class="btn btn-default" id="SetDateRange">SEARCH</button>
			</form>
			<form class="form-inline" role="form" method="POST" action="#">
			  <div class="form-group">
				<label for="txtItemCode">ITEM CODE: </label>
				<?php 
					echo '<input type="text" class="form-control" id="txtItemCode" list="ItemCodeList" autocomplete="off" name = "txtItemCode" placeholder="-Item Code-" value="'.$itemCode.'">';
				?>
				<button type="submit" class="btn btn-default" id="SetItemCode">SEARCH</button>
			  </div>
			</form>
				
			<?php
				$result = PPSDBS::getInstance()->loadNonExistingPOReceived();
				$badgeCtr = 0;
				while($row = mysqli_fetch_array($result)){
					$badgeCtr++;
				}
			?>
			<div class="btn-group">
			  <button id="btn_AddNewPO" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD NEW P.O. RECEIVE</button>
			  <div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-transfer" data-toggle="dropdown">
					IMPORT/EXPORT
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
				<!-- dropdown menu links -->
					<li><a href="#" id="btn_ImportCSV" class="glyphicon glyphicon-import">IMPORT CSV</a></li>
					<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
					<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL</a></li>
				</ul>
			  </div>
			  <button id="btn_ResinComputation" type="button" class="btn btn-default glyphicon glyphicon-arrow-right">GENERATE RESIN PR</button>
			  <a href="./poReceivedNE.php"><button id="btn_NonExistingDieset" type="button" class="btn btn-default glyphicon glyphicon-tasks">DEVICE W/OUT DIE-SET &nbsp; <?php if($badgeCtr!=0){ echo'<span class="badge badge-error">'.$badgeCtr.'</span>';} ?></button></a>
			</div>
			<table id="tbl_POReceivedMain" border="1" style="font-size:9px;">
				<thead>
					<tr>
						<th>CATEGORY</th>
						<th>YEC P.O.</th>
						<th>ITEM CODE</th>
						<th>NAME</th>
						<th>ORDER NO.</th>
						<th>SCHD. QTY</th>
						<th>PURCHASE U/COST</th>
						<th>PURCH. ORDER AMOUNT</th>
						<th>DATE ISSUED</th>
						<th>PPD REPLY</th>
						<th>DATE NEEDED</th>
						<th>REMARKS</th>
						<th>DRAWING NO</th>
					</tr>
				</thead>
				<tbody>
				<?php 
					if($itemCode != "") {
						if($itemCode == '-VIEW ALL-') {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('VIEWALL', $_POST['txtItemCode'], $dateToday,$dateToday,$username);
						} else {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('itemcode', $_POST['txtItemCode'], $dateToday,$dateToday,$username);
						}
					} else {
						if(!isset($_POST['txtStartDate']) || !isset($_POST['txtEndDate'])) {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('date', $_POST['txtItemCode'], $dateToday,$dateToday,$username);
						} else {
							$getAllPOReceived = PPSDBS::getInstance()->viewPOReceived('date', $_POST['txtItemCode'], $_POST['txtStartDate'],$_POST['txtEndDate'],$username);
						}
					} 
					while($row = mysqli_fetch_array($getAllPOReceived)) {
						echo "<tr>";
						echo "
							<td>".$row['Supplier']."</td>
							<td>".$row['YECPO']."</td>
							<td class='itemCode'>".$row['ItemCode']."</td>
							<td>".$row['Name']."</td>
							<td>".$row['OrderNo']."</td>
							<td>".$row['SchdQty']."</td>
							<td>".$row['PurchaseUnitCost']."</td>
							<td>".$row['PurchaseOrderAmt']."</td>
							<td>".$row['DateIssued']."</td>
							<td>".$row['PPDReply']."</td>
							<td>".$row['DateNeeded']."</td>
							<td>".$row['Remarks']."</td>
							<td>".$row['DrawingNo']."</td>
						";
						echo "</tr>";
					}					
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!--MODALS-->
<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">UPDATE P.O. RECEIVED</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:20%;">Workcenter</label><input type="text" style="width:20%;" id="workcenter">
				<label style="margin-right:5%;width:20%;">W/C Name</label><input type="text" style="width:20%;" id="wcName"><br>
				<label style="margin-right:5%;width:20%;">Supplier</label><input type="text" style="width:20%;" id="supplier">
				<label style="margin-right:5%;width:20%;">Supplier Name</label><input type="text" style="width:20%;" id="supplierName"><br>
				<label style="margin-right:5%;width:20%;">YEC P.O.</label><input type="text" style="width:20%;" id="yecPO">
				<label style="margin-right:5%;width:20%;">Item Code</label><input type="text" style="width:20%;" id="itemCode"><br>
				<label style="margin-right:5%;width:20%;">Name</label><input type="text" style="width:20%;" id="name">
				<label style="margin-right:5%;width:20%;">Order No.</label><input type="text" style="width:20%;" id="orderNo"><br>
				<label style="margin-right:5%;width:20%;">Scheduled Quantity</label><input type="text" style="width:20%;" id="schedQty">
				<label style="margin-right:5%;width:20%;">Purchase U/Cost</label><input type="text" style="width:20%;" id="purchaseUCost"><br>
				<label style="margin-right:5%;width:20%;">Purchase Order Amount</label><input type="text" style="width:20%;" id="purchaseOrderAmount">
				<label style="margin-right:5%;width:20%;">Currency</label><input type="text" style="width:20%;" id="currency"><br>
				<label style="margin-right:5%;width:20%;">Deli. Location</label><input type="text" style="width:20%;" id="deliLocation">
				<label style="margin-right:5%;width:20%;">Deli. Loc. Name</label><input type="text" style="width:20%;" id="deliLocName"><br>
				<label style="margin-right:5%;width:20%;">Date Issued</label><input type="text" style="width:20%;" id="dateIssued">
				<label style="margin-right:5%;width:20%;">PPD Reply</label><input type="text" style="width:20%;" id="ppdReply"><br>
				<label style="margin-right:5%;width:20%;">Date Needed</label><input type="text" style="width:20%;" id="dateNeeded">
				<label style="margin-right:5%;width:20%;">Remarks</label><input type="text" style="width:20%;" id="remarks"><br>
				<label style="margin-right:5%;width:20%;">Drawing No</label><input type="text" style="width:20%;" id="drawingNo">
				<label style="margin-right:5%;width:20%;">Classification</label><input type="text" style="width:20%;" id="classification">
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_UpdatePO" class="btn btn-success">SAVE</a>
			   <a href="#" id="btn_Delete" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">DELETE</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-primary">CLOSE</a>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modal_GenerateResinPR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Generate Resin PR</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:40%;">PO Balance Total kgs. needed:</label><input type="text" id="txt_TotalKgsNeededGM" style="width:50%;"><br><br>
				<label style="margin-right:5%;width:40%;">Warehouse Balance:</label><input type="text" id="txt_WarehouseGM" style="width:50%;">
				<label style="margin-right:5%;width:40%;">WIP Balance:</label><input type="text" id="txt_WIPGM" style="width:50%;">
				<label style="margin-right:5%;width:40%;">FGS Balance:</label><input type="text" id="txt_FGSGM" style="width:50%;">
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_Delete_Yes" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>

<!--Modal-->
<div id="modal_ImportPO" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Data Upload:</h4>
			</div>
			<form action="PORECEIVED-ImportToCSV.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
					<label>Please select a file to import:</label><input type="file" name="file" id="file">
				</div>
				<div class="modal-footer">
				   <input type="submit" class="btn btn-success" value="SUBMIT">
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
				</div>
			</form>
		</div>
	</div>
</div>

</body>
</html>