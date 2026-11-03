<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php"); 
	session_start();
	if (array_key_exists("username", $_SESSION))
		{
			$username = $_SESSION['username'];
			//$username = 'arlbena';
		}
	
	//gagawin mo pa lang to
	$dateToday = date("Y-m-d H:ia");
	
	$date_start_export = $_GET["date_start"];
	$date_end_export = $_GET["date_end"];

	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "PO RECEIVE"; //set title cont.
	$objSheet->setTitle($title); //set title cont.
	
		//BORDERS STYLE
	//set all borders corner to thin 
	/* $styleArray = array(
	  'borders' => array(
		'allborders' => array(
		  'style' => PHPExcel_Style_Border::BORDER_THIN
		)
	  )
	); 
	
	$styleArray2 = array(
	  'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb'=>'FFFFF'),
		)
	);
	
	$default_border = array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
	);

	$styleArray3 = array(
	  'borders' => array(
		'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
	  )
	);
	
	$styleArray4 = array(
	  'borders' => array(
		'bottom' => $default_border
	  )
	); */
	//WORD WRAP
	$objPHPExcel->getActiveSheet()->getStyle('A2:Z999')
    ->getAlignment()->setWrapText(true);
	

	$objSheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:J1')->getFont()->setBold(true)->setSize(10);
	//format 2
	$objSheet->getCell('A1')->setValue('Date Issued'); 
	$objSheet->getCell('B1')->setValue('Category'); 
	$objSheet->getCell('C1')->setValue('Product P.O. No'); 
	$objSheet->getCell('D1')->setValue("Item Code"); 
	$objSheet->getCell('E1')->setValue('Item Name'); 
	$objSheet->getCell('F1')->setValue('Order No.'); 
	$objSheet->getCell('G1')->setValue('Order Balance'); 
	$objSheet->getCell('H1')->setValue('Target for S/O'); 
	$objSheet->getCell('I1')->setValue('Shipment Date'); 
	$objSheet->getCell('J1')->setValue('Remarks'); 
	
	$colno = 2;
	//$exportNoRecordDeliveryConfirmation = PPSDBS::getInstance()->MaterialsForLoading();
	$exportNoRecordDeliveryConfirmation = PPSDBS::getInstance()->MaterialsForLoading($date_start_export,$date_end_export);
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
		->setCellValueExplicit('J'.$colno, '',PHPExcel_Cell_DataType::TYPE_STRING);
		/* $objPHPExcel->getActiveSheet()
		->getStyle('A'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('I'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['ShipmentDate'],PHPExcel_Cell_DataType::TYPE_STRING); 
		$colno++;
		$objSheet->getStyle('A2:J'.($colno))->applyFromArray($styleArray); */
	}
	
	/* $exportRecordsDeliveryConfirmation = PPSDBS::getInstance()->exportRecordsDeliveryConfirmation($date_start_export,$date_end_export);
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
		->setCellValueExplicit('J'.$colno, $row['Remarks'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('A'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('I'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['ShipmentDate'],PHPExcel_Cell_DataType::TYPE_STRING);
		$colno++;
		$objSheet->getStyle('A2:J'.($colno-1))->applyFromArray($styleArray);
	} */
	
	$file_date = date('ymd');
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PO Received'.$file_date.'.xls');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>