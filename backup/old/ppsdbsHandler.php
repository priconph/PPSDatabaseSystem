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
				//case "addShipmentConfirmation" : addShipmentConfirmation(); break;
				//case "addItemSC" : addItemSC(); break;
				//case "viewShipmentConfirmationTransaction" : viewShipmentConfirmationTransaction(); break;
				//case "deleteShipmentConfirmationTransaction" : deleteShipmentConfirmationTransaction(); break;
				//case "updateShipmentTransactionInfo" : updateShipmentTransactionInfo(); break;
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
	function searchPoNum(){
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
	}
	
	//GENERATE QUOTATION//
	
	function deleteDiesetQuotation(){
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->deleteDiesetQuotation($return['pkid']);
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
		$result = PPSDBS::getInstance()->addDieset($return['Date'],$return['Status'],$return['YECControlNum'],$return['R3Code'],$return['ExpCode'],$return['DeviceName'],$return['DrawingNum'],$return['Rev'],$return['MoldClass'],$return['DieNo'],$return['NumOfCavity'],$return['CavNum'],$return['ChangingPart'],$return['RackNum'],$return['DiesetSize'],$return['DiesetWeight'],$return['UnitWeight'],$return['SprueWeight'],$return['ShotWeight'],$return['CycleTimePerShot'],$return['FixedAssetNum'],$return['DateManufactured'],$return['MoldMake'],$return['ToolLife'], $return['ToolShot'],$return['ToolLifePMI'],$return['MaintenanceCycle'],$return['InjectionMachine'],$return['Material'],$return['ToneColor'],$return['Remarks'],$return['Username']);
		$return["RevNo"] = $result;
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
	
	function updateDieset() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updateDiesetTransferMaterial($return['Date'],$return['Status'],$return['DateModified'],$return['DateReturned'],$return['YECControlNum'],$return['R3Code'],$return['ExpCode'],$return['DeviceName'],$return['DrawingNum'],$return['Rev'],$return['MoldClass'],$return['DieNo'],$return['NumOfCavity'],$return['CavNum'],$return['ChangingPart'],$return['RackNo'],$return['DiesetSize'],$return['DiesetWeight'],$return['UnitWeight'],$return['SprueWeight'],$return['ShotWeight'],$return['CycleTimePerShot'],$return['FixedAssetNum'],$return['DateManufactured'],$return['MoldMake'],$return['ToolLife'], $return['ToolShot'],$return['ToolLifePMI'],$return['MaintenanceCycle'],$return['InjectionMachine'],$return['Material'],$return['ToneColor'],$return['Remarks'],$return['Username']);
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
		$result = PPSDBS::getInstance()->updateDeliveryConfirmation($return['orderNo'],$return['pkid'],$return['orderBalance'],$return['targetSO'],$return['shipmentDate'],$return['remarks'],$return['username']);
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
		$result = PPSDBS::getInstance()->addNewRecordShipmentInvoice($return['ControlNumber'],$return['Date'],$return['RevNo'],$return['SoldTo'],$return['ShippedBy'],$return['GrandTotal'],$return['Username']);
		$return['msg'] = $result;
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewRecordShipment() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = '';
		$result = PPSDBS::getInstance()->addNewRecordShipment($return['controlNumber'],$return['dateIssued'],$return['orderNo'],$return['shipOut'],$return['unitPrice'],$return['amount'],$return['remarks'],$return['username']);
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
?>