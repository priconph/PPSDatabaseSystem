<?php
class PPSDBS extends mysqli
	{
		private static $instance = null;
		
		//db connection config variables
		private $user = "root";
		private $pass = "r@p1d0nl1n3";
		private $dbName = "db_pps";
		private $dbHost = "localhost";

		public static function getInstance() {
			if(!self::$instance instanceof self) 
			{
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function __clone()
		{
			trigger_error('Clone is not allowed.',E_USER_ERROR);
		}
		
		public function __wakeup()
		{
			trigger_error('Deserializing is not allowed.',E_USER_ERROR);
		}
		
		private function __construct() {
			parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
			if(mysqli_connect_error())
			{
				exit('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			}
			parent::set_charset('utf-8');
		}

		////////////////////////////////////PUBLIC FUNCTIONS STARTS HERE//////////////////////////////////////////////
		
		
		public function sample1($ControlNumber){
			$query = "update tbl_ProductionSchedule set MailStatus = '2' where ControlNumber = '$ControlNumber'";
			return $this->query($query);
		}
		//PR GENERATETION
		public function MaterialsForPR2($MaterialType){
			$query = "SELECT `Material`, SUM(`TotalKgsNeeded`) AS TotalKgsNeeded,SUM(`TotalAdditionalKgs`) AS TotalAdditionalKgs,SUM(`WarehouseBalance`) AS WarehouseBalance,SUM(`ForPR`) AS ForPR FROM tbl_PRHistory WHERE `Material` = '$MaterialType' GROUP BY `Material`";
			return $this->query($query);
		}
		
		//access PPS Warehoues Data
		public function getPPSWarehouseTotalInAccum($Material){
			$query = "SELECT a.MaterialType, SUM(b.In) AS `In`  FROM tbl_Warehouse a INNER JOIN tbl_WarehouseTransaction b ON a.PartNumber = b.fkPartNumber WHERE a.MaterialType = '$Material' GROUP BY (a.MaterialType)";
			return $this->query($query);
		}
		
		public function getDiesetInconsistencies(){
			$query = "SELECT a.DeviceName, a.Material, a.YECMoldCtrlNo, a.R3Code, a.EXPCode, a.DrawingNo, a.Rev, a.Remarks, b.MaterialType, b.PartNumber FROM tbl_dieset a
						LEFT JOIN tbl_Warehouse b ON a.Material = b.MaterialType WHERE ISNULL(b.MaterialType)";
			return $this->query($query);
		}
		
		public function loadNonExistingPOReceived(){
			$query = "SELECT DISTINCT (a.Name),a.Supplier,a.YECPO,a.ItemCode,a.Name,a.OrderNo,a.SchdQty,a.PurchaseUnitCost,a.PurchaseOrderAmt,a.DateIssued,a.PPDReply,a.DateNeeded,a.Remarks,a.DrawingNo FROM tbl_POReceived a LEFT JOIN tbl_dieset b ON a.Name = b.DeviceName WHERE ISNULL
						(b.DeviceName)";
			return $this->query($query);
		}
		
		public function MaterialsForPR(){
			$query = "SELECT b.DeviceName, b.YECMoldCtrlNo, SUM(a.SchdQty) AS TotalQty, b.Material, b.NoOfCav, b.ShotWgt from tbl_POReceived a INNER JOIN tbl_dieset b ON a.Name = b.DeviceName GROUP BY b.DeviceName ORDER BY b.Material";
			return $this->query($query);
		}
		
		public function addDieset($date,$status,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username) {	//addDieset.php on btn_SaveDieset
			$checker = $this->query("SELECT * FROM db_pps.tbl_dieset WHERE tbl_dieset.YECMoldCtrlNo='".$YECControlNum."'");
			if($checker ->num_rows == 0) {
				$query = "INSERT INTO db_pps.tbl_dieset(Date,DiesetStatus,YECMoldCtrlNo,R3Code,EXPCode,DeviceName,DrawingNo,Rev,MoldClass,DieNo,NoOfCav,CavNo,ChangingPart,DiesetSize,DiesetWgt,UnitWgt,SprueWgt,ShotWgt,CycleShot,FixedAssetNo,DateManufactured,MoldMake,ToolLife,ToolShot,ToolLifePMI,MaintenanceCycle,InjectionMachine,Material,ToneColor,Remarks,LastUpdate,Username) 
					VALUES ('".$date."','".$status."','".$YECControlNum."','".$R3Code."','".$EXPCode."','".$DeviceName."','".$DrawingNum."','".$Rev."','".$MoldClass."','".$DieNo."','".$NumOfCavity."','".$CavNum."','".$ChangingPart."','".$DiesetSize."','".$DiesetWeight."','".$UnitWeight."','".$SprueWeight."','".$ShotWeight."','".$CycleTimePerShot."','".$FixedAssetNum."','".$DateManufactured."','".$MoldMake."','".$ToolLife."','".$ToolShot."','".$ToolLifePMI."','".$MaintenanceCycle."','".$InjectionMachine."','".$Material."','".$ToneColor."','".$Remarks."','".date('Y-m-d H:i:s')."','".$Username."');";
				$this->query($query);
				return "Successfully added!";
			} else {
				return "Dieset already exists!";
			}
		}	
		//START Export to Excel Generate Quotation //
		
		
		//END Export to Excel Generate Quotation//
		
		//START Generate Quotation Page //
		public function getQuotationByControlNumber($ControlNumber){
			$query = "SELECT tbl_DiesetQuotation.pkid, tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot, tbl_DiesetQuotation.Remarks FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.ControlNumber LIKE '%$ControlNumber%'";
			return $this->query($query);	
		}
		
		public function deleteDiesetQuotation($pkid){
			$query = "DELETE FROM tbl_DiesetQuotation where pkid = '$pkid'";
			return $this->query($query);
		}
		
		public function LoadQuotationInfo($pkid){
			$query = "SELECT tbl_DiesetQuotation.pkid, tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot, tbl_DiesetQuotation.Remarks FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.pkid = '$pkid'";
			return $this->query($query);
		}
		//END Generate Quotation Page//
		
		public function ExportToExcelGenerateQuotation($ControlNumber){
			$query = "SELECT tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.ControlNumber LIKE '%$ControlNumber%'";
			return $this->query($query);	
		}
		//START addGenerate Quotation //
		
		public function getAllDeviceName(){
			$query = "SELECT * FROM tbl_dieset";
			return $this->query($query);
		}
		
		public function addQuotation($fkid,$ControlNumber,$ResinCost,
									 $NumOfCavity,$ProductWeight,$SprueWeight,
									 $ShotWeight,$LaborCost,$CycleTime,
									 $ProcessCost,$MaterialCost,$SecondTimeProcess,
									 $SecondProcessCost,$Profit,$MoldingQuotation,
									 $Remarks,$LastUpdate,$Username){
			$query = "INSERT INTO tbl_DiesetQuotation VALUES('','$fkid','$ControlNumber','$ResinCost','$NumOfCavity','$ProductWeight','$SprueWeight','$ShotWeight','$LaborCost','$CycleTime','$ProcessCost','$MaterialCost','$SecondTimeProcess','$SecondProcessCost','$Profit','$MoldingQuotation','$Remarks','$LastUpdate','$Username')";
			return $this->query($query);
		}
		
		public function getDiesetSearch($SearchItem){
			$query = "SELECT * FROM db_pps.tbl_dieset where DeviceName LIKE '%$SearchItem%' OR EXPCode LIKE '%$SearchItem%' OR R3Code LIKE '%$SearchItem%' OR YECMoldCtrlNo LIKE '%$SearchItem%'";
			return $this->query($query);
		}
		
		public function GetDiesetByPartsCode($PartsCode){
			$query = "SELECT * FROM db_pps.tbl_dieset where R3Code = '$PartsCode'";
			return $this->query($query);
		}
		
		public function GetDiesetByDeviceName($DeviceName){
			$query = "SELECT * FROM db_pps.tbl_dieset where DeviceName = '$DeviceName'";
			return $this->query($query);
		}
		// END addGenerateQuotation //
		public function getAllControlNoQuotation(){
			$query = "SELECT DISTINCT(ControlNumber) FROM tbl_DiesetQuotation";
			return $this->query($query);
		}
		
		public function getQuotationDateRange($startDate,$endDate){
			$query = "SELECT * FROM tbl_DiesetQuotation where LastUpdate between '$startDate' and '$endDate'";
			return $this->query($query);
		}
		
		public function viewDiesetByDateRange($startDate,$endDate,$username) {	//dieset.php on set date button
			$query = "SELECT * FROM db_pps.tbl_dieset WHERE tbl_dieset.Date BETWEEN '".$startDate."' AND '".$endDate."'";
			return $this->query($query);
		}
		
		public function getDiesetTransferMaterial($YECControlNum) {	//dieset.php on clicking the tablerow
			$query = "SELECT * FROM db_pps.tbl_dieset WHERE tbl_dieset.YECMoldCtrlNo='".$YECControlNum."'";
			return $this->query($query);
		}
		
		public function updateDiesetTransferMaterial($date,$status,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username) {	//updateDieset.php on btn_UpdateDieset
			$query = "UPDATE db_pps.tbl_dieset SET Date='".$date."', DiesetStatus='".$status."',R3Code='".$R3Code."',EXPCode='".$EXPCode."',DeviceName='".$DeviceName."',DrawingNo='".$DrawingNum."',Rev='".$Rev."',MoldClass='".$MoldClass."',DieNo='".$DieNo."',NoOfCav='".$NumOfCavity."',CavNo='".$CavNum."',ChangingPart='".$ChangingPart."',DiesetSize='".$DiesetSize."',DiesetWgt='".$DiesetWeight."',UnitWgt='".$UnitWeight."',SprueWgt='".$SprueWeight."',ShotWgt='".$ShotWeight."',CycleShot='".$CycleTimePerShot."',FixedAssetNo='".$FixedAssetNum."',DateManufactured='".$DateManufactured."',MoldMake='".$MoldMake."',ToolLife='".$ToolLife."',ToolShot='".$ToolShot."',ToolLifePMI='".$ToolLifePMI."',MaintenanceCycle='".$MaintenanceCycle."',InjectionMachine='".$InjectionMachine."',Material='".$Material."',ToneColor='".$ToneColor."',Remarks='".$Remarks."',LastUpdate='".$LastUpdate."',Username='".$Username."' WHERE YECMoldCtrlNo='".$YECControlNum."'";
			return $this->query($query);
		}
		
		public function addPOReceived($supplier,$YECPO, $itemCode, $name, $orderNo, $schedQty, $purchaseUnitCost, $purchaseOrderAmt, $dateIssued, $PPDReply, $dateNeeded, $remarks, $drawingNo, $username) { 	//addPOReceived.php on btn_Save
			$checker = $this->query("SELECT * FROM db_pps.tbl_POReceived WHERE tbl_POReceived.ItemCode='".$itemCode."' AND logdel=0;");
			if($checker -> num_rows == 0) {
				$query = "INSERT INTO db_pps.tbl_POReceived(Supplier,YECPO,ItemCode,Name,OrderNo,SchdQty,PurchaseUnitCost,PurchaseOrderAmt,DateIssued,PPDReply,DateNeeded,Remarks,DrawingNo,LastUpdate,Username) VALUES('".$supplier."','".$YECPO."','".$itemCode."','".$name."','".$orderNo."','".$schedQty."','".$purchaseUnitCost."','".$purchaseOrderAmt."','".$dateIssued."','".$PPDReply."','".$dateNeeded."','".$remarks."','".$drawingNo."','".date('Y-m-d H:i:s')."','".$username."')";
				$this->query($query);
				return "Successfully added!";
			} else {
				return "Item code already exists!";
			}
		}
		
		public function viewPOReceived($keyword, $itemCode, $startDate, $endDate,$username) {
			if($keyword == 'itemcode') {
				$query = "SELECT * FROM db_pps.tbl_POReceived WHERE tbl_POReceived.ItemCode='".$itemCode."' AND logdel=0 ORDER BY LastUpdate DESC;";
			} else if($keyword == 'date'){
				$query = "SELECT * FROM db_pps.tbl_POReceived WHERE logdel=0 AND tbl_POReceived.DateIssued BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY LastUpdate DESC;";
			} else {
				$query = "SELECT * FROM db_pps.tbl_POReceived WHERE logdel=0 ORDER BY LastUpdate DESC";
			}
			return $this->query($query);
		}
		
		public function validateQuotation($fkid,$ControlNumber){
			$query = "SELECT * FROM tbl_DiesetQuotation where fkid='$fkid' AND ControlNumber = '$ControlNumber'";
			return $this->query($query);
		}
		
		public function getItemCodeList() {
			$query = "SELECT OrderNo FROM db_pps.tbl_POReceived WHERE logdel=0";
			return $this->query($query);
		}
		
		public function getItemCodeListReal() {
			$query = "SELECT ItemCode FROM db_pps.tbl_POReceived WHERE logdel=0";
			return $this->query($query);
		}
		
		public function getItemCodeInfoByItemCode($itemCode) {
			$query = "SELECT * FROM db_pps.tbl_POReceived WHERE OrderNo = '".$itemCode."' AND logdel=0";
			return $this->query($query);
		}
		
		public function getItemCodeInfoByItemCodeReal($itemCode) {
			$query = "SELECT * FROM db_pps.tbl_POReceived WHERE ItemCode = '".$itemCode."' AND logdel=0";
			return $this->query($query);
		}
		
		public function updatePOReceived($keyword, $supplier,$YECPO, $itemCode, $name, $orderNo, $schedQty, $purchaseUnitCost, $purchaseOrderAmt, $dateIssued, $PPDReply, $dateNeeded, $remarks, $drawingNo, $username) { 
			if($keyword == 'one') {
				$query = "UPDATE `tbl_POReceived` SET `PPDReply`='".$PPDReply."',`Remarks`='".$remarks."',`LastUpdate`='".date('Y-m-d H:i:s')."',`Username`='".$username."' WHERE `ItemCode`='".$itemCode."' AND logdel=0;";
			} else if($keyword == 'all'){
				$query = "UPDATE `tbl_POReceived` SET `Supplier`='".$supplier."',`YECPO`='".$YECPO."',`Name`='".$name."',`OrderNo`='".$orderNo."',`SchdQty`='".$schedQty."',`PurchaseUnitCost`='".$purchaseUnitCost."',`PurchaseOrderAmt`='".$purchaseOrderAmt."',`DateIssued`='".$dateIssued."',`PPDReply`='".$PPDReply."',`DateNeeded`='".$dateNeeded."',`Remarks`='".$remarks."',`DrawingNo`='".$drawingNo."',`LastUpdate`='".date('Y-m-d H:i:s')."',`Username`='".$username."' WHERE `ItemCode`='".$itemCode."' AND logdel=0;";
			}
			
			return $this->query($query);
		}
		
		public function addShipmentConfirmation($controlNo, $date,$customer,$total,$remarks,$username) {		//addShipmentConfirmation.php on btn_Save
			$checker = $this->query("SELECT * FROM db_pps.tbl_ShipmentConfirmation WHERE ControlNumber='".$controlNo."';");
			if($checker -> num_rows == 0) {
				$query = 'INSERT INTO db_pps.tbl_ShipmentConfirmation(ControlNumber,Date,Customer,Total,Remarks,LastUpdate,Username) VALUES("'.$controlNo.'","'.$date.'","'.$customer.'","'.$total.'","'.$remarks.'","'.date('Y-m-d H:i:s').'","'.$username.'")';
				return $this->query($query);
			} else {				
				return "Control Number already exists!";
			}
		}
		
		public function addShipmentConfirmationTransaction($fkControlNo, $PRNo, $partsCode,$partsName, $quantity, $price, $total, $username) {		//addShipmentConfirmation.php on btn_Save
			$query = 'INSERT INTO db_pps.tbl_ShipmentConfirmationTransaction(fkControlNumber,PRNo, PartsCode,PartsName,Quantity,Price,Total,LastUpdate,Username) VALUES("'.$fkControlNo.'","'.$PRNo.'","'.$partsCode.'","'.$partsName.'","'.$quantity.'","'.$price.'","'.$total.'","'.date('Y-m-d H:i:s').'","'.$username.'");';
			return $this->query($query);
		}
		
		public function viewShipmentConfirmation($startDate, $endDate) {
			$query = 'SELECT * FROM tbl_ShipmentConfirmation WHERE Date BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
			return $this->query($query);
		}
		
		public function getAllShipmentConfirmationControlNumber() {
			$query = "SELECT ControlNumber FROM db_pps.tbl_ShipmentConfirmation ORDER BY ControlNumber ASC";
			return $this->query($query);
		}
		public function viewShipmentConfirmationTransaction($controlNo) {
			$query = 'SELECT * FROM db_pps.tbl_ShipmentConfirmationTransaction WHERE tbl_ShipmentConfirmationTransaction.fkControlNumber ="'.$controlNo.'"';
			return $this->query($query);
		}
		
		public function viewShipmentConfirmationInfo($controlNo) {
			$query = "SELECT * FROM db_pps.tbl_ShipmentConfirmation WHERE ControlNumber='".$controlNo."';";
			return $this->query($query);
		}
		
		public function viewShipmentConfirmationInfoByKeyword($keyword, $controlNo, $startDate, $endDate) {
			if($keyword == 'controlnumber') {
				$query = "SELECT * FROM db_pps.tbl_ShipmentConfirmation WHERE ControlNumber='".$controlNo."';";
			} else if($keyword == 'date') {
				$query = "SELECT * FROM db_pps.tbl_ShipmentConfirmation WHERE Date BETWEEN '".$startDate."' AND '".$endDate."';";
			}
			return $this->query($query);
		}
		
		public function viewAllShipmentConfirmation() {
			$query = "SELECT * FROM db_pps.tbl_ShipmentConfirmation ORDER BY ControlNumber ASC;";
			return $this->query($query);
		}
		
		public function deleteShipmentConfirmationTransaction($fkControlNo) {
			$query = "DELETE FROM db_pps.tbl_ShipmentConfirmationTransaction WHERE fkControlNumber='".$fkControlNo."';";
			$this->query($query);
		}
		
		public function updateShipmentConfirmationInfo($grandTotal,$controlNumber,$remarks) {
			$query = "UPDATE db_pps.tbl_ShipmentConfirmation SET Total='".$grandTotal."', LastUpdate='".date('Y-m-d H:i:s')."',Remarks ='".$remarks."' WHERE ControlNumber='".$controlNumber."';";
			return $this->query($query);
		}
		
		public function generateExcelReport($controlNumber) {	//SHIPMENTCONFIRMATION-ExportToExcel.php
			$query = "SELECT * FROM db_pps.tbl_ShipmentConfirmation WHERE ControlNumber='".$controlNumber."';";
			return $this->query($query);
		}
		
		public function generateExcelTransactionReport($controlNumber) {
			$query = 'SELECT * FROM db_pps.tbl_ShipmentConfirmationTransaction WHERE tbl_ShipmentConfirmationTransaction.fkControlNumber ="'.$controlNumber.'"';
			return $this->query($query);
		}
		
		public function addInternalInvoice($controlNumber, $date, $revNo, $soldTo, $shippedBy, $grandTotal, $username) {	//addInternalInvoice.php on btn_Save
			$checker = $this->query('SELECT * FROM tbl_InternalInvoice WHERE ControlNumber="'.$controlNumber.'"');
			if($checker->num_rows == 0) {
				$query = 'INSERT INTO tbl_InternalInvoice (ControlNumber, Date, RevNo, SoldTo, ShippedBy, GrandTotal, Username, LastUpdate) VALUES("'.$controlNumber.'","'.$date.'","'.$revNo.'","'.$soldTo.'","'.$shippedBy.'","'.$grandTotal.'","'.$username.'","'.date('Y-m-d H:i:s').'")';
				return $this->query($query);
			} else {
				return "Control Number already exists!";
			}
		}
		
		public function addInternalInvoiceTbl($controlNumber,$productCode, $description, $poNumber, $quantity, $unitPrice, $total, $username) {
			$query = 'INSERT INTO tbl_InternalInvoiceTransaction (fkControlNumber,ProductCode,Description,PONumber,Quantity,UnitPrice, Total,Username,LastUpdate) VALUES("'.$controlNumber.'","'.$productCode.'","'.$description.'","'.$poNumber.'","'.$quantity.'","'.$unitPrice.'","'.$total.'","'.$username.'","'.date('Y-m-d H:i:s').'");';
			return $this->query($query);
		}
		
		public function deleteInternalInvoice($controlNumber) {
			$query = "DELETE FROM tbl_InternalInvoice WHERE ControlNumber='".$controlNumber."';";
			return $this->query($query);
		}
		
		public function getInternalInvoiceControlNumber() {
			$query = "SELECT ControlNumber FROM tbl_InternalInvoice ORDER BY ControlNumber DESC";
			return $this->query($query);
		}
		
		public function viewInternalInvoice($keyword, $controlNumber, $startDate, $endDate) {
			if($keyword == 'VIEWALL') {
				$query = "SELECT * FROM tbl_InternalInvoice ORDER BY Date DESC";
			} else if ($keyword == 'CONTROLNUMBER') {
				$query = "SELECT * FROM tbl_InternalInvoice WHERE ControlNumber ='$controlNumber' ORDER BY Date DESC";
			} else if($keyword == 'DATE') {
				$query = "SELECT * FROM tbl_InternalInvoice WHERE Date BETWEEN '$startDate' AND '$endDate' ORDER BY Date DESC";
			} 
			return $this->query($query);
		}
		
		public function getInternalInvoiceInfo($controlNumber) {
			$query = "SELECT * FROM tbl_InternalInvoice WHERE ControlNumber='$controlNumber'";
			return $this->query($query);
		}
		
		public function getInternalInvoiceItemTransaction($controlNumber) {
			$query = 'SELECT * FROM tbl_InternalInvoiceTransaction WHERE fkControlNumber="'.$controlNumber.'";';
			return $this->query($query);
		}
		
		//access PPS Warehoues Data
		public function getPPSWarehouseData(){
			$query = "SELECT * FROM tbl_Warehouse";
			return $this->query($query);
		}
		
}

class YPICS{
	//MALACHIXD CONNECTION INFO
	private $serverM = "192.168.3.251\MSSQLSERVER";
	private $userM ="builder"; 
	private $passM = ""; //empty
	private $databaseM = "";
	private $db_handleM =""; //empty
	
	//FUNCTION TO CONNECT TO MALACHI SERVER	
    private function connect_DBM(){
        $this->db_handleM = mssql_connect($this->serverM,$this->userM,$this->passM);
            return mssql_select_db($this->databaseM,$this->db_handleM);
    }
   
    //FUNCTION TO CLOSE MALACHI SERVER
    private function close_DBM(){
        if(isset($this->db_handleM)){
            mssql_close($this->db_handleM);
        }
    }
	
	/////////////////////////////////PUBLIC FUNCTIONS///////////////////////////////////////////////	
	//FUNCTION TO LOAD PO NUM FROM MALACHI DATABASE
	public function selectPoNum(){ //sample
		$db_found = $this->connect_DBM();
		if($db_found){
			$SQL = "SELECT * FROM VwProdSchedMaster ORDER BY PONum";
			$result = mssql_query($SQL);
			return $result;
			mssql_free_result($result);
		}
		else{
			$errorMsg = "Database not found, Please contact your administrator";
		}
		$this->close_DBM();
	}
	
}
?>
