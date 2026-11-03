<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
	}
	$employeeName = PPSDBS::getInstance()->getEmployeeInfo($username);
	//get transfered value
	// $startDate 	= "2018-08-06";
	// $endDate	= "2018-08-06";
	// $startTime 	= "06:00";
	// $endTime 	= "09:00";
	$startDate 	= $_GET['startDate'];
	$endDate 	= $_GET['endDate'];
	$startTime 	= $_GET['startTime'] . ':00';
	$endTime 	= $_GET['endTime'] . ':59';
	$division 	= $_GET['division'];
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(11);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "Sheet"; //set title cont.
	$objSheet->setTitle($title); //set title cont.
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(20);
	$objSheet->getColumnDimension('B')->setWidth(30);
	$objSheet->getColumnDimension('C')->setWidth(15);
	$objSheet->getColumnDimension('D')->setWidth(6);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(20);
	$objSheet->getColumnDimension('H')->setWidth(10);
	$objSheet->getColumnDimension('I')->setWidth(20);
	$objSheet->getColumnDimension('J')->setWidth(20);
	
	$objSheet->getCell('A1')->setValue('receivingno'); 
	$objSheet->getCell('B1')->setValue('invoiceno'); 
	$objSheet->getCell('C1')->setValue('item'); 
	$objSheet->getCell('D1')->setValue('Qty'); 
	$objSheet->getCell('E1')->setValue('package_category'); 
	$objSheet->getCell('F1')->setValue('package_qty'); 
	$objSheet->getCell('G1')->setValue('LotNo'); 
	$objSheet->getCell('H1')->setValue('supplier'); 
	$objSheet->getCell('I1')->setValue('not_required_iqc'); 
	$objSheet->getCell('J1')->setValue('received_date'); 
	$objSheet->getCell('K1')->setValue('REMARKS'); 
	$objSheet->getCell('L1')->setValue('Pre-ship Ctrl #'); 
	
	$colno = 2;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecordWBS($startDate, $endDate, $startTime, $endTime,$division);
	$script = PPSDBS::getInstance()->getShipmentRecordWBS_script($startDate, $endDate, $startTime, $endTime,$division);
	// $objSheet->getCell('A1')->setValue($script); 
	while($row = mysqli_fetch_array($getTransaction)) {
		$grandTotalQuantity = $grandTotalQuantity + $row['TotalShipoutQty'];
		$amount = $row['TotalShipoutQty']  * $row['UnitPrice'];
		
		$objSheet->getCell('A' . (string) $colno)->setValue('');
		$objSheet->getCell('B' . (string) $colno)->setValue($row['ControlNumber']);
		$objSheet->setCellValueExplicit('C' . (string) $colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['TotalShipoutQty']);
		$objSheet->getCell('E' . (string) $colno)->setValue(return_package_category($row['ControlNumber'],$row['OrderNo'],$row['ItemCode'],$row['LotNo']));
		$objSheet->getCell('F' . (string) $colno)->setValue(return_package_qty($row['ControlNumber'],$row['OrderNo'],$row['ItemCode'],$row['LotNo']));
		$objSheet->setCellValueExplicit('G' . (string) $colno, $row['LotNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('H' . (string) $colno)->setValue('PPS');
		$objSheet->getCell('I' . (string) $colno)->setValue(return_iqc_matix($row['ItemCode']));
		$objSheet->getCell('J' . (string) $colno)->setValue($row['DateIssued']);
		$objSheet->getCell('K' . (string) $colno)->setValue($row['Remarks']);
		$objSheet->getCell('L' . (string) $colno)->setValue($row['PreshipCtrlNo']);
		$colno++;
	}
	$file_date = date('ymd');
	unset($styleArray);
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	/* header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xlsx'); */
	header('Content-Disposition: attachment;filename=For load WBS- '.$file_date.'.xls');
	header('Cache-Control: max-age=0');
	
	/* $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); */
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	
	
	function return_package_category($ControlNumber, $PO,$Partscode,$LotNo) {
		require_once("ppsdbs-oop.php");
		$package_cat_query = PPSDBS::getInstance()->return_package_category($ControlNumber,$PO,$Partscode,$LotNo);
		if($row = mysqli_fetch_array($package_cat_query)) {
			return $row['PackageCategory'];
		} else {
			return 'N/A';
		}
	}
	function return_package_qty($ControlNumber,$PO,$Partscode,$LotNo) {
		require_once("ppsdbs-oop.php");
		$package_cat_query = PPSDBS::getInstance()->return_package_qty($ControlNumber,$PO,$Partscode,$LotNo);
		// return $package_cat_query = PPSDBS::getInstance()->return_package_qty($ControlNumber,$PO,$Partscode,$LotNo);
		if($row = mysqli_fetch_array($package_cat_query)) {
			return $row['PackageQty'];
		} else {
			return '0';
		}
	}
	function return_iqc_matix($Partscode) {
		require_once("ppsdbs-oop.php");
		$matrix_query = TS_SUBSYSTEM::getInstance()->return_iqc_matrix($Partscode);
		return $matrix_query;
	}
?>