<?php 
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
	{
		$username = $_SESSION['username'];
		//$username = 'arlbena';
	}
	$controlNumber = $_GET['ControlNumber'];
	$dateToday = date("M. d, Y");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial Unicode MS');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = date('m.d'); //set title cont.
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
	
	$default_border = array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
	);

	$styleArray3 = array(
	  'borders' => array(
		'bottom' => $default_border
	  )
	);
	
	$dottedBorder = array(
		'style' => PHPExcel_Style_Border::BORDER_DOTTED
	);

	//WORD WRAP
	//$objPHPExcel->getActiveSheet()->getStyle('A1:Z999')
   // ->getAlignment()->setWrapText(true);
	
	//set merge cell
	$objSheet->mergeCells('A3:G3');
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(10);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(50);
	$objSheet->getColumnDimension('D')->setWidth(20);
	$objSheet->getColumnDimension('E')->setWidth(20);
	$objSheet->getColumnDimension('F')->setWidth(20);
	$objSheet->getColumnDimension('G')->setWidth(20);
			
	$objSheet->getStyle('A1:G999')->applyFromArray($dottedBorder);
		
	$objSheet->getStyle('A1:G10')->getFont()->setBold(true)->setSize(16);
	//$objSheet->getStyle('B5:C7')->getFont()->setBold(true)->setSize(16);
	
	//value generated from database
	$transactionInfo = PPSDBS::getInstance()->getInternalInvoiceInfo($controlNumber);
	if($rowTransaction = mysqli_fetch_array($transactionInfo)) {
		$shippedBy = $rowTransaction['ShippedBy'];
		$soldTo = $rowTransaction['SoldTo'];
		$date = $rowTransaction['Date'];
		$revNo = $rowTransaction['RevNo'];
	}
	
	//set value
	$objSheet->getCell('A1')->setValue('PRICON MICROELECTRONICS, INC'); 
	$objSheet->getCell('A3')->setValue('INTERNAL INVOICE');
	$objSheet->getCell('F5')->setValue('Control No.:'); 
	$objSheet->getCell('F6')->setValue('Date:'); 
	$objSheet->getCell('F7')->setValue('Rev No:'); 
	$objSheet->getCell('A8')->setValue('Sold To: '); 
	$objSheet->getCell('E8')->setValue('Shipped By:'); 
	$objSheet->getCell('F9')->setValue($shippedBy); 
	$objSheet->getCell('B9')->setValue($soldTo);
	$objSheet->getCell('G5')->setValue($controlNumber); 
	$objSheet->getCell('G6')->setValue($date); 
	$objSheet->getCell('G7')->setValue($revNo); 
	$objSheet->getCell('A11')->setValue('ITEM #'); 
	$objSheet->getCell('B11')->setValue('PRODUCT CODE'); 
	$objSheet->getCell('C11')->setValue('DESCRIPTION'); 
	$objSheet->getCell('D11')->setValue('PO NUMBER'); 
	$objSheet->getCell('E11')->setValue('QUANTITY'); 
	$objSheet->getCell('F11')->setValue('U/PRICE'); 
	$objSheet->getCell('G11')->setValue('TOTAL'); 
	$colno = 14;
	$qty = 0;
	$total = 0;
	$grandTotal = 0;
	$counter =1;
	$getAllInternalInvoiceTransaction = PPSDBS::getInstance()->getInternalInvoiceItemTransaction($controlNumber);
	while($row = mysqli_fetch_array($getAllInternalInvoiceTransaction)) {
		$qty = $qty + $row['Quantity'];
		$grandTotal = $grandTotal + $row['Total'];
		$objSheet->getCell('A' . (string) $colno)->setValue($counter);
		$objSheet->getCell('B' . (string) $colno)->setValue($row['ProductCode']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['Description']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['PONumber']);
		$objSheet->getCell('E' . (string) $colno)->setValue($row['Quantity'] . " pcs");
		$objSheet->getCell('F' . (string) $colno)->setValue("$ " . $row['UnitPrice']);
		$objSheet->getCell('G' . (string) $colno)->setValue("$ " . $row['Total']);
		$colno++;
		$counter++;
	}
	$objSheet->getCell('A' . (string) ($colno))->setValue('Total');
	$objSheet->getCell('E' . (string) ($colno))->setValue($qty . " pcs");
	$objSheet->getCell('G' . (string) ($colno))->setValue("$ " . $grandTotal);
	$objSheet->mergeCells('A' . (string) ($colno) . ':C' . (string) ($colno));
	$objSheet->getStyle('A' . (string) ($colno) . ':C' . (string) ($colno))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objSheet->getStyle('A11:G11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('B12:B' . (string) $colno )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objSheet->getStyle('E12:G' . (string) $colno )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objSheet->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objSheet->getCell('B' . (string) ($colno + 2))->setValue('Prepared By');
	$objSheet->getCell('E' . (string) ($colno + 2))->setValue('Checked By');
	$objSheet->getCell('B' . (string) ($colno + 3))->setValue(' ');
	$objSheet->getCell('E' . (string) ($colno + 3))->setValue(' ');
	$objSheet->getCell('B' . (string) ($colno + 4))->setValue('Marie De Guzman');
	$objSheet->getCell('E' . (string) ($colno + 4))->setValue('Lyn Sunga');
	//set style
	$objSheet->getStyle('A11:G' . (string) $colno )->applyFromArray($styleArray);
	$objSheet->getStyle('F9')->applyFromArray($styleArray3);
	$objSheet->getStyle('B9')->applyFromArray($styleArray3);
	$objSheet->getStyle('G5')->applyFromArray($styleArray3); 
	$objSheet->getStyle('G6')->applyFromArray($styleArray3);
	$objSheet->getStyle('G7')->applyFromArray($styleArray3);
	$objSheet->getStyle('B7' . (string) ($colno + 3))->applyFromArray($styleArray3);
	$objSheet->getStyle('E7' . (string) ($colno + 3))->applyFromArray($styleArray3);
	
	unset($styleArray3);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>