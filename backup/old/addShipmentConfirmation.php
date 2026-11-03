<?php
require_once('./includes.php');
require_once('./ppsdbs-oop.php');
$dateToday = date("Y-m-d");
$username = $_SESSION['username'];
$username = "rona";
$getItemCodeList = PPSDBS::getInstance()->getItemCodeList();
echo "<datalist id='itemCodeList'>";
while($row = mysqli_fetch_array($getItemCodeList)) {
	echo '<option>'.$row['OrderNo'].'</option>';
}
echo "</datalist>";
?>
<html>
<title>PPS Database System</title>
<head>
<link href="./css/ppsStyleSheet.css" rel="stylesheet">
<script>
$('document').ready(function(){
	var row=0,totalPrice=0;
	//change funcitons
	$("#txt_QuantityAM").focusout(function(){
		$("#txt_TotalPriceAM").val(parseFloat($("#txt_QuantityAM").val())*parseFloat($("#txt_PriceAM").val()));
	});
	$("#txt_PriceAM").change(function(){
		$("#txt_TotalPriceAM").val(parseFloat($("#txt_QuantityAM").val())*parseFloat($("#txt_PriceAM").val()));
	});
	//click functions
	$('#btn_Cancel').click(function(){
		window.location.href = "./shipmentConfirmation.php";
	});
	
	$('#btn_AddPR').click(function(){
		$('#modal_AddPR').modal('show');
	});
	
	$('#btn_AddAM').click(function(){
		if($('#txt_PRNumberAM').val() != "" && $('#txt_PartsCodeAM').val() != "" && $('#txt_PartsNameAM').val() != "" && $('#txt_QuantityAM').val() != "" && $('#txt_PriceAM').val() != "" && $('#txt_TotalPriceAM').val() != "") {
			$('#tbl_ShipmentConfirmationMain tbody').append(
			'<tr><td>'+$("#txt_PRNumberAM").val()+
			'</td><td>'+$("#txt_PartsCodeAM").val()+
			'</td><td>'+$("#txt_PartsNameAM").val()+
			'</td><td>'+$("#txt_QuantityAM").val()+
			'</td><td>'+$("#txt_PriceAM").val()+
			'</td><td>'+$("#txt_TotalPriceAM").val()+
			'</td><td><a href="javascript:void(0);" class="remCF">Remove</a></td></tr>'); //APPEND NEW DATA INTO THE TABLE
			
			totalPrice = parseFloat($("#txt_GrandTotal").val()) + parseFloat($("#txt_TotalPriceAM").val());
			
			$('#txt_PRNumberAM').val('');
			$('#txt_PartsCodeAM').val('');
			$('#txt_PartsNameAM').val('');
			$('#txt_QuantityAM').val('0');
			$('#txt_PriceAM').val('');
			$('#txt_TotalPriceAM').val('0');
			$('#txt_GrandTotal').val(totalPrice.toFixed(4));
		} else {
			alert('Please check all fields.');
		}
	});
	
	var ControlNoG=0;
	$("#btn_Save").click(function(){
		
		var data = {
			"action" : "addShipmentConfirmation",
			"ControlNumber" : $("#txt_ControlNumber").val(),
			"Date" : $("#txt_Date").val(),
			"Customer" : $("#txt_Customer").val(),
			"Total" : $("#txt_GrandTotal").val(),
			"Remarks" : $("#txt_Remarks").val(),
			"Username" : "<?php echo $username; ?>"
		}
		ControlNoG = data['ControlNumber'];
		data = $.param(data);
		
		$.ajax({
			type : "post",
			dataType : "json",
			data : data,
			url : "./ppsdbsHandler",
			success : function(result){
				if(result["msg"] == "Control Number already exists!") {
					alert(result["msg"]);
				} else {
					if($('#txt_GrandTotal').val() == 0) {
						alert("No item added!");
					} else {
						row=0;
						//variable and a function to add new values in Shipment Confirmation
						var PRNo=0,PartsCode=0,PartsName=0,Quantity=0,Price=0,Total=0;
						$("#tbl_ShipmentConfirmationMain tr").each(function(row, tr){
							if(row>0){ 	//REMOVE ROW 0 WHICH IS A LABEL
								//GET VALUES HERE
								var data =  {
									"action" : "addItemSC",
									"ControlNumber" : ControlNoG,
									"PRNo" : $(tr).find('td:eq(0)').text(),
									"PartsCode" : $(tr).find('td:eq(1)').text(),
									"PartsName" : $(tr).find('td:eq(2)').text(),
									"Quantity" : $(tr).find('td:eq(3)').text(),
									"Price" : $(tr).find('td:eq(4)').text(),
									"Total" : $(tr).find('td:eq(5)').text(),
									"Username" : "<?php echo $username; ?>"
								}
								data = $.param(data);
								$.ajax({
									type:"post",
									dataType:"json",
									data:data,
									url:"./ppsdbsHandler",
									success:function(){
									}
								});
							}
							row++;		
						});
						alert('Successfully added!');
					}
				}
			} 
		});
	});
	
	totalPrice = 0; //reset current total price
	row = 0; //RESET ROW COUNT
	//FUNCTION FOR REMOVING A ROW INTO THE TABLE 
	$("#tbl_ShipmentConfirmationMain").on('click','.remCF',function(){ //TABLE ID = tblMain AND TABLE CLASS = remCF
		$(this).parent().parent().remove(); //REMOVES THE ROW
		//RECOMPUTE TOTAL AFTER REMOVING A ROW
		totalPrice = 0;
		$('#tbl_ShipmentConfirmationMain tr').each(function(row, tr){
			if(row>0){
				totalPrice = parseFloat(totalPrice) + parseFloat($(tr).find('td:eq(5)').text());
			}
			row++;
		});
		$('#txt_GrandTotal').val(totalPrice.toFixed(2));
	});
	
});

