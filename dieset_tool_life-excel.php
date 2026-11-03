<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
		{
			$username = $_SESSION['username'];
			//$username = 'marasd';
		}
	$ControlNumber = $_GET['ControlNumber'];
	$dateToday = date("M. d, Y");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "SMPO Production Schedule"; //set title cont.
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
	$objSheet->getColumnDimension('A')->setWidth(12);
	$objSheet->getColumnDimension('B')->setWidth(15);
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(15);
	$objSheet->getColumnDimension('E')->setWidth(7);
	$objSheet->getColumnDimension('F')->setWidth(7);
	$objSheet->getColumnDimension('G')->setWidth(15);
	$objSheet->getColumnDimension('H')->setWidth(15);
	$objSheet->getColumnDimension('I')->setWidth(15);
	
	
	$objSheet->mergeCells('D1:H1'); 
	$objSheet->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('D1')->getFont()->setBold(true)->setSize(16);
	$objSheet->mergeCells('A2:D2'); 
	$objSheet->mergeCells('M2:P2'); 
	//$objSheet->mergeCells('H4:N4'); 
	$objSheet->mergeCells('O4:R4'); 
	
	$objSheet->getCell('D1')->setValue('DIE-SET TOOL LIFE');
	//$objSheet->getCell('A2')->setValue('Date Prepared: '.$dateToday);
	//$objSheet->getCell('M2')->setValue('Control No.: '.$ControlNumber); //set later
	
	//$objSheet->getCell('A2')->setValue('Date Prepared: ');
	$objSheet->getStyle('A4:S5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getCell('A4')->setValue('Rack No.');
	$objSheet->getCell('B4')->setValue('R3Code.');
	$objSheet->getCell('C4')->setValue('Device Name');
	$objSheet->getCell('D4')->setValue('Drawing No.');
	$objSheet->getCell('E4')->setValue('Rev');
	$objSheet->getCell('F4')->setValue('Material');
	$objSheet->getCell('G4')->setValue('Tool Shot');
	$objSheet->getCell('H4')->setValue('Tool Life in PMI');
	$objSheet->getCell('I4')->setValue('Remarks');
	
	$result = ppsdbs::getInstance()->get_dieset_tool_life_end($ControlNumber);
	$objSheet->getStyle('A6:T999')->getFont()->setBold(false)->setSize(8);
	
	$count = 5;
	while($row = mysqli_fetch_array($result)){
		//$objSheet->getCell('A'.$count)->setValue($row['ProductCode']); 
		if($row['tool_life_used'] >= 80){
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('A'.$count, $row['RackNum'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('B'.$count, $row['R3Code'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('C'.$count, $row['DeviceName'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('D'.$count, $row['DrawingNo'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('E'.$count, $row['Rev'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('F'.$count, $row['Material'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('G'.$count, $row['ToolShot'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('H'.$count, $row['ToolLifePMI'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('I'.$count, $row['Remarks'],PHPExcel_Cell_DataType::TYPE_STRING);
			$count++;
		}
	}
	//set borders
	$endCount = $count;
	$count = 4;
	while ($count < $endCount+1){
		$objSheet->getStyle('A'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('B'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('C'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('D'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('E'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('F'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('G'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('H'.$count)->applyFromArray($styleArray3);
		$objSheet->getStyle('I'.$count)->applyFromArray($styleArray3);
		$count++;
	}
		
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=Dieset Tool Life.xls');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>