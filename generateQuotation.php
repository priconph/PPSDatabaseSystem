<?php
require_once('./includes.php');
require_once('ppsdbs-oop.php');
session_start();
if (array_key_exists("username", $_SESSION))
{
	$username = $_SESSION['username'];
	$dateToday = date("Y-m-d");
	echo "<datalist id='employeeNameList'>";
		$loadEmployeeName = PPSDBS::getInstance()->loadEmployeeNames();
		while($rowNames=mysqli_fetch_array($loadEmployeeName)) {
			echo "<option>".$rowNames['empname']."</option>";
		}
	echo "</datalist>";
}
?>
<html>
<title>PPS Database System</title>
<head>
<!--<link href="./css/ppsStyleSheet.css" rel="stylesheet">-->
<script>
$('document').ready(function(){
	var oTable = $('#tbl_GenerateQuotation').dataTable({
			paginate:false,
			"autoWidth": false
	});
	$('#btn_CreateQuotation').click(function(){
		window.location.href = "./addGenerateQuotation.php";
	});
	
	$('#btn_ImportCSV').click(function () {
		$('#ModalImportDiesetCSV').modal('show');
	});
	
	$('#btn_ExportToExcel').click(function(){
		$("#modal_GenerateQuotationReport").modal("show");
	});
	
	$('#btn_ExportToExcelGR').click(function(){
		if($('#txt_ControlNumber').val() == "-Select-"){
			alert("WARNING! \n No Control Number Selected!");
			return false;
		}
		var ControlNumber = $("#txt_ControlNumber").val();
		var To = $("#txt_ToGR").val();
		var Attn = $("#txt_AttnGR").val();
		var Cc = $("#txt_CcGR").val();
		var CheckedBy = $("#txt_CheckedGR").val();
		var NotedBy = $("#txt_NotedGR").val();
		var ApprovedBy = $("#txt_ApprovedGR").val();
		var Date = $("#txt_DateGR").val();
		var Subject = $("#txt_SubjectGR").val();
		window.location.href = "./ExportGenerateQuotation.php?To="+To+"&Attn="+Attn+"&Cc="+Cc+"&Date="+Date+"&CheckedBy="+CheckedBy+"&NotedBy="+NotedBy+"&ApprovedBy="+ApprovedBy+"&ControlNumber="+ControlNumber+"&Subject="+Subject;
	});
	
	$('#btn_ExportToPDFGR').click(function(){
		if($('#txt_ControlNumber').val() == "-Select-"){
			alert("WARNING! \n No Control Number Selected!");
			return false;
		}
		var ControlNumber = $("#txt_ControlNumber").val();
		var To = $("#txt_ToGR").val();
		var Attn = $("#txt_AttnGR").val();
		var Cc = $("#txt_CcGR").val();
		var CheckedBy = $("#txt_CheckedGR").val();
		var NotedBy = $("#txt_NotedGR").val();
		var ApprovedBy = $("#txt_ApprovedGR").val();
		var Date = $("#txt_DateGR").val();
		var Subject = $("#txt_SubjectGR").val();
		window.location.href = "./generateQuotation-ExportToPDF.php?To="+To+"&Attn="+Attn+"&Cc="+Cc+"&Date="+Date+"&CheckedBy="+CheckedBy+"&NotedBy="+NotedBy+"&ApprovedBy="+ApprovedBy+"&ControlNumber="+ControlNumber+"&Subject="+Subject;
	});
	
	var iPos,x,idGlobal,IDForDelete;
	$("#tbl_GenerateQuotation tbody tr").click(function(){
		idForDelete = $(this).find(".pkid").text();
		iPos = oTable.fnGetPosition( this );
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}
		else {
			oTable.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});
	
	$("#btn_Delete").click(function(){
		if (confirm('Delete this data?')) {
			var data = {
				"action" : "deleteDiesetQuotation",
				"pkid" : idForDelete
			}
			data = $.param(data);
			$.ajax({
				type : "post",
				dataType : "json",
				data : data,
				url : "./ppsdbsHandler.php",
				success : function(data){
					alert("Record has been deleted!");
					$("#btn_Select").trigger('click');
					//window.location.reload();
				}
			});
		}else{
			
		}
	});
	
	$('#tbl_GenerateQuotation tbody tr').dblclick(function(){
		var pkidUpdate = $(this).find(".pkid").text();
		window.location.href = "./updateGenerateQuotation.php?pkid="+pkidUpdate;
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
	
	
});


</script>
</head>
<body>
<div class="panel panel-primary" style="">	
	<div class="panel-heading">GENERATE QUOTATION</div>
	<div class="panel-body" style="overflow-x:scroll;">
		<?php
		$result = PPSDBS::getInstance()->getAllControlNoQuotation();
		echo '<datalist id="list_ControlNo">';
			echo '<option>VIEW ALL</option>';
			while($row = mysqli_fetch_array($result)){
				echo '<option>'.$row['ControlNumber'].'</option>';
			}
		echo '</datalist>';
		?>
		<?php
			if(isset($_POST['txt_ControlNumber'])){
				if($_POST['txt_ControlNumber']=="VIEW ALL"){
					$ControlNumber = "";
				}else{
					$ControlNumber = $_POST['txt_ControlNumber'];
				}
			}else{
				$ControlNumber = "-Select-";
			}
		?>
		<form method="post" action="#">
		<label>CONTROL NUMBER: </label> <input list="list_ControlNo" type="text" id="txt_ControlNumber" name="txt_ControlNumber" value="<?php echo $ControlNumber; ?>"> 
		<input type="submit" id="btn_Select" name="btn_Select" value="Select"><br><br>
		</form>
		
		<div class="btn-group">
		  
		</div>
		  <div class="btn-group">
			<button id="btn_CreateQuotation" type="button" class="btn btn-default">CREATE NEW QUOTATION SHEET</button>
			<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-transfer" data-toggle="dropdown">
				IMPORT/EXPORT
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
			<!-- dropdown menu links -->
				<li><a href="#" id="btn_ImportCSV" class="glyphicon glyphicon-import">IMPORT CSV</a></li>
				<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT REPORT</a></li>
			</ul>
			<button id="btn_Delete" type="button" class="btn btn-default">DELETE</button>
		  </div>
		
	<table id="tbl_GenerateQuotation" border="1" style="font-size:10px;">
		<thead>
			<tr>
				<th>Device</th>
				<th>Material Name</th>
				<th>Partscode</th>
				<th>Resin Cost</th>
				<th># of Cavity</th>
				<th>Product Wt.(g)</th>
				<th>Sprue Wt. (g)</th>
				<th>Shot Wt. (g)</th>
				<th>Labor Cost/hr ($)</th>
				<th>Cycle Time</th>
				<th>Process Cost</th>
				<th>Material Cost (per piece)</th>
				<th>2nd Process Time (secs)</th>
				<th>2nd Process Cost</th>
				<th>Profit</th>
				<th>Molding Product Quotation ($)</th>
				<th>Remarks</th>
				<th style="display:none;">ID</th>
			</tr>
		</thead>
		<tbody>
			<?php
			//$result = PPSDBS::getInstance()->getQuotationDateRange($_SESSION["date"],$_SESSION["date_out"]);
			$result = PPSDBS::getInstance()->getQuotationByControlNumber($ControlNumber);
			while($row=mysqli_fetch_array($result)){
				echo '<tr>';
				echo '<td>'.$row["DeviceName"].'</td>';
				echo '<td>'.$row["Material"].'</td>';
				echo '<td>'.$row["R3Code"].'</td>';
				echo '<td>'.$row["ResinCost"].'</td>';
				echo '<td>'.$row["NoOfCav"].'</td>';
				echo '<td>'.$row["ProductWgt"].'</td>';
				echo '<td>'.$row["SprueWgt"].'</td>';
				echo '<td>'.$row["ShotWgt"].'</td>';
				echo '<td>'.$row["LaborCost"].'</td>';
				echo '<td>'.$row["CycleTime"].'</td>';
				echo '<td>'.$row["ProcessCost"].'</td>';
				echo '<td>'.$row["MaterialCost"].'</td>';
				echo '<td>'.$row["SecondProcessTime"].'</td>';
				echo '<td>'.$row["SecondProcessCost"].'</td>';
				echo '<td>'.$row["Profit"].'</td>';
				echo '<td>'.$row["MoldingProdQuot"].'</td>';
				echo '<td>'.$row["Remarks"].'</td>';
				echo '<td style="display:none;" class="pkid">'.$row["pkid"].'</td>';
				echo '</tr>';
			}
			?>
		</tbody>
	</table>
	
	</div>
</div>

<!--Modal-->
<div id="ModalImportDiesetCSV" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<form action="./GenerateQuotation-ImportCSV.php" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
						<p><input type="file" name="file" id="file"></p>
				</div>
				<div class="modal-footer">
				   <input type="submit" class="btn btn-success" value="SUBMIT">
				   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="modal_GenerateQuotationReport" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">GENERATE REPORT</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:20%;">To:</label></label><input type="text" id="txt_ToGR" style="width:50%;"><br>
				<label style="margin-right:5%;width:20%;">Attn:</label></label><input type="text" id="txt_AttnGR" style="width:50%;"><br>
				<label style="margin-right:5%;width:20%;">Cc:</label></label><input type="text" id="txt_CcGR" style="width:50%;"><br>
				<label style="margin-right:5%;width:20%;">Subject:</label></label><input type="text" id="txt_SubjectGR" style="width:50%;"><br>
				<label style="margin-right:5%;width:20%;">Checked by:</label></label><input type="text" id="txt_CheckedGR" list='employeeNameList' autocomplete='off' value='Rosalinda Sunga' style="width:50%;"><br>
				<label style="margin-right:5%;width:20%;">Noted by:</label></label><input type="text" id="txt_NotedGR" list='employeeNameList' autocomplete='off' value='Tatsuya Kobayashi' style="width:50%;"><br>
				<label style="margin-right:5%;width:20%;">Approved by:</label></label><input type="text" id="txt_ApprovedGR" list='employeeNameList' autocomplete='off' value='Joel Padullo' style="width:50%;"><br>
				<?php
					echo '<label style="margin-right:5%;width:20%;">Date:</label></label><input type="date" id="txt_DateGR" style="width:50%;" value="'.$dateToday.'"><br>';
				?>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_ExportToExcelGR" class="btn btn-success">EXPORT TO EXCEL</a>
			   <a href="#" id="btn_ExportToPDFGR" class="btn btn-success">EXPORT TO PDF</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>

</body>
</html>