<script>
//function for initializing datatables
function call_DataTable(tableName,tbl_id){
	var tableName = $('#'+tbl_id+'').dataTable({
			paginate:false,
			"autoWidth": false
	});
}

function call_ClickFunction(itemID,itemContent){
	$('#'+itemID+'').click(function(){
		eval(itemContent);
	});
}
//function for date validation
function call_DateValidation(startDates,endDates){
	//START DATE VALIDATION
	$('#'+startDates+'').change(function(){
			var startDate = $('#'+startDates+'').val();
			var endDate = $('#'+endDates+'').val();
				if (startDate>endDate){
					$('#'+startDates+'').val(endDate);  
				}
		});
	//END DATE VALIDATION
	$('#'+endDates+'').change(function(){
		var startDate = $('#'+startDates+'').val();
		var endDate =$('#'+endDates+'').val();
			if (endDate<startDate){
				$('#'+endDates+'').val(startDate);  
			}
	});
}

//function for checking numeric input
function CheckNumeric(){
	// Get ASCII value of key that user pressed
	var key = window.event.keyCode;
	// Was key that was pressed a numeric character (0-9)?
	if ( key > 45 && key < 58 )
		return; // if so, do nothing
	   else
		 window.event.returnValue = null; // otherwise, // discard character
}
</script>

<?php
//function modal
function call_Modal($ModalName,$ModalTitle,$ModalBody,$ModalFooter){
	echo '<div id="'.$ModalName.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="ModalDeleteLabel" aria-hidden="true" style="overflow:inherit">
		 <div class="modal-dialog">
			<div class="modal-content" style="width:400px">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					  <h4 class="modal-title" id="ModalDeleteLabel">'.$ModalTitle.'</h4>
				</div>
				<div class="modal-body">
					'.$ModalBody.'
				</div>
				<div class="modal-footer">
				   '.$ModalFooter.'
				</div>
			</div>
		</div>
	</div>';
}
?>