<?PHP
  require_once("./ppsdbs-oop.php");
  // Original PHP code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.
	//$date_start_export = $_GET["date_start"];
	//$date_end_export = $_GET["date_end"];
	$date_start_export = "1970-01-01";
	$date_end_export = "2100-01-12";
	
	$data = array();
  	$exportNoRecordDeliveryConfirmation = PPSDBS::getInstance()->exportNoRecordDeliveryConfirmation($date_start_export,$date_end_export);
	while($row = mysqli_fetch_array($exportNoRecordDeliveryConfirmation)) {
		$current_data =  array("DateIssued" => $row['DateIssued'], "Category" => $row['Category'], "ProductPONo" => $row['ProductPONo'], "ItemCode" => $row['ItemCode'], "ItemName" => $row['ItemName'], "OrderNo" => $row['OrderNo'], "POBalance" => $row['POBalance'], "TargetForSO" => "", "ShipmentDate" => "", "Remarks" => "");
		array_push($data,$current_data);
	}
	$exportRecordsDeliveryConfirmation = PPSDBS::getInstance()->exportRecordsDeliveryConfirmation($date_start_export,$date_end_export);
	while($row = mysqli_fetch_array($exportRecordsDeliveryConfirmation)) {
		$current_data =  array("DateIssued" => $row['DateIssued'], "Category" => $row['Category'], "ProductPONo" => $row['ProductPONo'], "ItemCode" => $row['ItemCode'], "ItemName" => $row['ItemName'], "OrderNo" => $row['OrderNo'], "POBalance" => $row['POBalance'], "TargetForSO" => $row['TargetSO'], "ShipmentDate" => $row['ShipmentDate'], "Remarks" => $row['Remarks']);
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