function CheckNumeric(){
  // Get ASCII value of key that user pressed
   var key = window.event.keyCode;
  // Was key that was pressed a numeric character (0-9)?
   if ( key > 45 && key < 58 )
     return; // if so, do nothing
   else
     window.event.returnValue = null; // otherwise, 
	                               // discard character
}

function getItemCodeInfo() {
	$('document').ready(function () {
	var orderNo = $('#txt_PRNumberAM').val();
		var data = {
				"action":"getItemCodeInfo", 
				"OrderNo": orderNo
			}
		data = $.param(data); 
			$.ajax({	
				type: "POST",
				dataType: "json",
				url: "ppsdbsHandler.php",	
				data: data,
				success: function(result){
					$('#txt_PartsCodeAM').val(result['PartsCode']);
					$('#txt_PartsNameAM').val(result['PartsName']);
					$('#txt_PriceAM').val(result['Price']);
				}
		});
	});
}

</script>
</head>
<body>
	<div class="panel panel-primary" style="width:100%;margin:auto;">
	  <div class="panel-heading">Die-set Transfer and Material</div>
	  <div class="panel-body">
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<label style="margin-right:5%;width:20%;">CONTROL NUMBER:</label><input type="text" id="txt_ControlNumber" style="width:20%;">
				<label style="margin-right:5%;width:20%;">DATE:</label><input type="date" id="txt_Date" style="width:20%;" value="<?php echo date('Y-m-d'); ?>">
				<label style="margin-right:5%;width:20%;">CUSTOMER:</label><input type="text" id="txt_Customer" style="width:20%;">
				<label style="margin-right:5%;width:20%;">Remarks:</label><textarea cols='40' rows='2' style="margin-top:5px;width:20%" id='txt_Remarks'></textarea>
			</div>
		</div>
		
		<div class="panel panel-primary">
			<div class="panel-body">
				<div align="right">
				  <button id="btn_AddPR" type="button" class="btn btn-warning">ADD ITEM</button>
				</div><br><br>
			
				<table id="tbl_ShipmentConfirmationMain" class="table" border="1">
					<thead>
						<tr>
							<th>PR No.</th>
							<th>Parts Code</th>
							<th>Parts Name</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Total</th>
							<th>(X)</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
				<div align="right"><label style="width:10%;">Grand Total:</label><input type="text" id="txt_GrandTotal" value="0"></div>
			</div>
		</div>
		<div class="panel panel-primary" align="right">
			<div class="panel-body">
				<button id="btn_Save" class="btn btn-success" style="width:10%;">SAVE</button>
				<button class="btn btn-danger" id="btn_Cancel" style="width:10%;">CANCEL</button>
			</div>
		</div>
	  </div>
	</div>

<!--Modal-->
<div id="modal_AddPR" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
	 <div class="modal-dialog">
		<div class="modal-content" style="width:450px">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title" id="ModalDeleteLabel">PR Information</h4>
			</div>
			<div class="modal-body">
				<label style="margin-right:5%;width:30%;">PR Number:</label><input type="text" id="txt_PRNumberAM" style="width:65%;" oninput="getItemCodeInfo();" list="itemCodeList" autocomplete="off" name="itemname" tabindex="0"><br>
				<label style="margin-right:5%;width:30%;">Parts Code:</label><input type="text" id="txt_PartsCodeAM" style="width:65%;" readOnly><br>
				<label style="margin-right:5%;width:30%;">Parts Name:</label><input type="text" id="txt_PartsNameAM" style="width:65%;" readOnly><br>
				<label style="margin-right:5%;width:30%;">Quantity:</label><input value="0" onkeypress="CheckNumeric()" type="text" id="txt_QuantityAM" style="width:65%;"><br>
				<label style="margin-right:5%;width:30%;">Price:</label><input value="0" onkeypress="CheckNumeric()" type="text" id="txt_PriceAM" style="width:65%;" readOnly><br>
				<label style="margin-right:5%;width:30%;">Total Price:</label><input value="0" type="text" id="txt_TotalPriceAM" style="width:65%;" readOnly><br>
			</div>
			<div class="modal-footer">
			   <a href="#" id="btn_AddAM" class="btn btn-success">ADD</a>
			   <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-danger">CLOSE</a>
			</div>
		</div>
	</div>
</div>

</body>
</html>