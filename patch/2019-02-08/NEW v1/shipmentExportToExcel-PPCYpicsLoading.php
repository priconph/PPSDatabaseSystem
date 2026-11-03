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
	$objSheet->getColumnDimension('A')->setWidth(15);
	$objSheet->getColumnDimension('B')->setWidth(10);
	$objSheet->getColumnDimension('C')->setWidth(15);
	$objSheet->getColumnDimension('D')->setWidth(15);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(10);
	$objSheet->getColumnDimension('G')->setWidth(10);
	$objSheet->getColumnDimension('H')->setWidth(10);
	$objSheet->getColumnDimension('I')->setWidth(20);
	$objSheet->getColumnDimension('J')->setWidth(30);
	
	$objSheet->getCell('A1')->setValue('PORDER'); 
	$objSheet->getCell('B1')->setValue('AKUBU'); 
	$objSheet->getCell('C1')->setValue('JITU'); 
	$objSheet->getCell('D1')->setValue('CODE'); 
	$objSheet->getCell('E1')->setValue('FDATE'); 
	$objSheet->getCell('F1')->setValue('APRICE'); 
	$objSheet->getCell('G1')->setValue('HOKAN'); 
	$objSheet->getCell('H1')->setValue('VENDOR'); 
	$objSheet->getCell('I1')->setValue('INVOICE_NUM'); 
	$objSheet->getCell('J1')->setValue('NOTE'); 
	$objSheet->getCell('K1')->setValue('REMARKS'); 
	
	$colno = 2;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecordYPICS($startDate, $endDate, $startTime, $endTime, $division);
	$script = PPSDBS::getInstance()->getShipmentRecordYPICS_script($startDate, $endDate, $startTime, $endTime, $division);
	while($row = mysqli_fetch_array($getTransaction)) {
		$grandTotalQuantity = $grandTotalQuantity + $row['ShipoutQty'];
		$amount = $row['ShipoutQty']  * $row['UnitPrice'];
		
		$objSheet->getCell('A' . (string) $colno)->setValue($row['OrderNo']);
		$objSheet->getCell('B' . (string) $colno)->setValue('J');
		$objSheet->getCell('C' . (string) $colno)->setValue($row['ShipoutQty']);
		$objSheet->setCellValueExplicit('D' . (string) $colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('E' . (string) $colno)->setValue(date('Ymd',strtotime($row['DateIssued'])));
		$objSheet->getStyle('F'.$colno)->getNumberFormat()->setFormatCode('#,##0.0000');		
		$objSheet->getCell('F' . (string) $colno)->setValue($row['UnitPrice']);
		$objSheet->getCell('G' . (string) $colno)->setValue('WHS100');
		$objSheet->getCell('H' . (string) $colno)->setValue('PPD');
		$objSheet->getCell('I' . (string) $colno)->setValue($row['ControlNumber']);
		$objSheet->getCell('J' . (string) $colno)->setValue($row['PreshipCtrlNo']);
		$objSheet->getCell('K' . (string) $colno)->setValue($row['Remarks']);
		$colno++;
		
		/* Update export logs */
		$userlogs = 'Exported by: '.$username.' '.date('Y-m-d H:i:s').' From :'.$_GET['startDate'].' '.$_GET['startTime'].' To :'.$_GET['endDate'].' '.$_GET['endTime'].' for '.$division;
		$update_export_logs = PPSDBS::getInstance()->update_export_logs($row['id'], $userlogs);
	}
	
	// $objSheet->getCell('J1')->setValue($script); 
	$file_date = date('ymd');
	unset($styleArray);
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	/* header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xlsx'); */
	header('Content-Disposition: attachment;filename=TXSLIPJITU_PPS- '.$file_date.'.xls');
	header('Cache-Control: max-age=0');
	
	/* $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); */
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>