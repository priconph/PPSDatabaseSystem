<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date('Y-m-d');
session_start();
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/dieset_style_sheet.css" rel="stylesheet">
<script>
var Handler = "./ppsdbsHandler.php";
$('document').ready(function(){
	var oTable = $('#tbl_DiesetMain').dataTable({
		paginate:false
	});
	
	$('#btn_AddDieset').click(function(){
		window.location.href = "./addDieset.php";
	});
	
	$('#btn_GenerateQuotation').click(function(){
		window.location.href = "./generateQuotation.php";
	});
	
	$('#btn_SearchDieset').click(function(){
		var category = $('#cmb_search_category').val();
		$('#txt_search_dieset').val("");
		get_search_item(category);
		$('#modal_search_dieset').modal('show');
	});
	
	$('#btn_ExportToExcel').click(function(){
		window.location.href = "./dieset_tool_life-excel.php";
	});
	
	$('#btn_ImportCSV').click(function () {
		$('#ModalImportDiesetCSV').modal('show');
	});
	
	$('#btn_ExportToPDF').click(function () {
		var keyword = $('#SearchItem').val();
		if(keyword == '') {
			alert('Please select DeviceName/R3/Exp Code/YEC Control No!');
			$('#SearchItem').focus();
		} else {
			window.location.href = "./addDiesetExportToPDF.php?yecControlNoDeviceName="+keyword;
		}
	});
	
	$('#tbl_DiesetMain tr').dblclick(function(){
		var R3Code = $(this).children(".R3Code").map(function() {
			return $(this).text();
		}).get();
		
		var DeviceName = $(this).children(".DeviceName").map(function() {
			return $(this).text();
		}).get();
		
		var rev = $(this).children(".Rev").map(function() {
			return $(this).text();
		}).get();
		
		if(R3Code == ""){
			alert("This dieset doesn't have an R3Code please add R3Code to edit");
			$('#txt_DeviceName_Update').val(DeviceName);
			$('#modal_update_dieset_r3code').modal('show');
			return false;
		}
		
		window.location.href = "./updateDieset.php?r3code="+R3Code+"&rev="+rev;
	});
	
	$('#btn_update_r3code').click(function(){
		var DeviceName = $('#txt_DeviceName_Update').val();
		var R3Code = $('#txt_R3Code_Update').val();
		if(R3Code == ""){
			alert("Invalid R3Code");
			return false;
		}
		update_dieset_r3code(DeviceName,R3Code);
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
	
	$('#cmb_search_category').change(function(){
		var category = $('#cmb_search_category').val();
		$('#txt_search_dieset').val("");
		get_search_item(category);
	});
	
});

function get_search_item(category){
	var data = {
		"action"	: "loadSearchItem",
		"Category"	: category
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: Handler,
		success	: function(data){
			$('#list_search').empty();
			var ctr = 0;
			while (ctr < data['ctr']){
				if(category == 1){
					var datalist = "<option>"+data['DeviceName'][ctr]+"</option>";
					$('#list_search').append(datalist);
				}else if(category ==2){
					var datalist = "<option>"+data['R3Code'][ctr]+"</option>";
					$('#list_search').append(datalist);
				}else{
					var datalist = "<option>"+data['RackNum'][ctr]+"</option>";
					$('#list_search').append(datalist);
				}
				ctr++;
				$('#cmb_search_category').removeAttr('list');
				$('#cmb_search_category').attr('list','list_search');
			}
		}
	});
}

function update_dieset_r3code(DeviceName,R3Code){
	var data = {
		"action"	: "updateDiesetPartCode",
		"tbl_Name"	: "tbl_dieset",
		"DeviceName": DeviceName,
		"R3Code"	: R3Code
	}
	data = $.param(data);
	$.ajax({
		type	: "post",
		dataType: "json",
		data	: data,
		url		: Handler,
		success	: function(data){
			alert(data['result']);
			window.location.reload();
		}
	});
}
</script>
</head>
<body>
<!--
<button class="btn btn-default" id="btn_AddNewDieset">ADD DIESET</button>
<button class="btn btn-default" id="btn_UpdateDieset">UPDATE DIESET</button>
-->
<div>
	<div class="panel panel-primary">
	<div class="panel-heading">DIE-SET</div>
		<div class="panel-body">
			
			<?php
			/*
			//Datalist for search
			$result = PPSDBS::getInstance()->getAllDeviceName();
			echo '<datalist id="list_DeviceName">';
			while ($row= mysqli_fetch_array($result)){
				echo '<option>'.$row["DeviceName"].'</option>';
			}
			echo '</datalist>';
			//Set Date here
			if($_SESSION['date']==""){
				if(isset($_POST['txtStartDate']) && isset($_POST['txtStartDate'])){
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				}else{
					$_SESSION['date'] = $dateToday;
					$_SESSION['date_out'] = $dateToday;
				}
			}else{
				if($_POST['txtStartDate']!=""){
					$_SESSION['date'] = $_POST['txtStartDate'];
					$_SESSION['date_out'] = $_POST['txtEndDate'];
				}else{
					
				}
			}
			?>
			<?php
			if(isset($_POST["txt_search_dieset"])){
				$SearchItem = $_POST["txt_search_dieset"];
				$Category = $_POST["cmb_search_category"];
			}else{
				$SearchItem = "-Select-";
			}
			?>
			<!-- 
			<form class="form-inline" role="form" method="POST" action="dieset.php">
			  <div class="form-group">
				<label class="sr-only" for="txtStartDate">Start Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtStartDate" name="txtStartDate" placeholder="Start Date" value="'.$_SESSION['date'].'">';	
				?>
			  </div>
			  <div class="form-group">
				<label class="sr-only" for="txtEndDate">End Date</label>
				<?php 
					echo '<input type="date" class="form-control" id="txtEndDate" name = "txtEndDate" placeholder="End Date" value="'.$_SESSION['date_out'].'">';
				?>
			  </div>
			  <button type="submit" class="btn btn-default" id="SetDateRange">SET DATE</button>
			</form>
			-->
			<!--<form class="form-inline" method="post" action="#">
				<div class="form-group">
				<label>SEARCH: </label> <input class="form-control" type="text" id="SearchItem" name="SearchItem" value="" placeholder="DeviceName/R3/Exp Code/YEC Control No" autocomplete="off" list="list_DeviceName"> 
				</div>
				<input class="btn btn-default" type="submit" id="btn_Search" name="btn_Search" value="SEARCH"><br><br>
			</form>-->
			
			<?php
				$getAllDieset = PPSDBS::getInstance()->getDiesetInconsistencies();
				$badgeCount=0;
				while($row = mysqli_fetch_array($getAllDieset)) {
					$badgeCount++;
				}
			*/
			?>
			<button id="btn_ExportToExcel" class="glyphicon glyphicon-export btn btn-primary">EXPORT TO EXCEL</button>
			<!--
			<button id="btn_SearchDieset" type="button" class="btn btn-default glyphicon glyphicon-plus">SEARCH DIESET</button>
			<button id="btn_AddDieset" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD DIESET</button>
			  <div class="btn-group">
				<button type="button" class="btn btn-default dropdown-toggle glyphicon glyphicon-transfer" data-toggle="dropdown">
					IMPORT/EXPORT
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
				<!-- dropdown menu links -->
			<!--	
				<li><a href="#" id="btn_ImportCSV" class="glyphicon glyphicon-import">IMPORT CSV</a></li>
					<li><a href="#" id="btn_ExportToPDF" class="glyphicon glyphicon-export">EXPORT TO PDF</a></li>
					<li><a href="#" id="btn_ExportToExcel" class="glyphicon glyphicon-export">EXPORT TO EXCEL</a></li>
				</ul>
			  </div>
			  <button id="btn_GenerateQuotation" type="button" class="btn btn-default glyphicon glyphicon-arrow-right">GENERATE QUOTATION</button>
			  <a href="./diesetInconsistencies.php"><button id="btn_DiesetInconistency" type="button" class="btn btn-default glyphicon glyphicon-tasks">MATERIALS INCONSISTENCY &nbsp; <?php if($badgeCount!=0){ echo'<span class="badge badge-error">'.$badgeCount.'</span>';} ?></button></a>
			
			<!-- <div class="btn-group">
			  <button id="btn_AddDieset" type="button" class="btn btn-default glyphicon glyphicon-plus">ADD DIESET</button>
			  <button id="btn_ImportCSV" type="button" class="btn btn-default glyphicon glyphicon-import">IMPORT CSV FILE</button>
			  <button id="btn_ExportToExcel" type="button" class="btn btn-default glyphicon glyphicon-export">EXPORT TO EXCEL</button>
			  <button id="btn_ExportToPDF" type="button" class="btn btn-default glyphicon glyphicon-file">EXPORT TO PDF</button>
			  <button id="btn_GenerateQuotation" type="button" class="btn btn-default glyphicon glyphicon-arrow-right">GENERATE QUOTATION</button>
			  <a href="./diesetInconsistencies.php"><button id="btn_DiesetInconistency" type="button" class="btn btn-default">MATERIALS INCONSISTENCY &nbsp; <?php if($badgeCount!=0){ echo'<span class="badge badge-error">'.$badgeCount.'</span>';} ?></button></a>
			 </div> -->
			<h5 id="label_dblClickNote">Note: Double Click Row to Edit</h5>
			<table id="tbl_DiesetMain" border="1">
				<thead>
					<tr>
						<th>Rack No.</th>
						<!--<th>Die-set Count</th>
						<th>Item Count</th>
						<th>For Evaluation Item</th>-->
						<th>R/3 Code</th>
						<!--<th>EXP CODE</th>-->
						<th>DEVICE NAME</th>
						<th>DRAWING NO</th>
						<th>REV</th>
						<th>MATERIAL</th>
						<th>TOOL SHOT</th>
						<th>TOOL LIFE IN PMI</th>
						<th>REMARKS</th>
					</tr>
				</thead>
				<tbody>
					<?php
						
						$getAllDieset = PPSDBS::getInstance()->get_dieset_tool_life_end();
							//$getAllDieset = PPSDBS::getInstance()->getDiesetSearch($SearchItem,$Category);
						while($row = mysqli_fetch_array($getAllDieset)) {
							if($row['tool_life_used'] >= 80){
								echo "	
								<tr>
									<td id='yec' class='yec'>".$row['RackNum']."</td>
									<td class='R3Code'>".$row['R3Code']."</td>
									<td class='DeviceName'>".$row['DeviceName']."</td>
									<td>".$row['DrawingNo']."</td>
									<td class='Rev'>".$row['Rev']."</td>
									<td>".$row['Material']."</td>
									<td>".$row['ToolShot']."</td>
									<td>".$row['ToolLifePMI']."</td>
									<td>".$row['Remarks']."</td>
								</tr>";
								//<td>".$row['EXPCode']."</td>
							}
						}
					?>
				</tbody>
			</table>
			
		</div>
	</div>
</div>

<!--MODALS-->
<div id="modal_" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" ="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Modal1</h4>
			</div>
			<div class="modal-body">
				<p>YOU ARE ABOUT TO DELETE AN ACCOUNT</p>
				<p>ARE YOU SURE?</p>
				<p>PROCEED?</p>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btnDeleteUserAccountC" class="btn btn-success">YES</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">NO</a>
			</div>
		</div>
	</div>
</div>

<div id="ModalImportDiesetCSV" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<form action="DIESET-ImportToCSV.php" method="POST" enctype="multipart/form-data">
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

<div id="ModalImportDiesetCSV" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Upload CSV File</h4>
			</div>
			<form action="DIESET-ImportToCSV.php" method="POST" enctype="multipart/form-data">
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

<div id="modal_search_dieset" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">Search</h4>
			</div>
			<div class="modal-body">
				<form method="post" action="#">
				<datalist id="list_search" name='list_search'></datalist>
				<select id="cmb_search_category" name="cmb_search_category">
					<option value="1">Device Name</option>
					<option value="2">R3 Code</option>
					<option value="3">Rack Number</option>
				</select>
				<input type="text" id="txt_search_dieset" name='txt_search_dieset' list="list_search" value="<?php echo $SearchItem; ?>" style="width:50%;">
			</div>
			<div class="modal-footer">
			   <!--<a href="#" id="btn_search_dieset" class="btn btn-success">SEARCH</a>-->
				<input type="submit" value="search" class="btn btn-success">
				</form>
			  <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>

<div id="modal_update_dieset_r3code" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalImportDiesetCSV" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:400px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">UPDATE R3Code</h4>
			</div>
			<div class="modal-body">
				<label style="width:40%;">Device Name:</label><input type="text" id="txt_DeviceName_Update" style="width:60%;" readonly><br>
				<label style="width:40%;">R3 Code:</label><input type="text" id="txt_R3Code_Update" style="width:60%;">
			</div>
			<div class="modal-footer">
				<a href="#" id="btn_update_r3code" class="btn btn-success">UPDATE</a>
				<a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>