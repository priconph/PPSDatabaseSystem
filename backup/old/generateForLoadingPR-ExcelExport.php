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

	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "FOR LOADING"; //set title cont.
	$objSheet->setTitle($title); //set title cont.
	
		//BORDERS STYLE
	//set all borders corner to thin 
	$styleArray = array(
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
	);
	//WORD WRAP
	$objPHPExcel->getActiveSheet()->getStyle('A2:Z999')
    ->getAlignment()->setWrapText(true);
	
	
	$objSheet->mergeCells('A1:N1');
	$objSheet->mergeCells('A2:N2');
	
	$objSheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
	$objSheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
	$objSheet->getCell('A1')->setValue('Pricon Microelectronics, Inc.');
	$objSheet->getCell('A2')->setValue('PR Information as of '.$dateToday);
	//row here
	$objSheet->getStyle('A3:N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A3:N3')->getFont()->setBold(true)->setSize(10);
	$objSheet->getCell('A3')->setValue('P.O. No');
	$objSheet->getCell('B3')->setValue('Parts Code');
	$objSheet->getCell('C3')->setValue('Device Name');
	$objSheet->getCell('D3')->setValue('Drawing No.');
	$objSheet->getCell('E3')->setValue('Revision No.');
	$objSheet->getCell('F3')->setValue('Material Type');
	$objSheet->getCell('G3')->setValue('PO Balance');
	$objSheet->getCell('H3')->setValue('WIP');
	$objSheet->getCell('I3')->setValue('FGS');
	$objSheet->getCell('J3')->setValue('TOTAL');
	$objSheet->getCell('K3')->setValue("For Prod'n (For Loading)");
	$objSheet->getCell('L3')->setValue('Shot Weight');
	$objSheet->getCell('M3')->setValue('Cav. No.');
	$objSheet->getCell('N3')->setValue('Cycle Time');
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(14); 
	$objSheet->getColumnDimension('B')->setWidth(9); 
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(15);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(8);
	$objSheet->getColumnDimension('H')->setWidth(8);
	$objSheet->getColumnDimension('I')->setWidth(8);
	$objSheet->getColumnDimension('J')->setWidth(10);
	$objSheet->getColumnDimension('K')->setWidth(9);
	$objSheet->getColumnDimension('L')->setWidth(6);
	$objSheet->getColumnDimension('M')->setWidth(6);
	$objSheet->getColumnDimension('N')->setWidth(6);
		
	//SET HEIGHT
	$objPHPExcel->getActiveSheet()
    ->getRowDimension('2')
    ->setRowHeight(23);
		
	//Values here
	//1st Table
	$DeviceFGSList = array();
	$ForPR = 0;
	$MaterialNameOld = "";
	$CellCount = "4";
	$CellCountStart = $CellCount - 1;
	$result = PPSDBS::getInstance()->MaterialsForLoading();
	while($row = mysqli_fetch_array($result)){
		if(is_null($row['wip'])){ 
			$row['wip'] = 0;
		}
		if(is_null($row['fgs'])){ 
			$row['fgs'] = 0;
		}
		$forLoading = $row["POBalance"] - ($row['wip'] + $row['fgs']);
		$shotWgt = number_format($row['ShotWgt'], 4, '.', '');
		//begin adding value to the cell
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$CellCount, $row["OrderNo"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$CellCount, $row["ItemCode"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$CellCount, $row["DeviceName"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$CellCount, $row["DrawingNo"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$CellCount, $row["Rev"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$CellCount, $row["Material"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$CellCount, $row["POBalance"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$CellCount, $row["wip"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$CellCount, $row["fgs"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('J'.$CellCount, ($row["wip"] + $row["fgs"]),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('K'.$CellCount, $forLoading,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('L'.$CellCount, $shotWgt,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('M'.$CellCount, $row['CavNo'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('N'.$CellCount, $row['CycleShot'],PHPExcel_Cell_DataType::TYPE_STRING);
		$CellCount++;
	}
	$CellCountEnd = $CellCount - 1;
	$objSheet->getStyle('A'.$CellCountStart.':N'.$CellCountEnd)->applyFromArray($styleArray);
	//for pr table
	$CellCount++;
	$CellCountStart = $CellCount;
	$objSheet->getStyle('A'.$CellCount.':N'.$CellCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A'.$CellCount.':N'.$CellCount)->getFont()->setBold(true)->setSize(10);
	$CellCount++;
		
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=For Loading.xls');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>