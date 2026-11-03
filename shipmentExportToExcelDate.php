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
	$startTime 	= $_GET['startTime'].':00';
	$endTime 	= $_GET['endTime'].':59';
	$division 	= $_GET['division'];
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Verdana');

	/* 11192024 by Nessa */
	// $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
	// $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
	// $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
	// $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
	// $objPHPExcel->getActiveSheet()->getProtection()->setPassword('p@ssw0rd');

	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(7.5); // 11192024 by Nessa
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "SHIPMENT"; //set title cont.
	$objSheet->setTitle($title); //set title cont.
	$objSheet->mergeCells('A1:K1');
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
	$objSheet->getColumnDimension('A')->setWidth(40);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(25);
	$objSheet->getColumnDimension('E')->setWidth(40);
	$objSheet->getColumnDimension('F')->setWidth(15);
	$objSheet->getColumnDimension('G')->setWidth(10);
	$objSheet->getColumnDimension('H')->setWidth(12);
	$objSheet->getColumnDimension('I')->setWidth(15);
	$objSheet->getColumnDimension('J')->setWidth(25);
	$objSheet->getColumnDimension('K')->setWidth(30);
		
	$objSheet->getStyle('A2:K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:K1')->getFont()->setBold(true)->setSize(12);
	$objSheet->getStyle('A1:K2')->getFont()->setBold(true)->setSize(8.5);
	$objSheet->getStyle('A1:K2')->getFont()->setBold(true)->setSize(8.5);
	
	$objSheet->getCell('A1')->setValue('INVOICE SALES SUMMARY'); 
	$objSheet->getCell('A2')->setValue('Item Name'); 
	$objSheet->getCell('B2')->setValue('Date Issued');
	$objSheet->getCell('C2')->setValue('Order No'); 
	$objSheet->getCell('D2')->setValue('Control Number');
	$objSheet->getCell('E2')->setValue('Pre-Shipment Ctrl #');
	$objSheet->getCell('F2')->setValue('Product Code'); 
	// $objSheet->getCell('G2')->setValue('Product PO No'); 
	$objSheet->getCell('G2')->setValue('Quantity'); 
	$objSheet->getCell('H2')->setValue('U/P'); 
	$objSheet->getCell('I2')->setValue('Amount'); 
	$objSheet->getCell('J2')->setValue('Lot Number'); 
	$objSheet->getCell('K2')->setValue('Product Category'); 
	
	$colno = 3;
	$getTransaction = PPSDBS::getInstance()->getShipmentRecord2($startDate, $endDate,$startTime, $endTime, $division);
	// echo json_encode($getTransaction);
	// exit();
	$getShipmentRecord2_script = PPSDBS::getInstance()->getShipmentRecord2_script($startDate, $endDate,$startTime, $endTime, $division);
	
	while($row = mysqli_fetch_array($getTransaction)) {
		$grandTotalQuantity = $grandTotalQuantity + $row['ShipoutQty'];
		$amount = $row['ShipoutQty']  * $row['UnitPrice'];
		$grandTotalAmount = $grandTotalAmount + $amount;
		$objSheet->getCell('A' . (string) $colno)->setValue($row['ItemName']);
		$objSheet->getCell('B' . (string) $colno)->setValue($row['DateCreated']);
		$objSheet->getCell('C' . (string) $colno)->setValue($row['OrderNo']);
		$objSheet->getCell('D' . (string) $colno)->setValue($row['ControlNumber']);
		$objSheet->getCell('E' . (string) $colno)->setValue($row['PreshipCtrlNo']);
		$objSheet->setCellValueExplicit('F' . (string) $colno, $row['ItemCode'],PHPExcel_Cell_DataType::TYPE_STRING);
		$objSheet->getCell('K' . (string) $colno)->setValue($row['Category']);

		// if($row['Category'] == "GRINDING-SPECIAL CASE"){
		// 	$objSheet->setCellValueExplicit('G' . (string) $colno, $row['Category'].'('.$row['ProductPONo'].')',PHPExcel_Cell_DataType::TYPE_STRING);
		// }else{
		// 	$objSheet->setCellValueExplicit('G' . (string) $colno, $row['ProductPONo'],PHPExcel_Cell_DataType::TYPE_STRING);
		// }

		$objSheet->getCell('G' . (string) $colno)->setValue($row['ShipoutQty']);

		$objSheet->getCell('H' . (string) $colno)->setValue($row['UnitPrice']);
		$objSheet->getStyle('H'.$colno)->getNumberFormat()->setFormatCode('#,##0.0000');		

		$objSheet->setCellValue('I'.(string) ($colno), '=ROUND(G'.(string) ($colno).'*H'.(string) ($colno).',2)');
		$objSheet->getStyle('I'.$colno)->getNumberFormat()->setFormatCode('#,##0.00');

		$lotno = "";
		$lotno = PPSDBS::getInstance()->getPreshipLotNo($row['ItemCode'],$row['OrderNo']);
		$objSheet->setCellValueExplicit('J' . (string) $colno, $row['LotNo'],PHPExcel_Cell_DataType::TYPE_STRING);

		//Border
		$objSheet->getStyle('A1:K'.$colno)->applyFromArray($styleArray);

		$colno++;
	}
	
	$Total = $colno - 1;

	$objSheet->getCell('F' . (string) ($colno))->setValue('Grand Total');
	$objSheet->getCell('G' . (string) ($colno))->setValue($grandTotalQuantity);
	$objSheet->getStyle('G' . (string) ($colno))->getNumberFormat()->setFormatCode('#,##0');

	$objSheet->setCellValue('I'. (string) ($colno), "=SUM(I3:I".$Total.")");
	$objSheet->getStyle('I' . (string) ($colno))->getNumberFormat()->setFormatCode('#,##0.00');
	$objPHPExcel->getActiveSheet()->getStyle('I'.$colno)->getNumberFormat()->setFormatCode('#,##0.00');		
	
	$objSheet->getStyle('A' . (string) ($colno) . ':F' . (string) ($colno))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	//Border
	$objSheet->getStyle('F'.$colno.':I'.$colno)->applyFromArray($styleArray);

	//WORD WRAP
	// $objPHPExcel->getActiveSheet()->getStyle('A1:K'.(string)($colno))->getAlignment()->setWrapText(true);
	$objSheet->getCell('A' . (string) ($colno + 2))->setValue('Prepared By');
	
	$user 		= '';
	$position 	= '';

	/* 10152024 By Nessa */
	if($username == 'irmacas'){
		$user 		= 'Irma Castro';

		$objSheet->getCell('A' . (string) ($colno + 4))->setValue($user);
		$objSheet->getCell('A' . (string) ($colno + 5))->setValue('PPC Sr. Planner');

		$objSheet->getCell('G' . (string) ($colno + 9))->setValue('Exported by: '.$employeeName);

		$objSheet->getCell('B' . (string) ($colno + 2))->setValue('Conformed By');
		$objSheet->getCell('B' . (string) ($colno + 4))->setValue('Bernadette Vizcarra');
		$objSheet->getCell('B' . (string) ($colno + 5))->setValue('CN-PPC Jr. Planner');

		$objSheet->getCell('D' . (string) ($colno + 2))->setValue('Approved By:');
		$objSheet->getCell('D' . (string) ($colno + 4))->setValue('Grace B. Rivero');
		$objSheet->getCell('D' . (string) ($colno + 5))->setValue('PPD Sr. Manager');

		// $objSheet->getCell('D' . (string) ($colno + 2))->setValue('Checked By:');
		// $objSheet->getCell('D' . (string) ($colno + 4))->setValue('Arnold Martinez');
		// $objSheet->getCell('D' . (string) ($colno + 5))->setValue('PPD-CN Asst. Manager');

		// $objSheet->getCell('F' . (string) ($colno + 2))->setValue('Approved By:');
		// $objSheet->getCell('F' . (string) ($colno + 4))->setValue('Grace B. Rivero');
		// $objSheet->getCell('F' . (string) ($colno + 5))->setValue('PPD Sr. Manager');

	}else if($username == 'josepca'){
		$user 		= 'Josephine Cahilig';

		$objSheet->getCell('A' . (string) ($colno + 4))->setValue($user);
		$objSheet->getCell('A' . (string) ($colno + 5))->setValue('PPC Sr. Planner');

		$objSheet->getCell('G' . (string) ($colno + 9))->setValue('Exported by: '.$employeeName);

		$objSheet->getCell('B' . (string) ($colno + 2))->setValue('Conformed By');
		$objSheet->getCell('B' . (string) ($colno + 4))->setValue('Cris Morica');
		$objSheet->getCell('B' . (string) ($colno + 5))->setValue('TS-PPC Sr. Planner');

		$objSheet->getCell('D' . (string) ($colno + 2))->setValue('Approved By:');
		$objSheet->getCell('D' . (string) ($colno + 4))->setValue('Grace B. Rivero');
		$objSheet->getCell('D' . (string) ($colno + 5))->setValue('PPD Sr. Manager');

	}
	
	$objSheet->getStyle('A'. (string) ($colno) .':I'.(string) ($colno + 5))->getFont()->setBold(true);

	$file_date = date('ymd');
	unset($styleArray);
	ob_end_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	//header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xlsx');
	header('Content-Disposition: attachment;filename=Shipment '.$file_date.'.xls');
	header('Cache-Control: max-age=0');
	
	//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>