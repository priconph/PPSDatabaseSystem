<?PHP
  require_once("./ppsdbs-oop.php");
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
	//$date_start_export = $_GET["date_start"];
	//$date_end_export = $_GET["date_end"];
	
	$data = array();
  	$result = PPSDBS::getInstance()->export_dieset();
	while($row = mysqli_fetch_array($result)) {
		$current_data =  array("Date" => $row['Date'], "DiesetStatus" => $row['DiesetStatus'], "DateModified" => $row['DateModified'], "DateReturned" => $row['DateReturned'], "YECMoldCtrlNo" => $row['YECMoldCtrlNo'], "R3Code" => $row['R3Code'], "EXPCode" => $row['EXPCode'], "DeviceName" => $row['DeviceName'], "DrawingNo" => $row['DrawingNo']
		, "Rev" => $row['Rev'], "MoldClass" => $row['MoldClass'], "DieNo" => $row['DieNo'], "NoOfCav" => $row['NoOfCav'], "CavNo" => $row['CavNo'], "ChangingPart" => $row['ChangingPart'], "RackNum" => $row['RackNum'], "DiesetSize" => $row['DiesetSize'], "DiesetWgt" => $row['DiesetWgt'], "UnitWgt" => $row['UnitWgt'], "SprueWgt" => $row['SprueWgt'], "ShotWgt" => $row['ShotWgt'], "CycleShot" => $row['CycleShot'], "FixedAssetNo" => $row['FixedAssetNo'], "DateManufactured" => $row['DateManufactured']
		, "MoldMake" => $row['MoldMake'], "ToolLife" => $row['ToolLife'], "ToolShot" => $row['ToolShot'], "ToolLifePMI" => $row['ToolLifePMI'], "MaintenanceCycle" => $row['MaintenanceCycle'], "InjectionMachine" => $row['InjectionMachine'], "Material" => $row['Material'], "ToneColor" => $row['ToneColor'], "Remarks" => $row['Remarks']);
		array_push($data,$current_data);
	}
 
  function cleanData(&$str)
  {
    // escape tab characters
    $str = preg_replace("/\t/", "\\t", $str);

    // escape new lines
    $str = preg_replace("/\r?\n/", "\\n", $str);

    // convert 't' and 'f' to boolean values
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';

    // force certain number/date formats to be imported as strings
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
      $str = "'$str";
    }

    // escape fields that include double quotes
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }

  // file name for download
  $filename = "po_balance_" . date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\n";
  }

  exit;
?>