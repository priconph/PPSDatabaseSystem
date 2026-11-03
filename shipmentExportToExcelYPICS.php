<?php
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	require_once("ppsdbs-oop.php");
	
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
		
	}
	$username = 'marlope';
	$employeeName = PPSDBS::getInstance()->getEmployeeInfo($username);
	//get transfered value
	/* $startDate = $_GET['startDate'];
	$endDate = $_GET['endDate']; */
	$startDate = '2016-06-01';
	$endDate = '2016-06-01';
	
	$data = array();
	$headers = array('OrderNo.','Act.QtyCorresp.Schd.','Act.QtyCorresp.Inv.','Act.Pur.U/Cost','Invoice No.','Revised Invoice No.','Act.Class','FinishDate','Putaway location','Remarks');
	$data[] = $headers;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecord('date_excel_ypics',$startDate, $endDate, '');
	while( $row = mysqli_fetch_assoc($getTransaction) ) {
		$data[] = $row;
		// echo $row['0'];
	 }
	 // filename for download
	  $filename = "PPS Shipment For YPICS" . date('Ymd') . ".xls";

	  header("Content-Disposition: attachment; filename=\"$filename\"");
	  header("Content-Type: application/vnd.ms-excel");
	  // header("Content-Type: text/plain");
  
	  foreach($data as $row) {
		array_walk($row, __NAMESPACE__ . '\cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
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

		//force certain number/date formats to be imported as strings
		/* if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
		  $str = "'$str";
		} */
		
		// escape fields that include double quotes
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	  }
	  exit;
?>