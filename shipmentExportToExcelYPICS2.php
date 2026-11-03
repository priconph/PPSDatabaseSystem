<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
		//$username = 'arlbena';
	}
	$employeeName = PPSDBS::getInstance()->getEmployeeInfo($username);
	//get transfered value
	$startDate = $_GET['startDate'];
	$endDate = $_GET['endDate'];
	
	// $startDate = '2016-06-01';
	// $endDate = '2016-06-01';
	
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "SHIPMENT YPICS ".date('Y-m-d'); //set title cont.
	$objSheet->setTitle($title); //set title cont.
	
	$objSheet->getCell('A1')->setValue('OrderNo.'); 
	$objSheet->getCell('B1')->setValue('Act.QtyCorresp.Schd.');
	$objSheet->getCell('C1')->setValue('Act.QtyCorresp.Inv.'); 
	$objSheet->getCell('D1')->setValue('Act.Pur.U/Cost');
	$objSheet->getCell('E1')->setValue('Invoice No.');
	$objSheet->getCell('F1')->setValue('Revised Invoice No.'); 
	$objSheet->getCell('G1')->setValue('Act.Class'); 
	$objSheet->getCell('H1')->setValue('FinishDate'); 
	$objSheet->getCell('I1')->setValue('Putaway location'); 
	$objSheet->getCell('J1')->setValue('Remarks');
	
	$colno = 2;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecord('date_excel_ypics',$startDate, $endDate, '');
	while($row = mysqli_fetch_array($getTransaction)) {
		$objSheet->setCellValueExplicit('A' . (string) $colno, $row['OrderNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('B' . (string) $colno)->setValue($row['TargetQty']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['ShipoutQty']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['UnitPrice']);
		$objSheet->getStyle('D'.$colno)->getNumberFormat()->setFormatCode('#,##0.0000');
		$objSheet->getCell('E' . (string) $colno)->setValue($row['ControlNumber']);
		$objSheet->getCell('F' . (string) $colno)->setValue('');
		$objSheet->getCell('G' . (string) $colno)->setValue('J');
		$objSheet->getCell('H' . (string) $colno)->setValue($row['DateIssued']);
		$objSheet->getCell('I' . (string) $colno)->setValue('WHS100');
		$objSheet->getCell('J' . (string) $colno)->setValue($row['PreshipCtrlNo']);
		$colno++;
	}
	unset($styleArray);
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xlsx');
	header('Content-Disposition: attachment;filename='.$title.'.xls');
	header('Cache-Control: max-age=0');
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>