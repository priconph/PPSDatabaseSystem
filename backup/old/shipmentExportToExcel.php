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
	$objSheet->mergeCells('A3:J3');
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(8);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(20);
	$objSheet->getColumnDimension('E')->setWidth(40);
	$objSheet->getColumnDimension('F')->setWidth(20);
	$objSheet->getColumnDimension('G')->setWidth(15);
	$objSheet->getColumnDimension('H')->setWidth(15);
	$objSheet->getColumnDimension('I')->setWidth(15);
	$objSheet->getColumnDimension('J')->setWidth(20);
			
	$objSheet->getStyle('A1:J999')->applyFromArray($dottedBorder);
		
	$objSheet->getStyle('A1:J11')->getFont()->setBold(true)->setSize(16);
	//$objSheet->getStyle('B5:C7')->getFont()->setBold(true)->setSize(16);
	
	//value generated from database
	$transactionInfo = PPSDBS::getInstance()->getShipmentRecord('controlnumber','','',$controlNumber);
	if($rowTransaction = mysqli_fetch_array($transactionInfo)) {
		$shippedBy = $rowTransaction['ShippedBy'];
		$soldTo = $rowTransaction['SoldTo'];
		$date = $rowTransaction['DateCreated'];
		$revNo = $rowTransaction['RevNo'];
	}
	
	//set value
	$objSheet->getCell('A1')->setValue('PRICON MICROELECTRONICS, INC'); 
	$objSheet->getCell('A3')->setValue('INTERNAL INVOICE');
	$objSheet->getCell('I5')->setValue('Control No.:'); 
	$objSheet->getCell('I6')->setValue('Date:'); 
	$objSheet->getCell('I7')->setValue('Rev No:'); 
	$objSheet->getCell('A8')->setValue('Sold To: '); 
	$objSheet->getCell('H8')->setValue('Shipped By:'); 
	$objSheet->getCell('I9')->setValue($shippedBy); 
	$objSheet->getCell('B9')->setValue($soldTo);
	$objSheet->getCell('J5')->setValue($controlNumber); 
	$objSheet->getCell('J6')->setValue($date); 
	$objSheet->getCell('J7')->setValue($revNo); 
	$objSheet->getCell('A11')->setValue('ITEM #'); 
	$objSheet->getCell('B11')->setValue('CATEGORY'); 
	$objSheet->getCell('C11')->setValue('PRODUCT PO #'); 
	$objSheet->getCell('D11')->setValue('ITEM CODE'); 
	$objSheet->getCell('E11')->setValue('ITEM NAME'); 
	$objSheet->getCell('F11')->setValue('ORDER NO'); 
	$objSheet->getCell('G11')->setValue('SHIP OUT QTY'); 
	$objSheet->getCell('H11')->setValue('UNIT PRICE'); 
	$objSheet->getCell('I11')->setValue('AMOUNT'); 
	$objSheet->getCell('J11')->setValue('REMARKS');
	$colno = 12;
	$qty = 0;
	$total = 0;
	$grandTotal = 0;
	$counter =1;
	$getAllInternalInvoiceTransaction = PPSDBS::getInstance()->getShipmentRecord('transaction','','',$controlNumber);
	while($row = mysqli_fetch_array($getAllInternalInvoiceTransaction)) {
		$qty = $qty + $row['ShipOutQty'];
		$grandTotal = $grandTotal + $row['Amount'];

		$objSheet->getCell('A' . (string) $colno)->setValue($counter);
		$objSheet->getCell('B' . (string) $colno)->setValue($row['Category']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['ProductPONo']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['ItemCode']);
		$objSheet->getCell('E' . (string) $colno)->setValue($row['ItemName']);
		$objSheet->getCell('F' . (string) $colno)->setValue($row['OrderNo']);
		$objSheet->getCell('G' . (string) $colno)->setValue($row['ShipOutQty']);
		$objSheet->getCell('H' . (string) $colno)->setValue($row['UnitPrice']);
		$objSheet->getCell('I' . (string) $colno)->setValue("$".$row['Amount']);
		$objSheet->getCell('J' . (string) $colno)->setValue($row['Remarks']);
		$colno++;
		$counter++;
	}
	$objSheet->getCell('A' . (string) ($colno))->setValue('Total');
	$objSheet->getCell('G' . (string) ($colno))->setValue($qty . " pcs");
	$objSheet->getCell('I' . (string) ($colno))->setValue("$ " . $grandTotal);
	$objSheet->mergeCells('A' . (string) ($colno) . ':F' . (string) ($colno));
	$objSheet->getStyle('A' . (string) ($colno) . ':F' . (string) ($colno))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objSheet->getStyle('A11:J11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('B12:B' . (string) $colno )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objSheet->getStyle('E12:G' . (string) $colno )->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objSheet->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objSheet->getCell('C' . (string) ($colno + 2))->setValue('Prepared By');
	$objSheet->getCell('H' . (string) ($colno + 2))->setValue('Checked By');
	$objSheet->getCell('C' . (string) ($colno + 3))->setValue(' ');
	$objSheet->getCell('H' . (string) ($colno + 3))->setValue(' ');
	$objSheet->getCell('C' . (string) ($colno + 4))->setValue('Marie De Guzman');
	$objSheet->getCell('H' . (string) ($colno + 4))->setValue('Lyn Sunga');
	//set style
	$objSheet->getStyle('A11:J' . (string) $colno )->applyFromArray($styleArray);
	$objSheet->getStyle('I9')->applyFromArray($styleArray3);
	$objSheet->getStyle('B9')->applyFromArray($styleArray3);
	$objSheet->getStyle('J5')->applyFromArray($styleArray3); 
	$objSheet->getStyle('J6')->applyFromArray($styleArray3);
	$objSheet->getStyle('J7')->applyFromArray($styleArray3);
	$objSheet->getStyle('B7' . (string) ($colno + 3))->applyFromArray($styleArray3);
	$objSheet->getStyle('E7' . (string) ($colno + 3))->applyFromArray($styleArray3);
	
	unset($styleArray3);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xls');
	header('Cache-Control: max-age=0');
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2005');
	$objWriter->save('php://output');
?>