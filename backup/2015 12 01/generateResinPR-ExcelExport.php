<?php
	require('../media/PHPExcel.php');
	// Initiate cache
	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
	$cacheSettings = array( 'memoryCacheSize' => '32MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
	
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
	$title = "RESIN PR INFORMATION"; //set title cont.
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
	
	
	$objSheet->mergeCells('A1:G1');
	$objSheet->mergeCells('A2:G2');
	
	$objSheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
	$objSheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
	$objSheet->getCell('A1')->setValue('Pricon Microelectronics, Inc.');
	$objSheet->getCell('A2')->setValue('PR Information as of '.$dateToday);
	//row here
	$objSheet->getStyle('A3:L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A3:L3')->getFont()->setBold(true)->setSize(10);
	$objSheet->getCell('A3')->setValue('R3 Code');
	$objSheet->getCell('B3')->setValue('Drawing No');
	$objSheet->getCell('C3')->setValue('Device Name');
	$objSheet->getCell('D3')->setValue('Total PO Balance Qty');
	$objSheet->getCell('E3')->setValue('WIP'.' as of ');
	$objSheet->getCell('F3')->setValue('FGS');
	$objSheet->getCell('G3')->setValue('For Loading');
	$objSheet->getCell('H3')->setValue('Material');
	$objSheet->getCell('I3')->setValue('No of Cavity');
	$objSheet->getCell('J3')->setValue('Shot Weight');
	$objSheet->getCell('K3')->setValue('Kilograms Needed');
	$objSheet->getCell('L3')->setValue('Additional Kgs');
	
	//SET WIDTH
	$objSheet->getColumnDimension('A')->setWidth(30); 
	$objSheet->getColumnDimension('B')->setWidth(15); 
	$objSheet->getColumnDimension('C')->setWidth(30); 
	$objSheet->getColumnDimension('D')->setWidth(15);
	$objSheet->getColumnDimension('E')->setWidth(15);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(15);
	$objSheet->getColumnDimension('H')->setWidth(12);
	$objSheet->getColumnDimension('I')->setWidth(12);
	$objSheet->getColumnDimension('J')->setWidth(15);
	$objSheet->getColumnDimension('K')->setWidth(15);
	$objSheet->getColumnDimension('L')->setWidth(15);
		
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
	$result = PPSDBS::getInstance()->MaterialsForPR();
	while($row = mysqli_fetch_array($result)){
		if(is_null($row['EOH'])){ 
			$row['EOH'] = 0;
		}
		if(is_null($row['FGSQty'])){ 
			$row['FGSQty'] = 0;
		}
		$forLoading = $row["POBalance"] - ($row['FGSQty'] + $row['EOH']);
		$kgsNeeded = (($row["ShotWgt"] / 1000)*$forLoading);
		$kgsNeeded = number_format($kgsNeeded, 4, '.', '');
		$additionalKgs = (0.8*(180+(34*$row["ShotWgt"])))/1000;
		$additionalKgs = number_format($additionalKgs, 4, '.', '');
		$forPR = ($kgsNeeded+$additionalKgs)-$row["Balance"];
		$forPR = number_format($forPR, 4, '.', '');
		$DeviceFGSList[$row["DeviceName"]] = $row['FGSQty'];
		//begin adding value to the cell
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('A'.$CellCount, $row["R3Code"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('B'.$CellCount, $row["DrawingNo"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('C'.$CellCount, $row["DeviceName"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('D'.$CellCount, $row["POBalance"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('E'.$CellCount, $row["EOH"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('F'.$CellCount, $row["FGSQty"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('G'.$CellCount, $forLoading,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('H'.$CellCount, $row["Material"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('I'.$CellCount, $row["NoOfCav"],PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('J'.$CellCount, number_format($row["ShotWgt"], 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('K'.$CellCount, $kgsNeeded,PHPExcel_Cell_DataType::TYPE_STRING);
		$objPHPExcel->getActiveSheet()
		->setCellValueExplicit('L'.$CellCount, $additionalKgs,PHPExcel_Cell_DataType::TYPE_STRING);
		$CellCount++;
	}
	$CellCountEnd = $CellCount - 1;
	$objSheet->getStyle('A'.$CellCountStart.':L'.$CellCountEnd)->applyFromArray($styleArray);
	//for pr table
	$CellCount++;
	$CellCountStart = $CellCount;
	$objSheet->getStyle('A'.$CellCount.':K'.$CellCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A'.$CellCount.':K'.$CellCount)->getFont()->setBold(true)->setSize(10);
	$objSheet->getCell('A'.$CellCount)->setValue('Material');
	$objSheet->getCell('B'.$CellCount)->setValue('Total Kgs For PR');
	$objSheet->getCell('C'.$CellCount)->setValue('Additional Kgs');
	$objSheet->getCell('D'.$CellCount)->setValue('For PR');
	$CellCount++;
	
	$ForPR = 0;
	$KgsNeededArray = array();
	$AdditionalKgsNeededArray = array();
	$result = PPSDBS::getInstance()->MaterialsForPRByMaterialsbbb();
	while($row = mysqli_fetch_array($result)){
		//dapat ito kapag tapos na yung loop lang mag miminus :) pero dyan lang muna sya pansamantala
		$MaterialInfo = PPSDBS::getInstance()->getPPSWarehouseDataByMaterial($row["Material"]); 
		while ($row2 = mysqli_fetch_array($MaterialInfo)){
			$WarehouseBalance = $row2['Balance'];
		}
		if(is_null($row['EOH'])){ 
			$row['FGSQty'] = 0;
		}
		if(is_null($row['FGSQty'])){ 
			$row['FGSQty'] = 0;
		}
		$forLoading = $row["POBalance"] - ($row['FGSQty'] + $row['EOH']);
		$kgsNeeded = (($row["ShotWgt"] / 1000)*$forLoading);
		$kgsNeeded = number_format($kgsNeeded, 4, '.', '');
		$additionalKgs = (0.8*(180+(34*$row["ShotWgt"])))/1000;
		$additionalKgs = number_format($additionalKgs, 4, '.', '');
		$KgsNeededArray[$row['Material']] = $KgsNeededArray[$row['Material']] + $kgsNeeded;
		$AdditionalKgsNeededArray[$row['Material']] = $AdditionalKgsNeededArray[$row['Material']] + $additionalKgs;
	}
	$forPR = 0;
	$MaterialInfo = PPSDBS::getInstance()->getPPSWarehouseData(); 
	while ($row2 = mysqli_fetch_array($MaterialInfo)){
		if(isset($KgsNeededArray[$row2['MaterialType']])){
			$forPR = ($KgsNeededArray[$row2['MaterialType']] + $AdditionalKgsNeededArray[$row2['MaterialType']]) - $row2['Balance'];
		}else{
			$forPR = 0;
		}
		if($forPR > 0){
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('A'.$CellCount, $row2["MaterialType"],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('B'.$CellCount, number_format($KgsNeededArray[$row2['MaterialType']], 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
			$AdditionalKgsNeededArray[$row2['MaterialType']] = number_format($AdditionalKgsNeededArray[$row2['MaterialType']], 4, '.', '');
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('C'.$CellCount, number_format($AdditionalKgsNeededArray[$row2['MaterialType']], 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('D'.$CellCount, number_format($forPR, 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
			$CellCount++;
		}
	}
	
	//for non existent materials
	foreach(array_keys($KgsNeededArray) as $paramName){
		//echo $paramName . "<br>";
		$checkExistMaterial = PPSDBS::getInstance()->checkExistMaterial($paramName);
		if($checkExistMaterial == 'Not Existing'){
			$PRThis = $KgsNeededArray[$paramName] + $AdditionalKgsNeededArray[$paramName];
			if($PRThis > 0){
				$objPHPExcel->getActiveSheet()
				->setCellValueExplicit('A'.$CellCount, $paramName,PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()
				->setCellValueExplicit('B'.$CellCount, number_format($KgsNeededArray[$paramName], 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
				
				$objPHPExcel->getActiveSheet()
				->setCellValueExplicit('C'.$CellCount, number_format($AdditionalKgsNeededArray[$paramName], 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->getActiveSheet()
				->setCellValueExplicit('D'.$CellCount, number_format($PRThis, 4, '.', ''),PHPExcel_Cell_DataType::TYPE_STRING);
				$CellCount++;
			}
		}
	}
	$CellCountEnd = $CellCount - 1;
	$objSheet->getStyle('A'.$CellCountStart.':D'.$CellCountEnd)->applyFromArray($styleArray);
	
	$file_date = date('ymd');
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PR Information'.$file_date.'.xls');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>