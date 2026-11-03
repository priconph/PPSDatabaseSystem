<?php
	require('../media/PHPExcel.php');
	require_once("ppsdbs-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
		{
			$username = $_SESSION['username'];
			//$username = 'arlbena';
		}
	//$startDate = $_GET['sDate'];
	//$endDate = $_GET['eDate'];
	$yecControlNoDeviceName = str_replace("[]","#",$_GET['yecControlNoDeviceName']);
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "DIESET"; //set title cont.
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
	$objPHPExcel->getActiveSheet()->getStyle('A1:Z999')
    ->getAlignment()->setWrapText(true);
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(10);
	$objSheet->getColumnDimension('B')->setWidth(8);
	$objSheet->getColumnDimension('C')->setWidth(8);
	$objSheet->getColumnDimension('D')->setWidth(15);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(30);
	$objSheet->getColumnDimension('H')->setWidth(25);
	$objSheet->getColumnDimension('I')->setWidth(12);
	$objSheet->getColumnDimension('J')->setWidth(30);
	$objSheet->getColumnDimension('K')->setWidth(30);
	$objSheet->getColumnDimension('L')->setWidth(30);
	$objSheet->getColumnDimension('M')->setWidth(30);
	$objSheet->getColumnDimension('N')->setWidth(30);
	$objSheet->getColumnDimension('O')->setWidth(30);
	$objSheet->getColumnDimension('P')->setWidth(30);
	$objSheet->getColumnDimension('Q')->setWidth(30);
	$objSheet->getColumnDimension('R')->setWidth(30);
	$objSheet->getColumnDimension('S')->setWidth(30);
	$objSheet->getColumnDimension('T')->setWidth(30);
	$objSheet->getColumnDimension('U')->setWidth(30);
	$objSheet->getColumnDimension('V')->setWidth(30);
	$objSheet->getColumnDimension('W')->setWidth(30);
	$objSheet->getColumnDimension('X')->setWidth(30);
	$objSheet->getColumnDimension('Y')->setWidth(30);
	$objSheet->getColumnDimension('Z')->setWidth(30);
	$objSheet->getColumnDimension('AA')->setWidth(30);
	$objSheet->getColumnDimension('AB')->setWidth(30);
	$objSheet->getColumnDimension('AC')->setWidth(30);
	$objSheet->getColumnDimension('AD')->setWidth(30);
	$objSheet->getColumnDimension('AE')->setWidth(30);
	$objSheet->getColumnDimension('AF')->setWidth(30);
	$objSheet->getColumnDimension('AG')->setWidth(30);
			
		$objSheet->getStyle('A1')->applyFromArray($styleArray3);
		$objSheet->getStyle('B1')->applyFromArray($styleArray3);
		$objSheet->getStyle('C1')->applyFromArray($styleArray3);
		$objSheet->getStyle('D1')->applyFromArray($styleArray3);
		$objSheet->getStyle('E1')->applyFromArray($styleArray3);
		$objSheet->getStyle('F1')->applyFromArray($styleArray3);
		$objSheet->getStyle('G1')->applyFromArray($styleArray3);
		$objSheet->getStyle('H1')->applyFromArray($styleArray3);
		$objSheet->getStyle('I1')->applyFromArray($styleArray3);
		$objSheet->getStyle('J1')->applyFromArray($styleArray3);
		$objSheet->getStyle('K1')->applyFromArray($styleArray3);
		$objSheet->getStyle('L1')->applyFromArray($styleArray3);
		$objSheet->getStyle('M1')->applyFromArray($styleArray3);
		$objSheet->getStyle('N1')->applyFromArray($styleArray3);
		$objSheet->getStyle('O1')->applyFromArray($styleArray3);
		$objSheet->getStyle('P1')->applyFromArray($styleArray3);
		$objSheet->getStyle('Q1')->applyFromArray($styleArray3);
		$objSheet->getStyle('R1')->applyFromArray($styleArray3);
		$objSheet->getStyle('S1')->applyFromArray($styleArray3);
		$objSheet->getStyle('T1')->applyFromArray($styleArray3);
		$objSheet->getStyle('U1')->applyFromArray($styleArray3);
		$objSheet->getStyle('V1')->applyFromArray($styleArray3);
		$objSheet->getStyle('W1')->applyFromArray($styleArray3);
		$objSheet->getStyle('X1')->applyFromArray($styleArray3);
		$objSheet->getStyle('Y1')->applyFromArray($styleArray3);
		$objSheet->getStyle('Z1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AA1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AB1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AC1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AD1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AE1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AF1')->applyFromArray($styleArray3);
		$objSheet->getStyle('AG1')->applyFromArray($styleArray3);
		
	$objSheet->getStyle('A1:AG1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:AG1')->getFont()->setBold(true)->setSize(10);
	//format 2
	$objSheet->getCell('A1')->setValue('Date'); 
	$objSheet->getCell('B1')->setValue('Dieset Status'); 
	$objSheet->getCell('C1')->setValue("Date Modified"); 
	$objSheet->getCell('D1')->setValue("Date Returned"); 
	$objSheet->getCell('E1')->setValue("YEC Molding Prd'n Control No."); 
	$objSheet->getCell('F1')->setValue('R/3 Code'); 
	$objSheet->getCell('G1')->setValue('Exp Code'); 
	$objSheet->getCell('H1')->setValue('Device Name'); 
	$objSheet->getCell('I1')->setValue('Drawing Number'); 
	$objSheet->getCell('J1')->setValue('Rev'); 
	$objSheet->getCell('K1')->setValue('Mold Classification'); 
	$objSheet->getCell('L1')->setValue('Die No'); 
	$objSheet->getCell('M1')->setValue('Number of Cavity');
	$objSheet->getCell('N1')->setValue('Cav No');
	$objSheet->getCell('O1')->setValue('Changing Part(Have / None)');
	$objSheet->getCell('P1')->setValue('Rack Number/Location');
	$objSheet->getCell('Q1')->setValue('Dieset Size');
	$objSheet->getCell('R1')->setValue('Dieset Wight(kg)');
	$objSheet->getCell('S1')->setValue('Unit Weight');
	$objSheet->getCell('T1')->setValue('Sprue Weight');
	$objSheet->getCell('U1')->setValue('1Shot Weight');
	$objSheet->getCell('V1')->setValue('Cycle Time 1/Shot');
	$objSheet->getCell('W1')->setValue('Fixed Asset No');
	$objSheet->getCell('X1')->setValue('Date Manufactured');
	$objSheet->getCell('Y1')->setValue('Mold Make');
	$objSheet->getCell('Z1')->setValue('Tool Life');
	$objSheet->getCell('AA1')->setValue('Tool Shot');
	$objSheet->getCell('AB1')->setValue('Tool Life in PMI');
	$objSheet->getCell('AC1')->setValue('Maintenance Cycle');
	$objSheet->getCell('AD1')->setValue('Injection Machine');
	$objSheet->getCell('AE1')->setValue('Material');
	$objSheet->getCell('AF1')->setValue('Tone Color');
	$objSheet->getCell('AG1')->setValue('Remarks');
	
	$colno = 2;
	$rona = 0;
	//$getAllDiesetRecord = PPSDBS::getInstance()->viewDiesetByDateRange($startDate,$endDate);
	$getAllDiesetRecord = PPSDBS::getInstance()->getDiesetSearch($yecControlNoDeviceName);
	while($row = mysqli_fetch_array($getAllDiesetRecord)) {
		//1st format
		/*
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['YECMoldCtrlNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$colno, '-WAITING-',PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$colno, '-WAITING-',PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$colno, '-WAITING-',PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$colno, $row['R3Code'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$colno, $row['EXPCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$colno, $row['DeviceName'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$colno, $row['DrawingNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['Rev'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('J'.$colno, $row['Material'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('K'.$colno, $row['Remarks'],PHPExcel_Cell_DataType::TYPE_STRING);
		*/
		//2nd format
		$rona++;
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$colno, $row['Date'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$colno, $row['DiesetStatus'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$colno, $rona . $row['DateModified'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$colno, $row['DateReturned'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$colno, $row['YECMoldCtrlNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$colno, $row['R3Code'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$colno, $row['EXPCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$colno, $row['DeviceName'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$colno, $row['DrawingNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('J'.$colno, $row['Rev'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('K'.$colno, $row['MoldClass'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('L'.$colno, $row['DieNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('M'.$colno, $row['NoOfCav'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('N'.$colno, $row['CavNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('O'.$colno, $row['ChangingPart'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('P'.$colno, $row['RackNum'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('Q'.$colno, $row['DiesetSize'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('R'.$colno, $row['DiesetWgt'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('S'.$colno, $row['UnitWgt'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('T'.$colno, $row['SprueWgt'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('U'.$colno, $row['ShotWgt'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('V'.$colno, $row['CycleShot'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('W'.$colno, $row['FixedAssetNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('X'.$colno, $row['DateManufactured'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('Y'.$colno, $row['MoldMake'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('Z'.$colno, $row['ToolLife'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AA'.$colno, $row['ToolShot'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AB'.$colno, $row['ToolLifePMI'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AC'.$colno, $row['MaintenanceCycle'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AD'.$colno, $row['InjectionMachine'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AE'.$colno, $row['Material'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AF'.$colno, $row['ToneColor'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('AG'.$colno, $row['Remarks'],PHPExcel_Cell_DataType::TYPE_STRING);
		
		$objPHPExcel->getActiveSheet()
		->getStyle('C'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->getStyle('D'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		$objPHPExcel->getActiveSheet()
		->getStyle('X'.$colno)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
		//old format
		/*$objSheet->getCell('A' . (string) $colno)->setValue($row['YECMoldCtrlNo']);
		$objSheet->getCell('B' . (string) $colno)->setValue("-WAITING-");
		$objSheet->getCell('C' . (string) $colno)->setValue("-WAITING-");
		$objSheet->getCell('D' . (string) $colno)->setValue("-WAITING-");
		$objSheet->getCell('E' . (string) $colno)->setValue($row['R3Code']);
		$objSheet->getCell('F' . (string) $colno)->setValue($row['EXPCode']);
		$objSheet->getCell('G' . (string) $colno)->setValue($row['DeviceName']);
		$objSheet->getCell('H' . (string) $colno)->setValue($row['DrawingNo']);
		$objSheet->getCell('I' . (string) $colno)->setValue($row['Rev']);
		$objSheet->getCell('J' . (string) $colno)->setValue($row['Material']);
		$objSheet->getCell('K' . (string) $colno)->setValue($row['Remarks']);*/
		$colno++;
	}
	
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>