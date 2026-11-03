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
		'bottom' => $default_border,
        'left' => $default_border,
        'top' => $default_border,
        'right' => $default_border,
	  )
	);
	
	$dottedBorder = array(
		'style' => PHPExcel_Style_Border::BORDER_DOTTED
	);

	//WORD WRAP
	$objPHPExcel->getActiveSheet()->getStyle('A1:Z999')
    ->getAlignment()->setWrapText(true);
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(28);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(50);
	$objSheet->getColumnDimension('D')->setWidth(17);
	$objSheet->getColumnDimension('E')->setWidth(29);
	$objSheet->getColumnDimension('F')->setWidth(38);
			
	$objSheet->getStyle('A1:F999')->applyFromArray($dottedBorder);
		
	$objSheet->getStyle('A5:Z999')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:F7')->getFont()->setBold(true)->setSize(16);
	//$objSheet->getStyle('B5:C7')->getFont()->setBold(true)->setSize(16);
	
	//value generated from database
	$transactionInfo = PPSDBS::getInstance()->generateExcelReport($controlNumber);
	if($rowTransaction = mysqli_fetch_array($transactionInfo)) {
		$customer = "CUSTOMER: " . $rowTransaction['Customer'];
		$date = Date('l, F d,Y ',strtotime($rowTransaction['Date']));
	}
	
	$objSheet->getCell('A1')->setValue('TS - MOLDING'); 
	$objSheet->getCell('A2')->setValue('Shipment Confirmation Sheet'); 
	$objSheet->getCell('A3')->setValue($date); 
	$objSheet->getCell('A5')->setValue($customer); 
	$objSheet->getCell('B5')->setValue('DEC. SALES'); 
	$objSheet->getCell('A7')->setValue('PR No.'); 
	$objSheet->getCell('B7')->setValue('Parts Code'); 
	$objSheet->getCell('C7')->setValue('Parts Name'); 
	$objSheet->getCell('D7')->setValue('Actual'); 
	$objSheet->getCell('F7')->setValue('Total'); 
	$colno = 8;
	$qty = 0;
	$total = 0;
	$grandTotal = 0;
	$getAllShipmentTransaction = PPSDBS::getInstance()->generateExcelTransactionReport($controlNumber);
	while($row = mysqli_fetch_array($getAllShipmentTransaction)) {
		$qty = $qty + $row['Quantity'];
		$total = $total + $row['Total'];
		$grandTotal = $grandTotal + $row['Total'];
		$objSheet->getCell('A' . (string) $colno)->setValue($row['PRNo']);
		$objSheet->getCell('B' . (string) $colno)->setValue($row['Partscode']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['Partsname']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['Quantity']);
		$objSheet->getCell('E' . (string) $colno)->setValue($row['Price']);
		$objSheet->getCell('F' . (string) $colno)->setValue($row['Total']);
		$colno++;
	}
	$objSheet->getCell('D' . (string) ($colno))->setValue($qty);
	$objSheet->getStyle('D' . (string) $colno)->getFont()->setBold(true)->setSize(16);
	$objSheet->getCell('F' . (string) ($colno))->setValue($total);
	$objSheet->getStyle('F' . (string) $colno)->getFont()->setBold(true)->setSize(16);
	$objSheet->getCell('F' . (string) ($colno))->setValue($grandTotal);
	$objSheet->getStyle('F' . (string) $colno+1)->getFont()->setBold(true)->setSize(16);
	unset($styleArray3);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>