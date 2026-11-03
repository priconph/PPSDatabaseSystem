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
				case "updateQuotation" : updateQuotation(); break;
				case "getItemCodeInfo" : getItemCodeInfo(); break;
				case "addShipmentConfirmation" : addShipmentConfirmation(); break;
				case "addItemSC" : addItemSC(); break;
				case "viewShipmentConfirmationTransaction" : viewShipmentConfirmationTransaction(); break;
				case "deleteShipmentConfirmationTransaction" : deleteShipmentConfirmationTransaction(); break;
				case "updateShipmentTransactionInfo" : updateShipmentTransactionInfo(); break;
				case "deleteDiesetQuotation" : deleteDiesetQuotation(); break;
				case "addInternalInvoice" : addInternalInvoice(); break;
				case "addInternalInvoiceTbl" : addInternalInvoiceTbl(); break;
				case "deleteInternalInvoice" : deleteInternalInvoice(); break;
				case "getItemCodeInfoByItemCodeReal" : getItemCodeInfoByItemCodeReal(); break;
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
	/////////////////////
	
	function addDieset() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addDieset($return['Date'],$return['Status'],$return['YECControlNum'],$return['R3Code'],$return['ExpCode'],$return['DeviceName'],$return['DrawingNum'],$return['Rev'],$return['MoldClass'],$return['DieNo'],$return['NumOfCavity'],$return['CavNum'],$return['ChangingPart'],$return['DiesetSize'],$return['DiesetWeight'],$return['UnitWeight'],$return['SprueWeight'],$return['ShotWeight'],$return['CycleTimePerShot'],$return['FixedAssetNum'],$return['DateManufactured'],$return['MoldMake'],$return['ToolLife'], $return['ToolShot'],$return['ToolLifePMI'],$return['MaintenanceCycle'],$return['InjectionMachine'],$return['Material'],$return['ToneColor'],$return['Remarks'],$return['Username']);
		$return["msg"] = $result;
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
		$result = PPSDBS::getInstance()->updateDiesetTransferMaterial($return['Date'],$return['Status'],$return['YECControlNum'],$return['R3Code'],$return['ExpCode'],$return['DeviceName'],$return['DrawingNum'],$return['Rev'],$return['MoldClass'],$return['DieNo'],$return['NumOfCavity'],$return['CavNum'],$return['ChangingPart'],$return['DiesetSize'],$return['DiesetWeight'],$return['UnitWeight'],$return['SprueWeight'],$return['ShotWeight'],$return['CycleTimePerShot'],$return['FixedAssetNum'],$return['DateManufactured'],$return['MoldMake'],$return['ToolLife'], $return['ToolShot'],$return['ToolLifePMI'],$return['MaintenanceCycle'],$return['InjectionMachine'],$return['Material'],$return['ToneColor'],$return['Remarks'],$return['Username']);
		$return['result'] = json_encode($result);
		echo json_encode($return);
	}
	
	function addNewPOReceived() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->addPOReceived($return['supplier'],$return['yecPO'], $return['itemCode'], $return['name'], $return['orderNo'], $return['schedQty'], $return['purchaseUCost'], $return['purchaseOrderAmount'], $return['dateIssued'], $return['ppdReply'], $return['dateNeeded'], $return['remarks'], $return['drawingNo'], $return['username']);
		$return['msg'] = $result;
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
	
	function updatePOReceived() {
		require_once('ppsdbs-oop.php');
		$return = $_POST;
		$result = "";
		$result = PPSDBS::getInstance()->updatePOReceived('all',$return['supplier'],$return['yecPO'], $return['itemCode'], $return['name'], $return['orderNo'], $return['schedQty'], $return['purchaseUCost'], $return['purchaseOrderAmount'], $return['dateIssued'], $return['ppdReply'], $return['dateNeeded'], $return['remarks'], $return['drawingNo'], $return['username']);
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

	function addShipmentConfirmation() {
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
	
?>