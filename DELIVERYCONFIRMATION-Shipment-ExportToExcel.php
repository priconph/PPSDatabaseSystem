<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
		//$username = 'arlbena';
	}
	//get transfered value
	$startDate = $_GET['startdate'];
	$endDate = $_GET['enddate'];
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
	$objSheet->getColumnDimension('A')->setWidth(20);
	$objSheet->getColumnDimension('B')->setWidth(15);
	$objSheet->getColumnDimension('C')->setWidth(15);
	$objSheet->getColumnDimension('D')->setWidth(12);
	$objSheet->getColumnDimension('E')->setWidth(12);
	$objSheet->getColumnDimension('F')->setWidth(12);
	$objSheet->getColumnDimension('G')->setWidth(20);
	//set style		
	$objSheet->getStyle('A1')->applyFromArray($styleArray);
	$objSheet->getStyle('B1')->applyFromArray($styleArray);
	$objSheet->getStyle('C1')->applyFromArray($styleArray);
	$objSheet->getStyle('D1')->applyFromArray($styleArray);
	$objSheet->getStyle('E1')->applyFromArray($styleArray);
	$objSheet->getStyle('F1')->applyFromArray($styleArray);
	$objSheet->getStyle('G1')->applyFromArray($styleArray);
	//$objSheet->getStyle('H1')->applyFromArray($styleArray);
	//$objSheet->getStyle('I1')->applyFromArray($styleArray);
	//$objSheet->getStyle('H1')->applyFromArray($styleArray);
	//$objSheet->getStyle('I1')->applyFromArray($styleArray);
	//$objSheet->getStyle('J1')->applyFromArray($styleArray);
		
	$objSheet->getStyle('A1:G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(10);
	//format 2
	/*$objSheet->getCell('A1')->setValue('Date Issued'); 
	$objSheet->getCell('B1')->setValue('Category'); 
	$objSheet->getCell('C1')->setValue('Product P.O. No');
	$objSheet->getCell('D1')->setValue("Item Code");
	$objSheet->getCell('E1')->setValue('Item Name');
	$objSheet->getCell('F1')->setValue('Order No.');
	$objSheet->getCell('G1')->setValue('Shipout Qty');
	$objSheet->getCell('H1')->setValue('Unit Price');
	$objSheet->getCell('I1')->setValue('Amount');
	$objSheet->getCell('J1')->setValue('Remarks'); */
	
	$objSheet->getCell('A1')->setValue("Item Code");
	$objSheet->getCell('B1')->setValue('Item Name');
	$objSheet->getCell('C1')->setValue('Order No.');
	$objSheet->getCell('D1')->setValue('Shipout Qty');
	$objSheet->getCell('E1')->setValue('Unit Price');
	$objSheet->getCell('F1')->setValue('Amount');
	$objSheet->getCell('G1')->setValue('Remarks');
	
	$colno = 2;
	$getAllDeliveryConfirmationRecord = PPSDBS::getInstance()->exportShipmentRecord('date',$startDate, $endDate, '');
	while($row = mysqli_fetch_array($getAllDeliveryConfirmationRecord)) {
		$amount = number_format($row['ActualSO'] * $row['Price'],2,'.','');
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('B'.$colno, $row['Category'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('C'.$colno, $row['ProductPONo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$colno, $row['ItemName'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$colno, $row['OrderNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('D'.$colno, $row['POBalance'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$colno, $row['ActualSO'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$colno, $row['Price'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$colno, '$'.$amount,PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('H'.$colno, $row['Variance'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$colno, $row['Remarks'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objPHPExcel->getActiveSheet()
		//->getStyle('A'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('A'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$colno++;
		$objSheet->getStyle('A2:G'.($colno-1))->applyFromArray($styleArray);
	}
	
	$file_date = date('ymd');
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=Delivery Confirmation for Shipment '.$file_date.'.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>