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
			//$query = "SELECT a.DeviceName, a.Material, a.YECMoldCtrlNo, a.R3Code, a.EXPCode, a.DrawingNo, a.Rev, a.Remarks, b.MaterialType, b.PartNumber FROM tbl_dieset a
			//			LEFT JOIN tbl_Warehouse b ON a.Material = b.MaterialType WHERE ISNULL(b.MaterialType)";
			$query = "SELECT a.LastUpdate,a.DeviceName, a.Material, a.YECMoldCtrlNo, a.R3Code, a.EXPCode, a.DrawingNo, a.Rev, a.Remarks, b.MaterialType, b.PartNumber FROM tbl_dieset a
						LEFT JOIN tbl_Warehouse b ON a.Material = b.MaterialType WHERE ISNULL(b.MaterialType) AND a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) ORDER BY a.LastUpdate DESC";
			return $this->query($query);
		}
		
		public function getDiesetInconsistencies_Export(){
			$query = "SELECT a.* FROM tbl_dieset a LEFT JOIN tbl_Warehouse b ON a.Material = b.MaterialType WHERE ISNULL(b.MaterialType)";
			return $this->query($query);
		}
		
		public function loadNonExistingPOReceived(){
			$query = "SELECT DISTINCT(a.ItemName) , a.OrderNo, b.DeviceName, a.Category, a.ProductPONo, a.ItemCode, a.OrderQty, a.Price, a.Amount, a.DateIssued, a.ShipOutPPS, a.POBalance FROM tbl_POReceived a LEFT JOIN tbl_dieset b ON a.ItemCode = b.R3Code WHERE ISNULL(b.DeviceName) OR b.DiesetStatus=3";
			return $this->query($query);
		}
		
		public function MaterialsForLoading() {
			//$query = "SELECT po.OrderNo,po.ItemCode,dieset.DeviceName,dieset.DrawingNo,dieset.Rev,dieset.Material,po.POBalance,wip.EOH AS wip,fgs.EOH AS fgs,dieset.ShotWgt,dieset.CavNo,dieset.CycleShot FROM tbl_POReceived po INNER JOIN tbl_dieset dieset ON po.ItemName = dieset.DeviceName LEFT JOIN tbl_DeliveryConfirmation delivery ON delivery.OrderNo = po.OrderNo LEFT JOIN tbl_WIPProduction wip ON wip.PartsCode = dieset.R3Code LEFT JOIN tbl_FGSProduction fgs ON fgs.PartsCode = dieset.r3Code WHERE po.POBalance > 0 AND delivery.ShipmentDate < '".date('Y-m-d')."' OR delivery.ShipmentDate IS NULL";
			//$query = "SELECT po.OrderNo,po.ItemCode,dieset.DeviceName,dieset.DrawingNo,dieset.Rev,dieset.Material,po.POBalance,wip.EOH AS wip,fgs.EOH AS fgs,dieset.ShotWgt,dieset.CavNo,dieset.CycleShot FROM tbl_POReceived po INNER JOIN tbl_dieset dieset ON po.ItemName = dieset.DeviceName LEFT JOIN tbl_DeliveryConfirmation delivery ON delivery.OrderNo = po.OrderNo LEFT JOIN tbl_WIPProduction wip ON wip.PartsCode = dieset.R3Code LEFT JOIN tbl_FGSProduction fgs ON fgs.PartsCode = dieset.r3Code WHERE dieset.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE dieset.DeviceName = c.DeviceName) AND po.POBalance > 0 AND delivery.ShipmentDate < '".date('Y-m-d')."' OR delivery.ShipmentDate IS NULL ";
			$query = "SELECT po.OrderNo,po.ItemCode,dieset.DeviceName,dieset.DrawingNo,dieset.Rev,dieset.Material,po.POBalance,wip.EOH AS wip,fgs.EOH AS fgs,dieset.ShotWgt,dieset.CavNo,dieset.CycleShot FROM tbl_POReceived po INNER JOIN tbl_dieset dieset ON po.ItemCode = dieset.R3Code LEFT JOIN tbl_DeliveryConfirmation delivery ON delivery.OrderNo = po.OrderNo LEFT JOIN tbl_WIPProduction wip ON wip.PartsCode = dieset.R3Code LEFT JOIN tbl_FGSProduction fgs ON fgs.PartsCode = dieset.r3Code WHERE dieset.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE dieset.R3Code = c.R3Code) AND po.POBalance > 0 AND delivery.ShipmentDate < '".date('Y-m-d')."' OR delivery.ShipmentDate IS NULL ";
			return $this->query($query);
		}
		
		public function MaterialsForPR(){
			//OLD QUERY
			//$query = "SELECT b.DeviceName, b.YECMoldCtrlNo, SUM(a.SchdQty) AS TotalQty, b.Material, b.NoOfCav, b.ShotWgt from tbl_POReceived a INNER JOIN tbl_dieset b ON a.Name = b.DeviceName GROUP BY b.DeviceName ORDER BY b.Material";
			//$query = "SELECT a.ItemName, b.DeviceName, a.POBalance, b.Material, b.NoOfCav, b.ShotWgt, c.Qty AS FGSQty, d.`EOH`,b.R3Code FROM tbl_POReceived a INNER JOIN tbl_dieset b ON a.ItemName = b.DeviceName LEFT JOIN tbl_FGSProduction c ON b.DeviceName = c.DeviceName LEFT JOIN tbl_WIPProduction d ON d.PartsName = c.DeviceName WHERE a.POBalance >0 GROUP BY a.ItemName";
			$query = "SELECT b.DrawingNo,a.ItemName, b.DeviceName, a.POBalance, b.Material, b.NoOfCav, b.ShotWgt, c.Qty AS FGSQty, d.`EOH`,b.R3Code FROM tbl_POReceived a INNER JOIN tbl_dieset b ON a.ItemCode = b.R3Code LEFT JOIN tbl_FGSProduction c ON b.R3Code = c.PartsCode LEFT JOIN tbl_WIPProduction d ON d.PartsCode = c.PartsCode WHERE b.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE b.R3Code = c.R3Code) AND a.POBalance > 0 GROUP BY a.ItemCode";
			return $this->query($query);
		}
		
		public function MaterialsForPRByMaterials(){
			//OLD QUERY
			//$query = "SELECT b.DeviceName, b.YECMoldCtrlNo, SUM(a.SchdQty) AS TotalQty, b.Material, b.NoOfCav, b.ShotWgt from tbl_POReceived a INNER JOIN tbl_dieset b ON a.Name = b.DeviceName GROUP BY b.DeviceName ORDER BY b.Material";
			//$query = "SELECT a.ItemName, b.DeviceName, SUM(a.POBalance) as POBalance, b.Material, b.NoOfCav, b.ShotWgt, c.Qty AS FGSQty, d.`EOH` FROM tbl_POReceived a INNER JOIN tbl_dieset b on a.ItemName = b.DeviceName LEFT JOIN tbl_FGSProduction c ON b.DeviceName = c.DeviceName LEFT JOIN tbl_WIPProduction d ON d.PartsName = c.DeviceName WHERE a.POBalance > 0 GROUP BY b.Material";
			$query = "SELECT a.ItemName, b.DeviceName, SUM(a.POBalance) as POBalance, b.Material, b.NoOfCav, b.ShotWgt, c.Qty AS FGSQty, d.`EOH` FROM tbl_POReceived a INNER JOIN tbl_dieset b on a.ItemName = b.DeviceName LEFT JOIN tbl_FGSProduction c ON b.DeviceName = c.DeviceName LEFT JOIN tbl_WIPProduction d ON d.PartsName = c.DeviceName WHERE b.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE b.DeviceName = c.DeviceName) AND a.POBalance > 0 GROUP BY b.Material";
			return $this->query($query);
		}
		
		public function MaterialsForPRByMaterialsbbb(){
			//OLD QUERY
			//$query = "SELECT b.DeviceName, b.YECMoldCtrlNo, SUM(a.SchdQty) AS TotalQty, b.Material, b.NoOfCav, b.ShotWgt from tbl_POReceived a INNER JOIN tbl_dieset b ON a.Name = b.DeviceName GROUP BY b.DeviceName ORDER BY b.Material";
			$query = "SELECT a.ItemName, b.DeviceName, a.POBalance, b.Material, b.NoOfCav, b.ShotWgt, c.Qty AS FGSQty, d.`EOH` FROM tbl_POReceived a INNER JOIN tbl_dieset b on a.ItemCode = b.R3Code LEFT JOIN tbl_FGSProduction c ON b.R3Code = c.PartsCode LEFT JOIN tbl_WIPProduction d ON d.PartsCode = c.PartsCode WHERE a.POBalance > 0 GROUP BY b.Material;";
			return $this->query($query);
		}
		
		public function addDieset($date,$status,$DateModified,$DateReturned,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$RackNum,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username) {	//addDieset.php on btn_SaveDieset
			//$checker = $this->query("SELECT * FROM db_pps.tbl_dieset WHERE tbl_dieset.R3Code='".$R3Code."' AND DiesetStatus='1'"); //R3 Code unique
			//$checker = $this->query("SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.DeviceName='".$DeviceName."' AND a.DiesetStatus='1'");
			$checker = $this->query("SELECT * FROM tbl_dieset WHERE `R3Code` = '$R3Code';");
			if($checker ->num_rows == 0) {
				$query = "INSERT INTO db_pps.tbl_dieset(Date,DiesetStatus,DateModified,DateReturned,YECMoldCtrlNo,R3Code,EXPCode,DeviceName,DrawingNo,Rev,MoldClass,DieNo,NoOfCav,CavNo,ChangingPart,RackNum,DiesetSize,DiesetWgt,UnitWgt,SprueWgt,ShotWgt,CycleShot,FixedAssetNo,DateManufactured,MoldMake,ToolLife,ToolShot,ToolLifePMI,MaintenanceCycle,InjectionMachine,Material,ToneColor,Remarks,LastUpdate,Username) 
					VALUES ('".$date."','".$status."','".$DateModified."','".$DateReturned."','".$YECControlNum."','".$R3Code."','".$EXPCode."','".$DeviceName."','".$DrawingNum."','".$Rev."','".$MoldClass."','".$DieNo."','".$NumOfCavity."','".$CavNum."','".$ChangingPart."','".$RackNum."','".$DiesetSize."','".$DiesetWeight."','".$UnitWeight."','".$SprueWeight."','".$ShotWeight."','".$CycleTimePerShot."','".$FixedAssetNum."','".$DateManufactured."','".$MoldMake."','".$ToolLife."','".$ToolShot."','".$ToolLifePMI."','".$MaintenanceCycle."','".$InjectionMachine."','".$Material."','".$ToneColor."','".$Remarks."','".date('Y-m-d H:i:s')."','".$Username."');";
				$this->query($query);
				/* $query = "INSERT INTO db_pps.tbl_dieset_history(Date,DiesetStatus,DateModified,DateReturned,YECMoldCtrlNo,R3Code,EXPCode,DeviceName,DrawingNo,Rev,MoldClass,DieNo,NoOfCav,CavNo,ChangingPart,RackNum,DiesetSize,DiesetWgt,UnitWgt,SprueWgt,ShotWgt,CycleShot,FixedAssetNo,DateManufactured,MoldMake,ToolLife,ToolShot,ToolLifePMI,MaintenanceCycle,InjectionMachine,Material,ToneColor,Remarks,LastUpdate,Username) 
					VALUES ('".$date."','".$status."','".$DateModified."','".$DateReturned."','".$YECControlNum."','".$R3Code."','".$EXPCode."','".$DeviceName."','".$DrawingNum."','".$Rev."','".$MoldClass."','".$DieNo."','".$NumOfCavity."','".$CavNum."','".$ChangingPart."','".$RackNum."','".$DiesetSize."','".$DiesetWeight."','".$UnitWeight."','".$SprueWeight."','".$ShotWeight."','".$CycleTimePerShot."','".$FixedAssetNum."','".$DateManufactured."','".$MoldMake."','".$ToolLife."','".$ToolShot."','".$ToolLifePMI."','".$MaintenanceCycle."','".$InjectionMachine."','".$Material."','".$ToneColor."','".$Remarks."','".date('Y-m-d H:i:s')."','".$Username."');";
				$this->query($query); */
				return "Success";
			} else {
				return $checker;
			}
		}
		
		public function addDiesetImport($date,$status,$DateModified,$DateReturned,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$RackNum,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username) {	//addDieset.php on btn_SaveDieset
			/* $checker = $this->query("SELECT * FROM tbl_dieset WHERE `R3Code` = '$R3Code';");
			if($checker ->num_rows == 0) {
			
			}else{
			
			} */
			$query = "INSERT INTO db_pps.tbl_dieset(Date,DiesetStatus,DateModified,DateReturned,YECMoldCtrlNo,R3Code,EXPCode,DeviceName,DrawingNo,Rev,MoldClass,DieNo,NoOfCav,CavNo,ChangingPart,RackNum,DiesetSize,DiesetWgt,UnitWgt,SprueWgt,ShotWgt,CycleShot,FixedAssetNo,DateManufactured,MoldMake,ToolLife,ToolShot,ToolLifePMI,MaintenanceCycle,InjectionMachine,Material,ToneColor,Remarks,LastUpdate,Username) 
				VALUES ('".$date."','".$status."','".$DateModified."','".$DateReturned."','".$YECControlNum."','".$R3Code."','".$EXPCode."','".$DeviceName."','".$DrawingNum."','".$Rev."','".$MoldClass."','".$DieNo."','".$NumOfCavity."','".$CavNum."','".$ChangingPart."','".$RackNum."','".$DiesetSize."','".$DiesetWeight."','".$UnitWeight."','".$SprueWeight."','".$ShotWeight."','".$CycleTimePerShot."','".$FixedAssetNum."','".$DateManufactured."','".$MoldMake."','".$ToolLife."','".$ToolShot."','".$ToolLifePMI."','".$MaintenanceCycle."','".$InjectionMachine."','".$Material."','".$ToneColor."','".$Remarks."','".date('Y-m-d H:i:s')."','".$Username."');";
			return $this->query($query);
		}
		
		public function add_obsolete_dieset($id){
			$query = $this->query("INSERT INTO tbl_dieset_obsolete SELECT * FROM tbl_dieset WHERE `id` = '$id';");
			$query2 = $this->query("DELETE FROM `tbl_dieset` WHERE `id` = '$id';");
			return "Old file moved to obsolete!";
		}
		//START Export to Excel Generate Quotation //
		
		
		//END Export to Excel Generate Quotation//
		
		//START Generate Quotation Page //
		public function getQuotationByControlNumber($ControlNumber){
			//$query = "SELECT tbl_DiesetQuotation.pkid, tbl_DiesetQuotation.fkid, tbl_DiesetQuotation.RevNo, tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot, tbl_DiesetQuotation.Remarks FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.logdel=0 AND tbl_DiesetQuotation.ControlNumber LIKE '%$ControlNumber%'";
			$query = "SELECT tbl_DiesetQuotation.pkid, tbl_DiesetQuotation.fkid, tbl_DiesetQuotation.RevNo, tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot, tbl_DiesetQuotation.Remarks FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.logdel=0 AND tbl_dieset.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE tbl_dieset.DeviceName = c.DeviceName) AND tbl_DiesetQuotation.ControlNumber LIKE '%$ControlNumber%'";
			return $this->query($query);	
		}
		
		public function deleteDiesetQuotation($pkid){
			$query = "UPDATE tbl_DiesetQuotation SET logdel=1 WHERE pkid = '$pkid'";	//instead of deleting the record, create lodgel to check the deleted record
			return $this->query($query);
		}
		
		public function LoadQuotationInfo($pkid){
			//$query = "SELECT tbl_DiesetQuotation.pkid, tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot, tbl_DiesetQuotation.Remarks FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.pkid = '$pkid'";
			$query = "SELECT tbl_DiesetQuotation.pkid, tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot, tbl_DiesetQuotation.Remarks FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_dieset.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE tbl_dieset.DeviceName = c.DeviceName) AND tbl_DiesetQuotation.pkid = '$pkid'";
			return $this->query($query);
		}
				
		public function ExportToExcelGenerateQuotation($ControlNumber){
			//$query = "SELECT tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.RevNo,tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_DiesetQuotation.ControlNumber LIKE '%$ControlNumber%'";
			$query = "SELECT tbl_dieset.DeviceName, tbl_dieset.Material, tbl_dieset.R3Code, tbl_DiesetQuotation.RevNo,tbl_DiesetQuotation.ControlNumber, tbl_DiesetQuotation.ResinCost, tbl_DiesetQuotation.NoOfCav, tbl_DiesetQuotation.ProductWgt, tbl_DiesetQuotation.SprueWgt, tbl_DiesetQuotation.ShotWgt, tbl_DiesetQuotation.LaborCost, tbl_DiesetQuotation.CycleTime, tbl_DiesetQuotation.ProcessCost, tbl_DiesetQuotation.MaterialCost, tbl_DiesetQuotation.SecondProcessTime, tbl_DiesetQuotation.SecondProcessCost, tbl_DiesetQuotation.Profit, tbl_DiesetQuotation.MoldingProdQuot FROM tbl_dieset INNER JOIN tbl_DiesetQuotation ON tbl_dieset.id = tbl_DiesetQuotation.fkid WHERE tbl_dieset.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE tbl_dieset.DeviceName = c.DeviceName) AND tbl_DiesetQuotation.ControlNumber LIKE '%$ControlNumber%'";
			return $this->query($query);	
		}
			
		public function getAllDeviceName(){
			$query = "SELECT * FROM tbl_dieset";
			return $this->query($query);
		}
		
		public function addQuotation($fkid,$ControlNumber,$ResinCost,
									 $NumOfCavity,$ProductWeight,$SprueWeight,
									 $ShotWeight,$LaborCost,$CycleTime,
									 $ProcessCost,$MaterialCost,$SecondTimeProcess,
									 $SecondProcessCost,$Profit,$MoldingQuotation,
									 $RevNo,$Remarks,$LastUpdate,$Username){
			$query = "INSERT INTO tbl_DiesetQuotation VALUES('','$fkid','$ControlNumber','$ResinCost','$NumOfCavity','$ProductWeight','$SprueWeight','$ShotWeight','$LaborCost','$CycleTime','$ProcessCost','$MaterialCost','$SecondTimeProcess','$SecondProcessCost','$Profit','$MoldingQuotation','$RevNo','$Remarks',0,'$LastUpdate','$Username')";
			return $this->query($query);
		}
		
		public function addQuotationImport($ControlNumber,$Device,$ResinCost,
									 $NumOfCavity,$ProductWeight,$SprueWeight,
									 $ShotWeight,$LaborCost,$CycleTime,
									 $ProcessCost,$MaterialCost,$SecondTimeProcess,
									 $SecondProcessCost,$Profit,$MoldingQuotation,
									 $Remarks,$LastUpdate,$Username){
			$fkid = 0;
			//$queryFkid = $this->query("SELECT id FROM tbl_dieset WHERE DeviceName='$Device'");
			$queryFkid = $this->query("SELECT a.id FROM tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND DeviceName='$Device'");
			while($row = mysqli_fetch_array($queryFkid)){
				$fkid = $row['id'];
			}
			$query = "INSERT INTO tbl_DiesetQuotation VALUES('','$fkid','$ControlNumber','$ResinCost','$NumOfCavity','$ProductWeight','$SprueWeight','$ShotWeight','$LaborCost','$CycleTime','$ProcessCost','$MaterialCost','$SecondTimeProcess','$SecondProcessCost','$Profit','$MoldingQuotation','$Remarks',0,'$LastUpdate','$Username')";
			$this->query($query);
			return $fkid;
		}
		
		public function updateQuotation($pkid,$ControlNumber,$ResinCost,
									 $NumOfCavity,$ProductWeight,$SprueWeight,
									 $ShotWeight,$LaborCost,$CycleTime,
									 $ProcessCost,$MaterialCost,$SecondTimeProcess,
									 $SecondProcessCost,$Profit,$MoldingQuotation,
									 $Remarks,$LastUpdate,$Username){
			$query = "UPDATE `tbl_DiesetQuotation` SET `ResinCost`='$ResinCost',`NoOfCav`='$NumOfCavity',`ProductWgt`='$ProductWeight',`SprueWgt`='$SprueWeight',`ShotWgt`='$ShotWeight',`LaborCost`='$LaborCost',`CycleTime`='$CycleTime',`ProcessCost`='$ProcessCost',`MaterialCost`='$MaterialCost',`SecondProcessTime`='$SecondTimeProcess',`SecondProcessCost`='$SecondProcessCost',`Profit`='$Profit',`MoldingProdQuot`='$MoldingQuotation',`Remarks`='$Remarks',`LastUpdate`='$LastUpdate',`Username`='$Username' WHERE pkid = '$pkid'";
			return $this->query($query);
			//return $query;
		}
		public function DeleteAllQuotation($ControlNumber){
			//get first the Revision number before deleting it to database
			$query = $this->query("SELECT DISTINCT(tbl_DiesetQuotation.RevNo) FROM tbl_DiesetQuotation WHERE tbl_DiesetQuotation.ControlNumber='$ControlNumber'");
			if($query->num_rows == 0) {
				$RevNo = 0;
			} else {
				while($row = mysqli_fetch_array($query)) {
					$RevNo = $row['RevNo']+1;
				}
			}
			
			$delete_record_on_dieset_with_controlno = $this->query("DELETE FROM tbl_DiesetQuotation WHERE ControlNumber = '$ControlNumber'");
			return $RevNo;
		}
		
		//END addGenerate Quotation //
		public function getDiesetSearch($SearchItem,$Category){
			if($Category == "1"){
				$Category = 'DeviceName';
			}else if($Category == "2"){
				$Category = 'R3Code';
			}else if($Category == "3"){
				$Category = 'RackNum';
			}
			if($SearchItem == "SELECT" && $Category == ""){
				$query = "SELECT * FROM tbl_dieset WHERE `DeviceName` = '$SearchItem';";
			}else if ($Category == "4"){
				$query = "SELECT * FROM tbl_dieset";
			}else{
				$query = "SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.".$Category." LIKE '%$SearchItem%' ";
			}
			return $this->query($query);
		}
		
		public function export_dieset(){
			$query = "SELECT * FROM tbl_dieset;";
			return $this->query($query);
		}
		
		public function getRevisedDiesetSearch($SearchItem,$Category){
			if($Category == "1"){
				$Category = 'DeviceName';
			}else if($Category == "2"){
				$Category = 'R3Code';
			}else if($Category == "3"){
				$Category = 'RackNum';
			}
			$query = "SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.".$Category." = '$SearchItem' AND a.DiesetStatus = '2'";
			return $this->query($query);
		}
		
		public function GetDiesetByPartsCode($PartsCode){
			$query = "SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.R3Code = '$PartsCode'";
			return $this->query($query);
		}
		
		public function GetDiesetByDeviceName($DeviceName){
			$query = "SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.DeviceName = '$DeviceName'";
			return $this->query($query);
		}
		
		public function addGenerateQuotation($ControlNo,$Device,$MaterialName,
											$PartsCode,$BuyingPrice,$NoOfCav,
											$ProductWgt,$SprueWgt,$ShowWgt,
											$LaborCostHr,$CycleTime,$ProcessCost,
											$MaterialCostPerPiece,$SecondProcessTime,$SecondProcessCost,
											$Profit,$MoldingProductQuotation,$Remarks,
											$Username){
			$query = "INSERT INTO `tbl_DiesetQuotation`(`pkid`, `fkid`, `ControlNumber`, `ResinCost`, `NoOfCav`, `ProductWgt`, `SprueWgt`, `ShotWgt`, `LaborCost`, `CycleTime`, `ProcessCost`, `MaterialCost`, `SecondProcessTime`, `SecondProcessCost`, `Profit`, `MoldingProdQuot`, `Remarks`, `LastUpdate`, `Username`) VALUES ()";
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
			$query = "SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.Date BETWEEN '".$startDate."' AND '".$endDate."'";
			return $this->query($query);
			
		}
		public function viewRevisedDiesetByDateRange($startDate,$endDate,$username) {	//dieset.php on set date button
			$query = "SELECT * FROM db_pps.tbl_dieset_history WHERE tbl_dieset_history.Date BETWEEN '".$startDate."' AND '".$endDate."'";
			return $this->query($query);
		}
		
		public function getDiesetTransferMaterial($R3Code,$Rev) {	//dieset.php on clicking the tablerow
			/* $query = $this->query("SELECT * FROM db_pps.tbl_dieset_history WHERE tbl_dieset_history.R3Code='".$R3Code."' AND tbl_dieset_history.Rev='".$Rev."'"); */
			/* if($query ->num_rows == 0) { */
				//$query = $this->query("SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.R3Code='".$R3Code."' AND a.Rev='".$Rev."'");
				$query = $this->query("SELECT a.* FROM db_pps.tbl_dieset a WHERE a.LastUpdate = (SELECT MAX(c.LastUpdate) FROM tbl_dieset c WHERE a.DeviceName = c.DeviceName) AND a.R3Code='".$R3Code."'");
				return $query;
			/* } else {
				return $query;
			} */
		}
		
		public function updateDiesetTransferMaterial($id,$date,$status,$DateModified,$DateReturned,$YECControlNum,$R3Code,$EXPCode,$DeviceName,$DrawingNum,$Rev,$MoldClass,$DieNo,$NumOfCavity,$CavNum,$ChangingPart,$RackNum,$DiesetSize,$DiesetWeight,$UnitWeight,$SprueWeight,$ShotWeight,$CycleTimePerShot,$FixedAssetNum,$DateManufactured,$MoldMake,$ToolLife, $ToolShot,$ToolLifePMI,$MaintenanceCycle,$InjectionMachine,$Material,$ToneColor,$Remarks,$Username) {	//updateDieset.php on btn_UpdateDieset
			//$checker = $this->query("SELECT * FROM db_pps.tbl_dieset_history WHERE tbl_dieset.R3Code='".$R3Code."' AND tbl_dieset.Rev='".$Rev."'"); //R3Code unique
			/* if($status == 1) { */
				$query = "UPDATE db_pps.tbl_dieset SET Date='".$date."',DateModified='".$DateModified."',DateReturned='".$DateReturned."',DiesetStatus = '".$status."', YECMoldCtrlNo='".$YECControlNum."',R3Code='".$R3Code."',
					EXPCode='".$EXPCode."',DeviceName='".$DeviceName."',DrawingNo='".$DrawingNum."',Rev='".$Rev."',
					MoldClass='".$MoldClass."',DieNo='".$DieNo."',NoOfCav='".$NumOfCavity."',CavNo='".$CavNum."',ChangingPart='".$ChangingPart."',RackNum='".$RackNum."',
					DiesetSize='".$DiesetSize."',DiesetWgt='".$DiesetWeight."',UnitWgt='".$UnitWeight."',SprueWgt='".$SprueWeight."',ShotWgt='".$ShotWeight."',CycleShot='".$CycleTimePerShot."',FixedAssetNo='".$FixedAssetNum."',
					DateManufactured='".$DateManufactured."',MoldMake='".$MoldMake."',ToolLife='".$ToolLife."',ToolShot='".$ToolShot."',ToolLifePMI='".$ToolLifePMI."',MaintenanceCycle='".$MaintenanceCycle."',
					InjectionMachine='".$InjectionMachine."',Material='".$Material."',ToneColor='".$ToneColor."',Remarks='".$Remarks."',LastUpdate='".date('Y-m-d H:i:s')."',Username='".$Username."' WHERE 
					id='".$id."';";
				$this->query($query);
				//return $query;
				return "Successfully Updated!";
			/* } else {
				$checker = $this->query("SELECT * FROM db_pps.tbl_dieset_history WHERE tbl_dieset_history.DeviceName='".$DeviceName."' AND tbl_dieset_history.Rev='".$Rev."'"); //Device Name Unique
				if($checker ->num_rows == 0) {
					$query = "INSERT INTO db_pps.tbl_dieset (Date,DiesetStatus,DateModified,DateReturned,YECMoldCtrlNo,R3Code,EXPCode,DeviceName,DrawingNo,Rev,MoldClass,DieNo,NoOfCav,CavNo,ChangingPart,RackNum,DiesetSize,DiesetWgt,UnitWgt,SprueWgt,ShotWgt,CycleShot,FixedAssetNo,DateManufactured,MoldMake,ToolLife,ToolShot,ToolLifePMI,MaintenanceCycle,InjectionMachine,Material,ToneColor,Remarks,LastUpdate,Username) 
						VALUES ('".$date."','".$status."','".$DateModified."','".$DateReturned."','".$YECControlNum."','".$R3Code."','".$EXPCode."','".$DeviceName."','".$DrawingNum."','".$Rev."','".$MoldClass."','".$DieNo."','".$NumOfCavity."','".$CavNum."','".$ChangingPart."','".$RackNum."','".$DiesetSize."','".$DiesetWeight."','".$UnitWeight."','".$SprueWeight."','".$ShotWeight."','".$CycleTimePerShot."','".$FixedAssetNum."','".$DateManufactured."','".$MoldMake."','".$ToolLife."','".$ToolShot."','".$ToolLifePMI."','".$MaintenanceCycle."','".$InjectionMachine."','".$Material."','".$ToneColor."','".$Remarks."','".date('Y-m-d H:i:s')."','".$Username."');";
					$this->query($query);
					//return $query;
					return "Successfully updated!";
				} else {
					return "Dieset and Revision number already exists!";
				}
			} */
		}
		
		/*public function addPOReceived($supplier,$YECPO, $itemCode, $name, $orderNo, $schedQty, $purchaseUnitCost, $purchaseOrderAmt, $dateIssued, $PPDReply, $dateNeeded, $remarks, $drawingNo, $username) { 	//addPOReceived.php on btn_Save
			$checker = $this->query("SELECT * FROM db_pps.tbl_POReceived WHERE tbl_POReceived.ItemCode='".$itemCode."' AND logdel=0;");
			if($checker -> num_rows == 0) {
				$query = "INSERT INTO db_pps.tbl_POReceived(Supplier,YECPO,ItemCode,Name,OrderNo,SchdQty,PurchaseUnitCost,PurchaseOrderAmt,DateIssued,PPDReply,DateNeeded,Remarks,DrawingNo,LastUpdate,Username) VALUES('".$supplier."','".$YECPO."','".$itemCode."','".$name."','".$orderNo."','".$schedQty."','".$purchaseUnitCost."','".$purchaseOrderAmt."','".$dateIssued."','".$PPDReply."','".$dateNeeded."','".$remarks."','".$drawingNo."','".date('Y-m-d H:i:s')."','".$username."')";
				$this->query($query);
				return "Successfully added!";
			} else {
				return "Item code already exists!";
			}
		} revised due to RNS format*/
		
		public function addPOReceived($category,$poNo, $itemCode, $itemName, $orderNo, $orderQty, $price, $amount, $dateIssued, $shipOut, $poBalance, $drawingNo, $username) { 	//addPOReceived.php on btn_Save
			$checker = $this->query("SELECT * FROM db_pps.tbl_POReceived WHERE tbl_POReceived.OrderNo='".$orderNo."' AND logdel=0;");
			if($checker -> num_rows == 0) {
				$query = "INSERT INTO db_pps.tbl_POReceived(Category,ProductPONo,ItemCode,ItemName,OrderNo,OrderQty,Price,Amount,DateIssued,ShipOutPPS,POBalance,DrawingNo,LastUpdate,Username,logdel) VALUES('".$category."','".$poNo."','".$itemCode."','".$itemName."','".$orderNo."','".$orderQty."','".$price."','".$amount."','".$dateIssued."','".$shipOut."','".$poBalance."','".$drawingNo."','".date('Y-m-d H:i:s')."','".$username."', 0)";
				$this->query($query);
				return "Successfully added!";
			} else {
				return "Order No.: ".$orderNo." already exists!";
			}
		}
		
		public function viewPOReceived($keyword, $orderNo, $startDate, $endDate) {
			if($keyword == 'orderno') {
				$query = "SELECT * FROM db_pps.tbl_POReceived WHERE tbl_POReceived.OrderNo='".$orderNo."' AND logdel=0 ORDER BY LastUpdate DESC;";
			} else if($keyword == 'date'){
				$query = "SELECT * FROM db_pps.tbl_POReceived WHERE logdel=0 AND tbl_POReceived.DateIssued BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY LastUpdate DESC;";
			} else if($keyword == 'VIEWALL'){
				$query = "SELECT * FROM db_pps.tbl_POReceived WHERE logdel=0 ORDER BY LastUpdate DESC";
			}
			return $this->query($query);
		}
		
		public function exportNoRecordDeliveryConfirmation($date_start,$date_end) {	//export on PO Receive
			//$query = $this->query('SELECT a.DateIssued,a.Category,a.ProductPONo,a.ItemCode,a.ItemName,a.OrderNo,a.POBalance FROM `tbl_POReceived` a LEFT JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo WHERE b.OrderNo IS NULL AND a.POBalance > 0;');
			$query = $this->query("SELECT a.DateIssued,a.Category,a.ProductPONo,a.ItemCode,a.ItemName,a.OrderNo,a.POBalance FROM `tbl_POReceived` a LEFT JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo WHERE b.OrderNo IS NULL AND a.POBalance > 0 AND a.DateIssued BETWEEN '$date_start' AND '$date_end';");
			return $query;
		}
		
		public function exportRecordsDeliveryConfirmation($date_start,$date_end) {	//export on PO Receive
			$query = "SELECT a.DateIssued,a.Category,a.ProductPONo,a.ItemCode,a.ItemName,a.OrderNo, 
				b.ShipmentDate,b.OrderBalance,b.TargetSO,a.POBalance
				FROM tbl_POReceived a
				INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo
				WHERE a.POBalance >0
				AND  '".date('Y-m-d')."' > b.ShipmentDate AND a.DateIssued BETWEEN '$date_start' AND '$date_end'";
			return $this->query($query);
		}
		public function validateQuotation($fkid,$ControlNumber){
			$query = "SELECT * FROM tbl_DiesetQuotation where fkid='$fkid' AND ControlNumber = '$ControlNumber'";
			return $this->query($query);
		}
		
		public function getOrderNoListPOReceived() {
			$query = "SELECT OrderNo FROM db_pps.tbl_POReceived WHERE logdel=0";
			return $this->query($query);
		}
		
		public function updatePOReceived($keyword, $category,$poNo, $itemCode, $itemName, $orderNo, $orderQty, $price, $amount, $dateIssued, $shipOut, $poBalance, $drawingNo, $username) { 
			if($keyword == 'one') {
				$query = "UPDATE `tbl_POReceived` SET `PPDReply`='".$PPDReply."',`Remarks`='".$remarks."',`LastUpdate`='".date('Y-m-d H:i:s')."',`Username`='".$username."' WHERE `OrderNo`='".$orderNo."' AND logdel=0;";
			} else if($keyword == 'all'){
				$query = "UPDATE `tbl_POReceived` SET `Category`='".$category."',`ProductPONo`='".$poNo."',`ItemName`='".$itemName."',`ItemCode`='".$itemCode."',`OrderQty`='".$orderQty."',`Price`='".$price."',`Amount`='".$amount."',`ShipOutPPS`='".$shipOut."',`POBalance`='".$poBalance."',`LastUpdate`='".date('Y-m-d H:i:s')."',`Username`='".$username."',`DrawingNo`='".$drawingNo."' WHERE `OrderNo`='".$orderNo."' AND logdel=0;";
			}
			
			return $this->query($query);
		}
		
		
		//
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
		//
		
		public function addDeliveryConfirmation($dateIssued,$orderNo,$orderBalance,$targetSO,$shipmentDate,$remarks,$username) {
			$checker = $this->query('SELECT OrderNo FROM tbl_POReceived WHERE OrderNo="'.$orderNo.'";');	//check if the OrderNo imported is already on POReceived table
			if($checker->num_rows != 0) {
				$query = 'INSERT INTO tbl_DeliveryConfirmation(DateIssued,OrderNo,OrderBalance,TargetSO,ShipmentDate,Remarks,LastUpdate,Username) VALUES("'.$dateIssued.'","'.$orderNo.'","'.$orderBalance.'","'.$targetSO.'","'.$shipmentDate.'","'.$remarks.'","'.date('Y-m-d H:i:s').'","'.$username.'");';
				$this->query($query);
				$msg = 'ok';
			} else {
				$msg = 'Order No: '.$orderNo.' does not exists on PO Received data!';
			}
			return $msg; 
		}
		
		public function getDeliveryConfirmation($keyword, $startDate, $endDate, $orderNo) {
			if($keyword == 'date') {
				$query = 'SELECT DISTINCT(a.OrderNo) AS OrderNo FROM tbl_POReceived a INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo WHERE b.logdel = 0 AND a.DateIssued BETWEEN "2014-01-01" AND "2015-12-25" AND status="0";';
			} else if($keyword == 'orderno') {
				$query = 'SELECT a.ItemCode,a.ItemName,a.OrderNo,b.OrderBalance,b.TargetSO,b.ShipmentDate,c.EOH AS WIP,d.EOH AS FGS,b.id FROM tbl_DeliveryConfirmation b INNER
				JOIN tbl_POReceived a ON b.OrderNo = a.OrderNo LEFT JOIN tbl_WIPProduction c ON c.PartsCode = a.ItemCode LEFT JOIN tbl_FGSProduction d ON d.PartsCode = a.ItemCode WHERE a.logdel=0 AND b.logdel=0 AND a.OrderNo="'.$orderNo.'" ORDER BY a.ItemCode';
			} else if($keyword == 'all') {
				$query = 'SELECT a.ItemCode,a.ItemName,a.OrderNo,b.OrderBalance,b.TargetSO,b.ShipmentDate,c.EOH AS WIP,d.EOH AS FGS,b.id FROM tbl_DeliveryConfirmation b INNER
				JOIN tbl_POReceived a ON b.OrderNo = a.OrderNo LEFT JOIN tbl_WIPProduction c ON c.PartsCode = a.ItemCode LEFT JOIN tbl_FGSProduction d ON d.PartsCode = a.ItemCode WHERE a.logdel=0 AND b.logdel=0 ORDER BY a.ItemCode';
			} else if($keyword == 'excel') {
				$query = 'SELECT a.ItemCode,a.ItemName,a.OrderNo,b.OrderBalance,b.TargetSO,b.ShipmentDate,c.EOH AS WIP,d.EOH AS FGS,b.id FROM tbl_DeliveryConfirmation b INNER
				JOIN tbl_POReceived a ON b.OrderNo = a.OrderNo LEFT JOIN tbl_WIPProduction c ON c.PartsCode = a.ItemCode LEFT JOIN tbl_FGSProduction d ON d.PartsCode = a.ItemCode WHERE a.logdel=0 AND b.logdel=0 AND b.ShipmentDate BETWEEN "'.$startDate.'" AND "'.$endDate.'" ORDER BY a.ItemCode';
			} else if($keyword == 'orderno2') {
				$query = 'SELECT e.ActualSO AS ActualSO,e.Variance,e.LotNumber,b.Remarks FROM tbl_DeliveryConfirmation b INNER
				JOIN tbl_POReceived a ON b.OrderNo = a.OrderNo INNER JOIN tbl_DeliveryUpdateRecord e ON e.pkid = b.id WHERE a.logdel=0 AND b.logdel=0 AND a.OrderNo="'.$orderNo.'" ORDER BY e.LastUpdate';
			}
			return $this->query($query);
		}
		
		public function getDeliveryConfirmationProductionValue($pkid) {
			$query = 'SELECT * FROM tbl_DeliveryUpdateRecord WHERE pkid="'.$pkid.'"';
			return $this->query($query);
		}
		public function getOrderNoListDeliveryConfirmation() {
			$query = 'SELECT DISTINCT(OrderNo) FROM tbl_DeliveryConfirmation WHERE logdel=0';
			return $this->query($query);
		}
		
		public function updateDeliveryConfirmation($orderNo,$pkid,$orderBalance,$targetSO,$shipmentDate,$remarks,$username,$actual_so_u) {
			$updated_variance = $targetSO - $actual_so_u;
			$query1 = "UPDATE `tbl_DeliveryUpdateRecord` SET `Variance` = '$updated_variance' WHERE `pkid` = '$pkid';";
			$this->query($query1);
			$query = 'UPDATE `tbl_DeliveryConfirmation` SET `OrderBalance`="'.$orderBalance.'",`TargetSO`="'.$targetSO.'",`ShipmentDate`="'.$shipmentDate.'",`LastUpdate`="'.date('Y-m-d H:i:s').'",`Username`="'.$username.'",`Remarks`="'.$remarks.'" WHERE `OrderNo`="'.$orderNo.'" AND logdel=0 AND `id`="'.$pkid.'";';
			return $this->query($query);
		}
		
		public function exportShipmentRecord($keyword, $startDate, $endDate, $orderNo) { //export on Delivery Confirmation
			
			if($keyword == 'date') {
				$query = 'SELECT a.DateIssued,a.Category,a.ProductPONo,a.POBalance,c.Variance,a.ItemCode,a.ItemName,a.OrderNo,a.Price,b.ShipmentDate,b.Remarks, SUM(c.ActualSO) AS ActualSO FROM tbl_POReceived a INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo INNER JOIN tbl_DeliveryUpdateRecord c ON c.pkid = b.id WHERE a.logdel=0 AND b.logdel=0 AND b.ShipmentDate BETWEEN "'.$startDate.'" AND "'.$endDate.'" GROUP BY OrderNo ORDER BY b.ShipmentDate DESC';
			} else if($keyword == 'orderno') {
				$query = 'SELECT a.DateIssued,a.Category,a.ProductPONo,a.POBalance,c.Variance,a.ItemCode,a.ItemName,a.OrderNo,a.Price,b.ShipmentDate,b.Remarks, SUM(c.ActualSO) AS ActualSO FROM tbl_POReceived a INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo INNER JOIN tbl_DeliveryUpdateRecord c ON c.pkid = b.id WHERE a.logdel=0 AND b.logdel=0 AND a.OrderNo="'.$orderNo.'" GROUP BY OrderNo ORDER BY b.ShipmentDate DESC';
			} else if($keyword == 'all') {
				$query = 'SELECT a.DateIssued,a.Category,a.ProductPONo,a.POBalance,c.Variance,a.ItemCode,a.ItemName,a.OrderNo,a.Price,b.ShipmentDate,b.Remarks, SUM(c.ActualSO) AS ActualSO FROM tbl_POReceived a INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo INNER JOIN tbl_DeliveryUpdateRecord c ON c.pkid = b.id WHERE a.logdel=0 AND b.logdel=0 GROUP BY OrderNo ORDER BY b.ShipmentDate DESC';
			}
			return $this->query($query);
		}
		
		public function getOrderNumberShipment($keyword) {
			if($keyword == 'internal') {	//load internal shipment order number 
				//$query = "SELECT OrderNo FROM tbl_POReceived WHERE OrderNo LIKE '%PR%' OR OrderNo LIKE '%AD%' OR OrderNo LIKE '%SM%' AND  logdel=0 ORDER BY OrderNo ASC";
				$query = "SELECT a.OrderNo FROM tbl_POReceived a INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo INNER JOIN tbl_DeliveryUpdateRecord c ON c.pkid = b.id WHERE a.logdel=0 AND b.logdel=0 AND a.OrderNo LIKE '%PR%' OR a.OrderNo LIKE '%AD%' OR a.OrderNo LIKE '%SM%' GROUP BY a.OrderNo ORDER BY a.OrderNo ASC";
			} else if($keyword == 'external') {	//load external shipment order number 
				//$query = "SELECT OrderNo FROM tbl_POReceived WHERE OrderNo NOT LIKE '%PR%' AND OrderNo NOT LIKE '%AD%' AND OrderNo NOT LIKE '%SM%' AND  logdel=0 ORDER BY OrderNo ASC";
				$query = "SELECT a.OrderNo FROM tbl_POReceived a INNER JOIN tbl_DeliveryConfirmation b ON a.OrderNo = b.OrderNo INNER JOIN tbl_DeliveryUpdateRecord c ON c.pkid = b.id 
				WHERE a.OrderNo NOT LIKE '%PR%' AND a.OrderNo NOT LIKE '%AD%' AND a.OrderNo NOT LIKE '%SM%' AND b.logdel=0 AND a.logdel=0 GROUP BY a.OrderNo ORDER BY OrderNo ASC";
			}
			return $this->query($query);
		}
		
		public function addNewRecordShipmentInvoice($controlNumber,$dateIssued,$revNo,$soldTo,$shippedBy,$total,$username) {	//insert to tbl_ShipmentInvoice 
			$checker  = $this->query("SELECT ControlNumber FROM tbl_ShipmentInvoice WHERE ControlNumber='".$controlNumber."' AND logdel=0;");
			if($checker->num_rows == 0) {
				$query = 'INSERT INTO `tbl_ShipmentInvoice`(`ControlNumber`, `DateCreated`, `RevNo`, `SoldTo`, `ShippedBy`, `GrandTotal`, `LastUpdate`, `Username`, `logdel`) 
				VALUES ("'.$controlNumber.'","'.$dateIssued.'","'.$revNo.'","'.$soldTo.'","'.$shippedBy.'","'.$total.'","'.date('Y-m-d H:i:s').'","'.$username.'",0)';
				$this->query($query);
				$msg = 'ok';
			} else {
				$msg = 'Control Number already exists!';
			}
			return $msg;
		}
		
		public function addNewRecordShipment($controlNumber,$dateIssued,$orderNo,$shipOut,$unitPrice,$amount,$remarks,$username) {	//insert to tbl_Shipment 
			$query = 'INSERT INTO `tbl_Shipment`(`fkControlNumber`, `DateIssued`, `OrderNo`, `ShipoutQty`, `UnitPrice`, `Amount`, `Remarks`, `LastUpdate`, `Username`, `logdel`) VALUES 
			("'.$controlNumber.'","'.$dateIssued.'","'.$orderNo.'","'.$shipOut.'","'.$unitPrice.'","'.$amount.'","'.$remarks.'","'.date('Y-m-d H:i:s').'","'.$username.'","0")';
			return $this->query($query);
		}
		
		public function getShipmentControlNumberList() {
			$query = 'SELECT ControlNumber FROM tbl_ShipmentInvoice WHERE logdel=0';
			return $this->query($query);
		}
		
		public function getShipmentRecord($keyword, $startDate, $endDate, $controlNumber) {
			if($keyword == 'date') {
				$query = 'SELECT DISTINCT(a.ControlNumber), a.DateCreated,a.RevNo,a.SoldTo,a.ShippedBy,a.GrandTotal FROM `tbl_ShipmentInvoice` a INNER JOIN tbl_Shipment b ON a.ControlNumber = b.fkControlNumber WHERE a.logdel=0 AND b.DateIssued BETWEEN "'.$startDate.'" AND "'.$endDate.'"';
			} else if($keyword == 'controlnumber') {
				$query = 'SELECT DISTINCT(a.ControlNumber), a.DateCreated,a.RevNo,a.SoldTo,a.ShippedBy,a.GrandTotal FROM `tbl_ShipmentInvoice` a WHERE a.ControlNumber="'.$controlNumber.'" AND a.logdel=0';
			} else if($keyword == 'all'){
				$query = 'SELECT a.ControlNumber, a.DateCreated,a.RevNo,a.SoldTo,a.ShippedBy,a.GrandTotal FROM `tbl_ShipmentInvoice` a WHERE a.logdel=0';
			} else if($keyword == 'transaction') {
				$query = 'SELECT b.Category,b.ProductPONo,b.ItemCode,b.ItemName,a.OrderNo,a.ShipOutQty,a.UnitPrice,a.Amount,a.Remarks FROM tbl_Shipment a INNER JOIN tbl_POReceived b ON 
				a.OrderNo = b.OrderNo WHERE a.logdel=0 AND a.fkControlNumber="'.$controlNumber.'"';
			} else if($keyword == 'date_excel') {
				$query = "SELECT po.Category, po.ItemCode,po.ItemName,shipment.DateIssued,shipment.OrderNo,invoice.ControlNumber,po.ProductPONo,shipment.ShipoutQty,shipment.UnitPrice,shipment.Amount FROM tbl_Shipment shipment INNER JOIN tbl_POReceived po ON shipment.OrderNo = po.OrderNo INNER JOIN tbl_ShipmentInvoice invoice ON invoice.ControlNumber = shipment.fkControlNumber WHERE shipment.DateIssued BETWEEN '$startDate' AND '$endDate'";
			}
			return $this->query($query);
		}
		
		public function getControlNumber() {
			$query = "SELECT MAX(ControlNumber) AS ControlNumber FROM tbl_ShipmentInvoice WHERE logdel=0";
			return $this->query($query);
		}
		
		//internal invoice code///////////////////////////////
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
		
		public function checkExistMaterial($Material){
			$query = $this->query("SELECT * FROM tbl_Warehouse WHERE MaterialType = '$Material'");
			if($query->num_rows == 0){
				return "Not Existing";
			}else{
				return "Existing";
			}
		}
		
		public function getPPSWarehouseDataByMaterial(){
			$query = "SELECT * FROM tbl_Warehouse";
			return $this->query($query);
		}
		
		//get the user
		public function getEmployeeInfo($username) {
			$query = $this->query("SELECT a.name FROM db_rapid.tbl_useraccounts a WHERE a.username='$username'");
			if($row = mysqli_fetch_array($query)) {
				return $row['name'];
			}
		}
		
		public function loadSearchItem($Category){
			$query = "SELECT * FROM tbl_dieset";
			return $this->query($query);
		}
		
		public function updateDiesetPartCode($TableName,$DeviceName,$R3Code){
			$checkExist = "SELECT * FROM `".$TableName."` WHERE `R3Code` = '$R3Code'";
			if($checkExist->num_rows == 0){
				$query = $this->query("UPDATE `".$TableName."` SET `R3Code` = '$R3Code' WHERE `DeviceName` = '$DeviceName'");
				$query = "UPDATE `".$TableName."` SET `R3Code` = '$R3Code', `Username` = '".$_SESSION['username']."' WHERE `DeviceName` = '$DeviceName'";
				return "R3 Code Updated!";
			}else{
				return "Partscode already exist!";
			}		
		}
		
		public function loadEmployeeNames() {
			$query = "SELECT CONCAT(firstname,' ',lastname) AS empname FROM db_rapid.tbl_employeeInfo";
			return $this->query($query);
		}
		
		public function getNameByUserName($username) {
			$query = $this->query("SELECT name FROM db_rapid.tbl_useraccounts WHERE username='$username'");
			return $query;
		}
		
		public function getPositionByName($name) {
			$query = "SELECT Position FROM db_rapid.tbl_employeeInfo WHERE CONCAT(firstname,' ', lastname)  = '$name';";
			return $this->query($query);
		}
		
		//Dieset tool life
		public function get_dieset_tool_life_end() {
			$query = "SELECT *,((`ToolShot`/`ToolLifePMI`)*100) AS tool_life_used FROM tbl_dieset;";
			return $this->query($query);
		}
		
		public function get_all_po_with_delivery() {
			$query = "";
			return $this->query($query);
		}
		
		public function update_shipment_by_batch($control_number,$order_no,$quantity,$up_new,$amount_new){
			$query = $this->query("SELECT * FROM `tbl_Shipment` WHERE `fkControlNumber` = '$control_number' AND `OrderNo` = '$order_no';");
			if($query->num_rows == 0){
				return "1";
			}else{
				$update_query = $this->query("UPDATE `tbl_Shipment` SET `ShipoutQty` = '$quantity',  `UnitPrice` = '$up_new', `Amount` = '$amount_new' WHERE `fkControlNumber` = '$control_number' AND `OrderNo` = '$order_no';");
				return "2";
			}
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
