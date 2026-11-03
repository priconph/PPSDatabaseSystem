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
	$title = "PO Received"; //set title cont.
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
	$objSheet->getColumnDimension('D')->setWidth(15);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(12);
	$objSheet->getColumnDimension('H')->setWidth(12);
	$objSheet->getColumnDimension('I')->setWidth(12);
	$objSheet->getColumnDimension('J')->setWidth(12);
	$objSheet->getColumnDimension('K')->setWidth(12);
	$objSheet->getColumnDimension('L')->setWidth(30);
	$objSheet->getColumnDimension('M')->setWidth(30);
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
	$objSheet->getStyle('J1')->applyFromArray($styleArray);
	$objSheet->getStyle('K1')->applyFromArray($styleArray);
	$objSheet->getStyle('L1')->applyFromArray($styleArray);
	$objSheet->getStyle('M1')->applyFromArray($styleArray);
		
	$objSheet->getStyle('A1:M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:M1')->getFont()->setBold(true)->setSize(10);
	//format 2
	$objSheet->getCell('A1')->setValue('Supplier'); 
	$objSheet->getCell('B1')->setValue('YEC P.O.'); 
	$objSheet->getCell('C1')->setValue("Item Code"); 
	$objSheet->getCell('D1')->setValue('Name'); 
	$objSheet->getCell('E1')->setValue('Order No.'); 
	$objSheet->getCell('F1')->setValue('Schd. Qty'); 
	$objSheet->getCell('G1')->setValue('Purchase U/Cost'); 
	$objSheet->getCell('H1')->setValue('Purc. Order Amount'); 
	$objSheet->getCell('I1')->setValue('Date Issued'); 
	$objSheet->getCell('J1')->setValue('PPD Reply'); 
	$objSheet->getCell('K1')->setValue('Date Needed');
	$objSheet->getCell('L1')->setValue('Remarks');
	$objSheet->getCell('M1')->setValue('Drawing No.');
	
	$colno = 2;
	$getAllPOReceivedRecord = PPSDBS::getInstance()->viewPOReceived('date','',$startDate, $endDate, '');
	while($row = mysqli_fetch_array($getAllPOReceivedRecord)) {
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['Supplier'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$colno, $row['YECPO'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$colno, $row['Name'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$colno, $row['OrderNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$colno, $row['SchdQty'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$colno, $row['PurchaseUnitCost'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$colno, $row['PrchaseOrderAmt'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['DateIssued'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('J'.$colno, $row['PPD Reply'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->getStyle('J'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('K'.$colno, $row['DateNeeded'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('L'.$colno, $row['Remarks'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('M'.$colno, $row['DrawingNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$colno++;
		//$objSheet->getStyle('A'.(string) . $colno . ':M' . (string) . $colno)->applyFromArray($styleArray);
		$objSheet->getStyle('A2:M'.($colno-1))->applyFromArray($styleArray);
	}
	
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>