<?php
	//echo "test";
	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {
				case "GetDiesetByDeviceName" : GetDiesetByDeviceName(); break;
				case "GetDiesetByPartsCode" : GetDiesetByPartsCode(); break;
				case "searchPoNum" : searchPoNum(); break;
				case "deleteSchedule" : deleteSchedule(); break;
				case "saveDieset" : addDieset(); break;
				case "viewDiesetByDateRange" : viewDiesetByDateRange(); break;
				case "updateDieset" : updateDieset(); break;
				case "addNewPOReceived" : addNewPOReceived(); break;
				case "updatePOReceived" : updatePOReceived(); break;
				case "addQuotation" : addQuotation(); break;
				case "addQuotationImport" : addQuotationImport(); break;
				case "updateQuotation" : updateQuotation(); break;
				case "getItemCodeInfo" : getItemCodeInfo(); break;
				case "updateDeliveryConfirmation" : updateDeliveryConfirmation(); break;
				case "deleteDiesetQuotation" : deleteDiesetQuotation(); break;
				case "addInternalInvoice" : addInternalInvoice(); break;	//for file addInternalInvoice.php
				case "addInternalInvoiceTbl" : addInternalInvoiceTbl(); break; //for file addInternalInvoice.php
				case "deleteInternalInvoice" : deleteInternalInvoice(); break; //for file addInternalInvoice.php
				case "getItemCodeInfoByItemCodeReal" : getItemCodeInfoByItemCodeReal(); break;
				case "orderNoCheckerDeliveryConfirmation" : orderNoCheckerDeliveryConfirmation(); break;
				case "exportShipmentRecord" : exportShipmentRecord(); break;
				case "addNewRecordShipmentInvoice" : addNewRecordShipmentInvoice(); break;
				case "addNewRecordShipment" : addNewRecordShipment(); break;
				case "LoadExistingQuotations" : LoadExistingQuotations(); break;
				case "DeleteAllQuotation" : DeleteAllQuotation(); break;
				case "loadSearchItem" : loadSearchItem(); break;
				case "getAllDeviceName" : getAllDeviceName(); break;
				case "updateDiesetPartCode" : updateDiesetPartCode(); break;
				case "getDiesetSearch" : getDiesetSearch(); break;
				case "add_obsolete_dieset" : add_obsolete_dieset(); break;
				case "addNewRecordShipment_new" : addNewRecordShipment_new(); break;
				/* Shipment - Preshipment Module */
				case "load_preshipment_control_no"			: load_preshipment_control_no(); break;
				case "load_preshipment_information"			: load_preshipment_information(); break;
				case "load_preshipment_information_update"	: load_preshipment_information_update(); break;
				case "load_invoice_main_information"		: load_invoice_main_information(); break;
				case "update_main_invoice_information"		: update_main_invoice_information(); break;
				case "delete_all_current_item_in_invoice"	: delete_all_current_item_in_invoice(); break;
				
				/* Additional functions as of November 2018 revisions */
				case "return_sold_to_list"					: return_sold_to_list(); break;
				case "return_shipment_schedule"				: return_shipment_schedule(); break;
			}
		}
	}

	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function deleteSchedule() {
		require_once('smpopsg-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->deleteSchedule($return['id']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	//YPICS Database access
	/* function searchPoNum(){
		$return = $_POST;
		$result = "";
		require_once('smpopsg-oop.php');
		$YPICS = new YPICS();
		$result = $YPICS->searchPoNum($return['PONum']);
			while ( $row = mssql_fetch_array($result) ) {			
				$return["PONum"]  = $row['PONum'];
				$return["DeviceName"]  = $row['DeviceName'];	
				$return["ProdCode"]  = $row['ProdCode'];	
				$return["POQty"]  = $row['POQty'];
				$return["CUST"]  = $row['CUST'];
			}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	} */
	
	//GENERATE QUOTATION//
	
	function deleteDiesetQuotation(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->deleteDiesetQuotation($return['pkid']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function getDiesetSearch(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->getDiesetSearch($return['SearchItem'],$return['Category']);
		$ctr = 0;
		while($row = mysqli_fetch_array($result)) {
			$return['RackNum'][$ctr] 		= $row['RackNum'];
			$return['R3Code'][$ctr] 		= $row['R3Code'];
			$return['DeviceName'][$ctr] 	= $row['DeviceName'];
			$return['DrawingNo'][$ctr] 		= $row['DrawingNo'];
			$return['Rev'][$ctr] 			= $row['Rev'];
			$return['Material'][$ctr]		= $row['Material'];
			$return['Remarks'][$ctr] 		= $row['Remarks'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	
	function DeleteAllQuotation(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->DeleteAllQuotation($return['ControlNo']);
		$return['RevNo'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	/////////////////////
	
	function addDieset() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addDieset($return['Date'],$return['Status'],$return['DateModified'],$return['DateReturned'],$return['YECControlNum'],$return['R3Code'],$return['ExpCode'],$return['DeviceName'],$return['DrawingNum'],$return['Rev'],$return['MoldClass'],$return['DieNo'],$return['NumOfCavity'],$return['CavNum'],$return['ChangingPart'],$return['RackNum'],$return['DiesetSize'],$return['DiesetWeight'],$return['UnitWeight'],$return['SprueWeight'],$return['ShotWeight'],$return['CycleTimePerShot'],$return['FixedAssetNum'],$return['DateManufactured'],$return['MoldMake'],$return['ToolLife'], $return['ToolShot'],$return['ToolLifePMI'],$return['MaintenanceCycle'],$return['Percentage'],$return['Plus'],$return['Minus'],$return['InjectionMachine'],$return['Material'],$return['ToneColor'],$return['Remarks'],$return['Username']);
		$new_id = $result["new_pkid"];
		if($result['msg'] == "Success"){
			$return["msg"] = $result;
		}else{
			while($row=mysqli_fetch_array($result)){
				$id = $row['id'];
			}
			$return['id'] = $id;
		}
		$return['new_pkid'] = $new_id;
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function viewDiesetByDateRange() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->viewDiesetByDateRange($return['sDate'],$return['eDate']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function add_obsolete_dieset() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->add_obsolete_dieset($return['id']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function updateDieset() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updateDiesetTransferMaterial($return['id'],$return['Date'],$return['Status'],$return['DateModified'],$return['DateReturned'],$return['YECControlNum'],$return['R3Code'],$return['ExpCode'],$return['DeviceName'],$return['DrawingNum'],$return['Rev'],$return['MoldClass'],$return['DieNo'],$return['NumOfCavity'],$return['CavNum'],$return['ChangingPart'],$return['RackNo'],$return['DiesetSize'],$return['DiesetWeight'],$return['UnitWeight'],$return['SprueWeight'],$return['ShotWeight'],$return['CycleTimePerShot'],$return['FixedAssetNum'],$return['DateManufactured'],$return['MoldMake'],$return['ToolLife'], $return['ToolShot'],$return['ToolLifePMI'],$return['MaintenanceCycle'],$return['Percentage'],$return['Plus'],$return['Minus'],$return['InjectionMachine'],$return['Material'],$return['ToneColor'],$return['Remarks'],$return['Username']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewPOReceived() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addPOReceived($return['category'],$return['poNo'], $return['itemCode'], $return['itemName'], $return['orderNo'], $return['orderQty'], $return['price'], $return['amount'], $return['dateIssued'], $return['shipOut'], $return['poBalance'], $return['drawingNo'], $return['username']);
		$return['msg'] = $result;
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function updatePOReceived() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updatePOReceived('all',$return['category'],$return['poNo'], $return['itemCode'], $return['itemName'], $return['orderNo'], $return['orderQty'], $return['price'], $return['amount'], $return['dateIssued'], $return['shipOut'], $return['poBalance'], $return['drawingNo'], $return['username']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function GetDiesetByDeviceName() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->GetDiesetByDeviceName($return['DeviceName']);
		if($row = mysqli_fetch_array($result)){
			$return['id'] = $row['id'];
			$return['PartsCode'] = $row['R3Code'];
			$return['Material'] = $row['Material'];
			$return['NoOfCav'] = $row['NoOfCav'];
			$return['UnitWgt'] = $row['UnitWgt'];
			$return['SprueWgt'] = $row['SprueWgt'];
			$return['ShotWgt'] = $row['ShotWgt'];
			$return['CycleShot'] = $row['CycleShot'];
		}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function GetDiesetByPartsCode() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->GetDiesetByPartsCode($return['PartsCode']);
		if($row = mysqli_fetch_array($result)){
			$return['id'] = $row['id'];
			$return['DeviceName'] = $row['DeviceName'];
			$return['Material'] = $row['Material'];
			$return['NoOfCav'] = $row['NoOfCav'];
			$return['UnitWgt'] = $row['UnitWgt'];
			$return['SprueWgt'] = $row['SprueWgt'];
			$return['ShotWgt'] = $row['ShotWgt'];
			$return['CycleShot'] = $row['CycleShot'];
		}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function addQuotation(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addQuotation($return["fkid"],$return["ControlNumber"],$return["ResinCost"],
															  $return["NumOfCavity"],$return["ProductWeight"],$return["SprueWeight"],
															  $return["ShotWeight"],$return["LaborCost"],$return["CycleTime"],
															  $return["ProcessCost"],$return["MaterialCost"],$return["SecondTimeProcess"],
															  $return["SecondProcessCost"],$return["Profit"],$return["MoldingQuotation"],
															  $return["RevNo"],$return["Remarks"],$return["LastUpdate"],$return["Username"]);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function addQuotationImport(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addQuotationImport($return["ControlNumber"],$return["Device"],$return["ResinCost"],
															  $return["NumOfCavity"],$return["ProductWeight"],$return["SprueWeight"],
															  $return["ShotWeight"],$return["LaborCost"],$return["CycleTime"],
															  $return["ProcessCost"],$return["MaterialCost"],$return["SecondTimeProcess"],
															  $return["SecondProcessCost"],$return["Profit"],$return["MoldingQuotation"],
															  $return["Remarks"],$return["LastUpdate"],$return["Username"]);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function updateQuotation(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updateQuotation($return["pkid"],$return["ControlNumber"],$return["ResinCost"],
															  $return["NumOfCavity"],$return["ProductWeight"],$return["SprueWeight"],
															  $return["ShotWeight"],$return["LaborCost"],$return["CycleTime"],
															  $return["ProcessCost"],$return["MaterialCost"],$return["SecondTimeProcess"],
															  $return["SecondProcessCost"],$return["Profit"],$return["MoldingQuotation"],
															  $return["Remarks"],$return["LastUpdate"],$return["Username"]);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function getItemCodeInfo() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->getItemCodeInfoByItemCode($return['OrderNo']);
		if($row = mysqli_fetch_array($result)) {
			$return["PartsCode"] = $row["ItemCode"];
			$return["PartsName"] = $row["Name"];
			$return["Price"] = $row["PurchaseUnitCost"];
		}
		
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}

	function LoadExistingQuotations() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->getQuotationByControlNumber($return['ControlNumber']);
		$ctr = 0;
		while($row = mysqli_fetch_array($result)) {
			$return["DeviceName"][$ctr] 		= $row["DeviceName"];
			$return["Material"][$ctr] 			= $row["Material"];
			$return["R3Code"][$ctr] 			= $row["R3Code"];
			$return["ResinCost"][$ctr] 			= $row["ResinCost"];
			$return["NoOfCav"][$ctr] 			= $row["NoOfCav"];
			$return["ProductWgt"][$ctr] 		= $row["ProductWgt"];
			$return["SprueWgt"][$ctr] 			= $row["SprueWgt"];
			$return["ShotWgt"][$ctr] 			= $row["ShotWgt"];
			$return["LaborCost"][$ctr] 			= $row["LaborCost"];
			$return["CycleTime"][$ctr] 			= $row["CycleTime"];
			$return["ProcessCost"][$ctr] 		= $row["ProcessCost"];
			$return["MaterialCost"][$ctr] 		= $row["MaterialCost"];
			$return["SecondProcessTime"][$ctr]	= $row["SecondProcessTime"];
			$return["SecondProcessCost"][$ctr]	= $row["SecondProcessCost"];
			$return["Profit"][$ctr] 			= $row["Profit"];
			$return["MoldingProdQuot"][$ctr] 	= $row["MoldingProdQuot"];
			$return["Remarks"][$ctr] 			= $row["Remarks"];
			$return["RevNo"][$ctr] 				= $row["RevNo"];
			$return["fkid"][$ctr] 				= $row["fkid"];
			$ctr++;
			$return['ctr'] = $ctr;
		}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	/*function addShipmentConfirmation() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addShipmentConfirmation($return['ControlNumber'], $return['Date'],$return['Customer'],$return['Total'],$return['Remarks'],$return['Username']);
		$return["msg"] = $result;
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function addItemSC() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addShipmentConfirmationTransaction($return['ControlNumber'], $return['PRNo'], $return['PartsCode'],$return['PartsName'], $return['Quantity'], $return['Price'], $return['Total'], $return['Username']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function viewShipmentConfirmationTransaction() {
		$ctr = 1;
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->viewShipmentConfirmationTransaction($return['ControlNumber']);
			while($row = mysqli_fetch_array($result) ) {			
				$return["PRNo".$ctr]  = $row['PRNo'];
				$return["PartsCode".$ctr]  = $row['Partscode'];	
				$return["PartsName".$ctr]  = $row['Partsname'];	
				$return["Quantity".$ctr]  = $row['Quantity'];
				$return["Price".$ctr]  = $row['Price'];
				$return["Total".$ctr]  = $row['Total'];
				$ctr++;
			}
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function deleteShipmentConfirmationTransaction() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->deleteShipmentConfirmationTransaction($return['ControlNumber']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function updateShipmentTransactionInfo() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updateShipmentConfirmationInfo($return['GrandTotal'], $return['ControlNumber'], $return['Remarks']);
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	*/

	function updateDeliveryConfirmation() {
		//$require_once('ppsdbs-oop.php');
		//$return = $_POST;
		//$result = '';
		///$result = PPSDBS::getInstance()->updateDeliveryConfirmation($return['orderNo'],$return['pkid'],$return['orderBalance'],$return['targetSO'],$return['shipmentDate'],$return['username']);
		//$result = PPSDBS::getInstance()->updateDeliveryConfirmation('121212','3','100','36','2014-10-26','ronaleef');
		//$return['result'] = json_encode($result);
		//echo json_encode($return);
		
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updateDeliveryConfirmation($return['orderNo'],$return['pkid'],$return['orderBalance'],$return['targetSO'],$return['shipmentDate'],$return['remarks'],$return['username'],$return['actual_so_u']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addInternalInvoice() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addInternalInvoice($return['ControlNumber'],$return['Date'],$return['RevNo'],$return['SoldTo'],$return['ShippedBy'],$return['GrandTotal'],$return['Username']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addInternalInvoiceTbl() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addInternalInvoiceTbl($return['ControlNumber'],$return['ProductCode'],$return['Description'],$return['PONumber'],$return['Quantity'],$return['UPrice'],$return['Total'],$return['Username']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function deleteInternalInvoice() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->deleteInternalInvoice($return['ControlNumber']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function getItemCodeInfoByItemCodeReal() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->getItemCodeInfoByItemCodeReal($return['ItemCode']);
		while($row = mysqli_fetch_array($result)) {
			$return['Desc'] = $row['Name'];
			$return['PONum'] = $row['OrderNo'];
			$return['UnitPrice'] = $row['PurchaseUnitCost'];
		}
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function orderNoCheckerDeliveryConfirmation() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->orderNoCheckerDeliveryConfirmation();
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function exportShipmentRecord() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->exportShipmentRecord('orderno', '','', $return['OrderNo']);
		while($row = mysqli_fetch_array($result)) {
			$return['DateIssued'] = $row['DateIssued'];
			$return['Category'] = $row['Category'];
			$return['ProductPONo'] = $row['ProductPONo'];
			$return['ItemCode'] = $row['ItemCode'];
			$return['ItemName'] = $row['ItemName'];
			$return['Price'] = $row['Price'];
			$return['ShipmentDate'] = $row['ShipmentDate'];
			$return['ActualSO'] = $row['ActualSO'];
			$return['Remarks'] = $row['Remarks'];
		}
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewRecordShipmentInvoice() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		session_start();
		$result = PPSDBS::getInstance()->addNewRecordShipmentInvoice($return['ControlNumber'],$return['Date'],$return['RevNo'],$return['SoldTo'],$return['ShippedBy'],$return['GrandTotal'],$return['Username'],$return['ctrno_preship']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewRecordShipment() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->addNewRecordShipment($return['controlNumber'],$return['dateIssued'],$return['orderNo'],$return['shipOut'],$return['unitPrice'],$return['amount'],$return['remarks'],$return['Username']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewRecordShipment_new() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->addNewRecordShipment_new($return['controlNumber'],$return['rec_list'],$return['Username']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}

	function loadSearchItem() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->loadSearchItem($return['Category']);
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
			$return['DeviceName'][$ctr] = $row['DeviceName'];
			$return['R3Code'][$ctr] = $row['R3Code'];
			$return['RackNum'][$ctr] = $row['RackNum'];
			$ctr++;
		}
		
		$return['ctr'] = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function getAllDeviceName() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->getAllDeviceName();
		$ctr = 0;
		while($row = mysqli_fetch_array($result)){
			$return['DeviceName'][$ctr] = $row['DeviceName'];
			$return['R3Code'][$ctr] = $row['R3Code'];
			$ctr++;
		}
		$return['ctr'] = $ctr;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function updateDiesetPartCode() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->updateDiesetPartCode($return['tbl_Name'],$return['DeviceName'],$return['R3Code']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	/* Shipment - Pre-shipment data loading module */
	
	function load_preshipment_control_no(){
		require_once('ppsdbs-oop.php');
		$return = array();
		$result = PPSDBS::getInstance()->load_preshipment_control_no($_POST['pattern']);
		$html = '';
		while($row = mysqli_fetch_array($result)){
			$html .= '<option value="'.$row['ctrno'].'" data-id="'.$row['id'].'">'.$row['ctrno'].'</option>';
		}
		$return['html'] = $html;
		echo json_encode($return);
	}
	
	function load_preshipment_information(){
		require_once('ppsdbs-oop.php');
		$return = array();
		$html = '';
		$GrandTotal = 0;
		$result = PPSDBS::getInstance()->load_preshipment_information($_POST['ctrno']);
		while($row = mysqli_fetch_array($result)){
			$html .= '<tr>';
			$html .= '	<td>'.$row['Partscode'].'</td>';
			$html .= '	<td>'.$row['DeviceName'].'</td>';
			$html .= '	<td>'.$row['PONo'].'</td>';
			$html .= '	<td>'.$row['Qty'].'</td>';
			$html .= '	<td>'.$row['Price'].'</td>';
			$html .= '	<td>'.number_format( ($row['Qty']*$row['Price']),4 ).'</td>';
			$html .= '	<td>'.$row['LotNo'].'</td>';
			$html .= '	<td></td>';
			$html .= '	<td>
							<button class="btn btn-info btn-sm glyphicon glyphicon-edit" title="edit"></button>
							<button class="btn btn-danger btn-sm glyphicon glyphicon-remove" title="remove"></button>
						</td>';
			$html .= '</tr>';
			$GrandTotal += ($row['Qty']*$row['Price']);
		}		
		$return['html'] = $html;
		$return['GrandTotal'] = number_format( $GrandTotal,4 );
		echo json_encode($return);
	}
	
	function load_preshipment_information_update(){
		require_once('ppsdbs-oop.php');
		$return = array();
		$html = '';
		$GrandTotal = 0;
		$result = PPSDBS::getInstance()->load_preshipment_information_update($_POST['ctrno']);
		for($x=0;$x<$result['ctr'];$x++){
			$html .= '<tr>';
			$html .= '	<td>'.$result['Partscode'][$x].'</td>';
			$html .= '	<td>'.$result['DeviceName'][$x].'</td>';
			$html .= '	<td>'.$result['PONo'][$x].'</td>';
			$html .= '	<td>'.$result['Qty'][$x].'</td>';
			$html .= '	<td>'.$result['Price'][$x].'</td>';
			$html .= '	<td>'.number_format( ($result['Qty'][$x]*$result['Price'][$x]),4 ).'</td>';
			$html .= '	<td>'.$result['LotNo'][$x].'</td>';
			$html .= '	<td>'.$result['Remarks'][$x].'</td>';
			$html .= '	<td>
							<button class="btn btn-info btn-sm glyphicon glyphicon-edit" title="edit"></button>
							<button class="btn btn-danger btn-sm glyphicon glyphicon-remove" title="remove"></button>
						</td>';
			$html .= '</tr>';
			$GrandTotal += ($result['Qty'][$x]*$result['Price'][$x]);
		}		
		$return['html'] = $html;
		$return['GrandTotal'] = number_format( $GrandTotal,4 );
		echo json_encode($return);
	}
	
	function load_invoice_main_information(){
		require_once('ppsdbs-oop.php');
		$return = array();
		$html = '';
		$GrandTotal = 0;
		$result = PPSDBS::getInstance()->load_invoice_main_information($_POST['id']);
		while($row = mysqli_fetch_array($result)){
			$return['id'] = $row['id'];
			$return['Current_Ctrl'] = $row['ControlNumber'];
			$return['ControlNumber'] = str_replace('-G','',$row['ControlNumber']);
			$return['RevNo'] = $row['RevNo'];
			$return['DateCreated'] = $row['DateCreated'];
			$return['ShippedBy'] = $row['ShippedBy'];
			$return['SoldTo'] = $row['SoldTo'];
			if(is_null($row['PreShipmentCtrl'])){
				$ctrlno = 'n/a';
			}else{
				$ctrlno = $row['PreShipmentCtrl'];
			}
			$return['id_tbl_PreShipment'] = $row['id_tbl_PreShipment'];
			$return['preship_ctrno'] = $ctrlno;
			$forGrinding = '';
			$Grinding = explode('-', $row['ControlNumber']);
			$Grinding = end($Grinding);
			if($Grinding == 'G'){
				$forGrinding = $Grinding;
			}
			$return['forGrinding'] = $forGrinding;
		}				
		echo json_encode($return);
	}
	
	function update_main_invoice_information(){
		require_once('ppsdbs-oop.php');
		$return = array();
		$result = PPSDBS::getInstance()->update_main_invoice_information($_POST);
		$return['msg'] = $result;
		echo json_encode($return);
	}
	
	function delete_all_current_item_in_invoice(){
		require_once('ppsdbs-oop.php');
		$return = array();
		$result = PPSDBS::getInstance()->delete_all_current_item_in_invoice($_POST['fkCtrl']);
		$return['msg'] = $result;
		echo json_encode($return);
	}
	
	
	/* Additional functions as of November 2018 revisions */
	function return_sold_to_list() {
		require_once('ppsdbs-oop.php');
		$html_select = '';
		$pattern 	 = $_POST['pattern'];
		$result = PPSDBS::getInstance()->return_sold_to_list($pattern);
		while($row = mysqli_fetch_array($result)) {
			$html_select .= '<option value="'.$row['sold_to'].'">'.$row['sold_to'].'</option>';
		}
		$return['html_select'] = $html_select;
		echo json_encode($return);
	}
	function return_shipment_schedule() {
		require_once('ppsdbs-oop.php');
		$time_from 	 = '';
		$time_to 	 = '';
		$date_end 	 = $_POST['date_end'];
		$schedule 	 = $_POST['schedule'];
		$result = PPSDBS::getInstance()->return_shipment_schedule($schedule);
		if($row = mysqli_fetch_array($result)) {
			$time_from 	= $row['time_from'];
			$time_to 	= $row['time_to'];
			if(date('A', strtotime($time_from)) == 'PM' && date('A', strtotime($time_to)) == 'AM') {
				$date_end = date('Y-m-d', strtotime($date_end.' +1day'));
			}
		}
		$return['time_from'] = $time_from;
		$return['time_to'] = $time_to;
		$return['date_end'] = $date_end;
		echo json_encode($return);
	}
	
?>