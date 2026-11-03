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
	$title = "Delivery Confirmation"; //set title cont.
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
	$objSheet->getColumnDimension('A')->setWidth(20);
	$objSheet->getColumnDimension('B')->setWidth(15);
	$objSheet->getColumnDimension('C')->setWidth(15);
	$objSheet->getColumnDimension('D')->setWidth(12);
	$objSheet->getColumnDimension('E')->setWidth(12);
	$objSheet->getColumnDimension('F')->setWidth(12);
	$objSheet->getColumnDimension('G')->setWidth(20);
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
	//$objSheet->getStyle('H1')->applyFromArray($styleArray);
	//$objSheet->getStyle('I1')->applyFromArray($styleArray);
	//$objSheet->getStyle('J1')->applyFromArray($styleArray);
		
	$objSheet->getStyle('A1:J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:J1')->getFont()->setBold(true)->setSize(10);
	
	$objSheet->getCell('A1')->setValue("Item Code");
	$objSheet->getCell('B1')->setValue('Item Name');
	$objSheet->getCell('C1')->setValue('Order No.');
	$objSheet->getCell('D1')->setValue('Order Balance');
	$objSheet->getCell('E1')->setValue('Target for S/O');
	$objSheet->getCell('F1')->setValue('Shipment Date');
	$objSheet->getCell('G1')->setValue('Variance');
	$objSheet->getCell('H1')->setValue('Remarks');
	$objSheet->getCell('I1')->setValue('WIP');
	$objSheet->getCell('J1')->setValue('FGS');
	
	$colno = 2;
	$getAllDeliveryConfirmation = PPSDBS::getInstance()->getDeliveryConfirmation('date',$startDate, $endDate, '');
	while($rowDelivery = mysqli_fetch_array($getAllDeliveryConfirmation)) {
		$getAllDeliveryConfirmation1 = PPSDBS::getInstance()->getDeliveryConfirmation('orderno', '','',$rowDelivery['OrderNo']);
		while($row = mysqli_fetch_array($getAllDeliveryConfirmation1)) {
			$getSubValues = PPSDBS::getInstance()->getDeliveryConfirmation('orderno2', '','',$row['OrderNo']);
			while($row2 = mysqli_fetch_array($getSubValues)) {
				$variance = $row2["Variance"];
				$Remarks .= $row2["Remarks"].", ";
			}
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('A'.$colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('B'.$colno, $row['ItemName'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('C'.$colno, $row['OrderNo'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('D'.$colno, $row['OrderBalance'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('E'.$colno, $row['TargetSO'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('F'.$colno, $row['ShipmentDate'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('G'.$colno, $variance,PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('H'.$colno, $Remarks,PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('H'.$colno, $row['WIP'],PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('J'.$colno, $row['FGS'],PHPExcel_Cell_DataType::TYPE_STRING);
			$colno++;
			$objSheet->getStyle('A2:J'.($colno-1))->applyFromArray($styleArray);
		}
	}

	$file_date = date('ymd');
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=Delivery Confirmation '.$file_date.'.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>