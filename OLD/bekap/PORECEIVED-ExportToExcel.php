<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
		//$username = 'arlbena';
	}
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "Delivery Confirmation"; //set title cont.
	$objSheet->setTitle($title); //set title cont.
	
	//BORDERS STYLE
	//set all borders corner to thin 
	$styleArray = array(
	  'borders' => array(
		'allborders' => array(
		  'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	  ),
	  'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'FFFFF'),
		)
	); 

	//WORD WRAP
	$objPHPExcel->getActiveSheet()->getStyle('A1:Z999')
    ->getAlignment()->setWrapText(true);
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(10);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(15);
	$objSheet->getColumnDimension('D')->setWidth(20);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(12);
	$objSheet->getColumnDimension('H')->setWidth(12);
	$objSheet->getColumnDimension('I')->setWidth(12);
	//set style		
	$objSheet->getStyle('A1')->applyFromArray($styleArray);
	$objSheet->getStyle('B1')->applyFromArray($styleArray);
	$objSheet->getStyle('C1')->applyFromArray($styleArray);
	$objSheet->getStyle('D1')->applyFromArray($styleArray);
	$objSheet->getStyle('E1')->applyFromArray($styleArray);
	$objSheet->getStyle('F1')->applyFromArray($styleArray);
	$objSheet->getStyle('G1')->applyFromArray($styleArray);
	$objSheet->getStyle('H1')->applyFromArray($styleArray);
	$objSheet->getStyle('I1')->applyFromArray($styleArray);
		
	$objSheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(10);
	//format 2
	$objSheet->getCell('A1')->setValue('Date Issued'); 
	$objSheet->getCell('B1')->setValue('Category'); 
	$objSheet->getCell('C1')->setValue('Product P.O. No'); 
	$objSheet->getCell('D1')->setValue("Item Code"); 
	$objSheet->getCell('E1')->setValue('Item Name'); 
	$objSheet->getCell('F1')->setValue('Order No.'); 
	$objSheet->getCell('G1')->setValue('Order. Balance'); 
	$objSheet->getCell('H1')->setValue('Target for S/O'); 
	$objSheet->getCell('I1')->setValue('Shipment Date'); 
	
	$colno = 2;
	$exportNoRecordDeliveryConfirmation = PPSDBS::getInstance()->exportNoRecordDeliveryConfirmation();
	while($row = mysqli_fetch_array($exportNoRecordDeliveryConfirmation)) {
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$colno, $row['Category'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$colno, $row['ProductPONo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$colno, $row['ItemName'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$colno, $row['OrderNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$colno, $row['POBalance'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$colno, '',PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, '',PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('A'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('I'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['ShipmentDate'],PHPExcel_Cell_DataType::TYPE_STRING);
		$colno++;
		$objSheet->getStyle('A2:I'.($colno-1))->applyFromArray($styleArray);
	}
	
	$exportRecordsDeliveryConfirmation = PPSDBS::getInstance()->exportRecordsDeliveryConfirmation();
	while($row = mysqli_fetch_array($exportRecordsDeliveryConfirmation)) {
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$colno, $row['Category'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$colno, $row['ProductPONo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$colno, $row['ItemName'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$colno, $row['OrderNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$colno, $row['POBalance'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$colno, $row['TargetSO'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['ShipmentDate'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('A'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('I'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['ShipmentDate'],PHPExcel_Cell_DataType::TYPE_STRING);
		$colno++;
		$objSheet->getStyle('A2:I'.($colno-1))->applyFromArray($styleArray);
	}
	
	
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>