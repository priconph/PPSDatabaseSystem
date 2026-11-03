<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
		{
			$username = $_SESSION['username'];
			//$username = 'arlbena';
		}
	$To = $_GET['To'];
	$Attn = $_GET['Attn'];
	$Cc = $_GET['Cc'];
	$Date = $_GET['Date'];
	$ControlNumber = $_GET['ControlNumber'];
	$dateToday = date("M. d, Y");
	$dateFileName = date("Y-m-d");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "Dieset Quotation"; //set title cont.
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
	);
	//WORD WRAP
	$objPHPExcel->getActiveSheet()->getStyle('A5:Z999')
    ->getAlignment()->setWrapText(true);
	
	//SET WIDTH
	
	$objSheet->getColumnDimension('A')->setWidth(5);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(20);
	$objSheet->getColumnDimension('E')->setWidth(9);
	$objSheet->getColumnDimension('F')->setWidth(9);
	$objSheet->getColumnDimension('G')->setWidth(9);
	$objSheet->getColumnDimension('H')->setWidth(9);
	$objSheet->getColumnDimension('I')->setWidth(9);
	$objSheet->getColumnDimension('J')->setWidth(9);
	$objSheet->getColumnDimension('K')->setWidth(9);
	$objSheet->getColumnDimension('L')->setWidth(9);
	$objSheet->getColumnDimension('M')->setWidth(9);
	$objSheet->getColumnDimension('N')->setWidth(9);
	$objSheet->getColumnDimension('O')->setWidth(9);
	$objSheet->getColumnDimension('P')->setWidth(10);
	$objSheet->getColumnDimension('Q')->setWidth(12);

	
	$objSheet->mergeCells('A5:B5'); 
	
	//SET ALL VALUES
	$objSheet->getCell('A1')->setValue('To');
	$objSheet->getCell('A2')->setValue('Attn');
	$objSheet->getCell('A3')->setValue('Cc');
	$objSheet->getCell('A4')->setValue('Date');
	$objSheet->getCell('B1')->setValue(':'.$To);
	$objSheet->getCell('B2')->setValue(':'.$Attn);
	$objSheet->getCell('B3')->setValue(':'.$Cc);
	$objSheet->getCell('B4')->setValue(':'.$Date);
	$objSheet->getCell('A5')->setValue($ControlNumber);
	
	$objSheet->getStyle('E6:Q6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objSheet->getStyle('A6:Q6')->getFont()->setBold(true)->setSize(8);
	
	$objSheet->getCell('A6')->setValue('Item #');
	$objSheet->getCell('B6')->setValue('Device');
	$objSheet->getCell('C6')->setValue('Material Name');
	$objSheet->getCell('D6')->setValue('Partscode');
	$objSheet->getCell('E6')->setValue('Resin Cost');
	$objSheet->getCell('F6')->setValue('# of Cavity');
	$objSheet->getCell('G6')->setValue('Product Wt.(g)');
	$objSheet->getCell('H6')->setValue('Sprue Wt.(g)');
	$objSheet->getCell('I6')->setValue('Shot Wt.(g)');
	$objSheet->getCell('J6')->setValue('Labor Cost/hr($)');
	$objSheet->getCell('K6')->setValue('Cycle Time');
	$objSheet->getCell('L6')->setValue('Process Cost');
	$objSheet->getCell('M6')->setValue('Material Cost(per piece)');
	$objSheet->getCell('N6')->setValue('2nd Process Time(secs)');
	$objSheet->getCell('O6')->setValue('2nd Process Cost');
	$objSheet->getCell('P6')->setValue('Profit');
	$objSheet->getCell('Q6')->setValue('Molding Product Quotation($)');
	
	
	$objSheet->getStyle('A7:D999')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objSheet->getStyle('E7:T999')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('E7:T999')->getFont()->setBold(false)->setSize(8);
	
	$result = PPSDBS::getInstance()->ExportToExcelGenerateQuotation($ControlNumber);
	$ItemNo = 1;
	$count = 7;
	
	while($row = mysqli_fetch_array($result)){
		$objSheet->getCell('P5')->setValue('Rev:'.$row['RevNo']);
		//$objPHPExcel->getActiveSheet()
		//->setCellValueExplicit('A'.$count, $row['ProductCode'],PHPExcel_Cell_DataType::TYPE_STRING); //used for text
		$objSheet->getCell('A'.$count)->setValue($ItemNo); 
		$objSheet->getCell('B'.$count)->setValue($row['DeviceName']); 
		$objSheet->getCell('C'.$count)->setValue($row['Material']); 
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$count, $row['R3Code'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('E'.$count)->setValue($row['ResinCost']); 
		$objSheet->getCell('F'.$count)->setValue($row['NoOfCav']); 
		$objSheet->getCell('G'.$count)->setValue($row['ProductWgt']); 
		$objSheet->getCell('H'.$count)->setValue($row['SprueWgt']); 
		$objSheet->getCell('I'.$count)->setValue($row['ShotWgt']); 
		$objSheet->getCell('J'.$count)->setValue($row['LaborCost']); 
		$objSheet->getCell('K'.$count)->setValue($row['CycleTime']); 
		$objSheet->getCell('L'.$count)->setValue($row['ProcessCost']); 
		$objSheet->getCell('M'.$count)->setValue($row['MaterialCost']); 
		$objSheet->getCell('N'.$count)->setValue($row['SecondProcessTime']); 
		$objSheet->getCell('O'.$count)->setValue($row['SecondProcessCost']); 
		$objSheet->getCell('P'.$count)->setValue($row['Profit']); 
		$objSheet->getCell('Q'.$count)->setValue($row['MoldingProdQuot']); 
		$count++;
		$ItemNo++;
	}
	

	//set borders
	$endCount = $count;
	$count = 6;
	while ($count < $endCount){
		$objSheet->getStyle('A'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('B'.$count)->applyFromArray($styleArray3); 
		$objSheet->getStyle('C'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('D'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('E'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('F'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('G'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('H'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('I'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('J'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('K'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('L'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('M'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('N'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('O'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('P'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('Q'.$count)->applyFromArray($styleArray3);
		$count++;
	}
	
	
	
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=DiesetQuotation'.$dateFileName.'.xls');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>