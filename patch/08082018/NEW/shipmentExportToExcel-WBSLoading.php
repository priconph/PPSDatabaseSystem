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
	$startTime 	= $_GET['startTime'];
	$endTime 	= $_GET['endTime'];
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
	
	$colno = 2;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecordYPICS($startDate, $endDate, $startTime, $endTime);
	while($row = mysqli_fetch_array($getTransaction)) {
		$grandTotalQuantity = $grandTotalQuantity + $row['ShipoutQty'];
		$amount = $row['ShipoutQty']  * $row['UnitPrice'];
		
		$objSheet->getCell('A' . (string) $colno)->setValue('');
		$objSheet->getCell('B' . (string) $colno)->setValue($row['ControlNumber']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['ItemName']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['ShipoutQty']);
		$objSheet->getCell('E' . (string) $colno)->setValue('Tray');
		$objSheet->getCell('F' . (string) $colno)->setValue(1);
		$objSheet->setCellValueExplicit('G' . (string) $colno, $row['LotNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('H' . (string) $colno)->setValue('PPS');
		$objSheet->getCell('I' . (string) $colno)->setValue(1);
		$objSheet->getCell('J' . (string) $colno)->setValue($row['DateIssued']);
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
?>