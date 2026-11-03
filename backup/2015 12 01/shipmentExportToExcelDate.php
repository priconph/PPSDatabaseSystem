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
	//$startDate = "2014-11-30";
	$endDate = $_GET['endDate'];
	//$endDate = "2014-12-12";
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(8);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "SHIPMENT"; //set title cont.
	$objSheet->setTitle($title); //set title cont.
	$objSheet->mergeCells('A1:H1');
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
        'color' => array('rgb'=>'FFFFFF'),
		)
	); 

	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(30);
	$objSheet->getColumnDimension('B')->setWidth(11);
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(20);
	$objSheet->getColumnDimension('E')->setWidth(20);
	$objSheet->getColumnDimension('F')->setWidth(10);
	$objSheet->getColumnDimension('G')->setWidth(10);
	$objSheet->getColumnDimension('H')->setWidth(15);
	$objSheet->getColumnDimension('I')->setWidth(15);
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
		
	$objSheet->getStyle('A2:I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(12);
	$objSheet->getStyle('A1:I2')->getFont()->setBold(true)->setSize(8.5);
	
	$objSheet->getCell('A1')->setValue('INVOICE SALES SUMMARY'); 
	$objSheet->getCell('A2')->setValue('Item Name'); 
	$objSheet->getCell('B2')->setValue('Date Issued');
	$objSheet->getCell('C2')->setValue('Order No'); 
	$objSheet->getCell('D2')->setValue('Control Number'); 
	$objSheet->getCell('E2')->setValue('Product Code'); 
	$objSheet->getCell('F2')->setValue('Product PO No'); 
	$objSheet->getCell('G2')->setValue('Quantity'); 
	$objSheet->getCell('H2')->setValue('U/P'); 
	$objSheet->getCell('I2')->setValue('Amount'); 
	
	$colno = 3;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecord('date_excel',$startDate, $endDate, '');
	while($row = mysqli_fetch_array($getTransaction)) {
		$grandTotalQuantity = $grandTotalQuantity + $row['ShipoutQty'];
		$grandTotalAmount = $grandTotalAmount + $row['Amount'];

		$objSheet->getCell('A' . (string) $colno)->setValue($row['ItemName']);
		$objSheet->getCell('B' . (string) $colno)->setValue($row['DateIssued']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['OrderNo']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['ControlNumber']);
		//$objSheet->getCell('E' . (string) $colno)->setValue($row['ItemCode']);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E' . (string) $colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		//$objSheet->getCell('F' . (string) $colno)->setValue($row['ProductPONo']);
		if($row['Category'] == "GRINDING-SPECIAL CASE"){
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('F' . (string) $colno, $row['Category'].'('.$row['ProductPONo'].')',PHPExcel_Cell_DataType::TYPE_STRING);
		}else{
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('F' . (string) $colno, $row['ProductPONo'],PHPExcel_Cell_DataType::TYPE_STRING);
		}
		$objSheet->getCell('G' . (string) $colno)->setValue($row['ShipoutQty']);
		
		$objPHPExcel->getActiveSheet()
		->getStyle('G'.$colno)
		->getNumberFormat()
		->setFormatCode('#,##0.00');
		
		$objSheet->getCell('H' . (string) $colno)->setValue($row['UnitPrice']);
		
		$objSheet->getCell('I' . (string) $colno)->setValue($row['Amount']);
		$objPHPExcel->getActiveSheet()
		->getStyle('I'.$colno)
		->getNumberFormat()
		->setFormatCode('#,##0.00');
		
		$colno++;
		$objSheet->getStyle('A2:I'.($colno-1))->applyFromArray($styleArray);
	}
	
	$objSheet->getCell('E' . (string) ($colno))->setValue('GrandTotal');
	$objSheet->getCell('G' . (string) ($colno))->setValue($grandTotalQuantity);
	$objSheet->getCell('I' . (string) ($colno))->setValue($grandTotalAmount);
	$objPHPExcel->getActiveSheet()
		->getStyle('I'.$colno)
		->getNumberFormat()
		->setFormatCode('#,##0.00');
		
	$objSheet->getStyle('A' . (string) ($colno) . ':F' . (string) ($colno))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	//WORD WRAP
	$objPHPExcel->getActiveSheet()->getStyle('A1:H'.(string)($colno))
    ->getAlignment()->setWrapText(true);
	$objSheet->getCell('A' . (string) ($colno + 2))->setValue('Prepared By');
	$objSheet->getCell('A' . (string) ($colno + 4))->setValue('Marie De Guzman');
	$objSheet->getCell('G' . (string) ($colno + 9))->setValue('Exported by: '.$employeeName);
	$objSheet->getCell('B' . (string) ($colno + 2))->setValue('Checked By');
	$objSheet->getCell('B' . (string) ($colno + 4))->setValue('Ms. Cris Saylon/Mr. Jojo Patos');
	$objSheet->getCell('B' . (string) ($colno + 5))->setValue('PPC Sr. Planner/Asst. Manager');
	$objSheet->getCell('E' . (string) ($colno + 2))->setValue('Approved by:');
	$objSheet->getCell('E' . (string) ($colno + 4))->setValue('Lyn Sunga');
	$objSheet->getCell('E' . (string) ($colno + 5))->setValue('PPC Gen. Manager');
	$objSheet->getStyle('A'. (string) ($colno) .':H'.(string) ($colno + 5))->getFont()->setBold(true);
	$file_date = date('ymd');
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xlsx');
	header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xls');
	header('Cache-Control: max-age=0');
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>