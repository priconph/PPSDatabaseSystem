<?php
	require('../media/PHPExcel.php');
	//require_once("smpopsg-oop.php");
	session_start();
	if (array_key_exists("username", $_SESSION))
		{
			$username = $_SESSION['username'];
			//$username = 'arlbena';
		}
	//$ControlNumber = $_GET['ControlNumber'];
	$dateToday = date("M. d, Y");
	$dateCtrl = date("Y-m");
	//create new phpexcel page
	$objPHPExcel = new PHPExcel;
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Lucida Fax');
	//set font
	$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
	
	$objSheet = $objPHPExcel->getActiveSheet();
	$title = "Generate Quotation"; //set title cont.
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
	$objSheet->getColumnDimension('A')->setWidth(8);
	$objSheet->getColumnDimension('B')->setWidth(20);
	$objSheet->getColumnDimension('C')->setWidth(20);
	$objSheet->getColumnDimension('D')->setWidth(10);
	$objSheet->getColumnDimension('E')->setWidth(10);
	$objSheet->getColumnDimension('F')->setWidth(10);
	$objSheet->getColumnDimension('G')->setWidth(10);
	$objSheet->getColumnDimension('H')->setWidth(10);
	$objSheet->getColumnDimension('I')->setWidth(10);
	$objSheet->getColumnDimension('J')->setWidth(10);
	$objSheet->getColumnDimension('K')->setWidth(10);
	$objSheet->getColumnDimension('L')->setWidth(10);
	$objSheet->getColumnDimension('M')->setWidth(10);
	$objSheet->getColumnDimension('N')->setWidth(10);
	$objSheet->getColumnDimension('O')->setWidth(10);
	$objSheet->getColumnDimension('P')->setWidth(15);
		
		$objSheet->getStyle('A6')->applyFromArray($styleArray3);
		$objSheet->getStyle('B6')->applyFromArray($styleArray3);
		$objSheet->getStyle('C6')->applyFromArray($styleArray3);
		$objSheet->getStyle('D6')->applyFromArray($styleArray3);
		$objSheet->getStyle('E6')->applyFromArray($styleArray3);
		$objSheet->getStyle('F6')->applyFromArray($styleArray3);
		$objSheet->getStyle('G6')->applyFromArray($styleArray3);
		$objSheet->getStyle('H6')->applyFromArray($styleArray3);
		$objSheet->getStyle('I6')->applyFromArray($styleArray3);
		$objSheet->getStyle('J6')->applyFromArray($styleArray3);
		$objSheet->getStyle('K6')->applyFromArray($styleArray3);
		$objSheet->getStyle('L6')->applyFromArray($styleArray3);
		$objSheet->getStyle('M6')->applyFromArray($styleArray3);
		$objSheet->getStyle('N6')->applyFromArray($styleArray3);
		$objSheet->getStyle('O6')->applyFromArray($styleArray3);
		$objSheet->getStyle('P6')->applyFromArray($styleArray3);
		
	$objSheet->getStyle('A5:P5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A1:P4')->getFont()->setBold(false)->setSize(10);
	$objSheet->mergeCells('A1:B1');
	$objSheet->mergeCells('A2:B2');
	$objSheet->mergeCells('A3:B3');
	$objSheet->mergeCells('A4:B4');
	$objSheet->mergeCells('A5:B5');
	$objSheet->getCell('A1')->setValue('To:');
	$objSheet->getCell('A2')->setValue('Attn:'); 
	$objSheet->getCell('A3')->setValue('Cc:'); 
	$objSheet->getCell('A4')->setValue('Date:'); 
	$objSheet->getCell('A5')->setValue('PMI-'.$dateCtrl.'-'); 
	//$objSheet->mergeCells('D1:P1'); 
	
	$objSheet->getStyle('A6:P6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objSheet->getStyle('A6:P6')->getFont()->setBold(true)->setSize(8);
	//$objSheet->getStyle('A5:S5')->getFont()->setBold(true)->setSize(8);
	$objSheet->getCell('A6')->setValue('Item #'); 
	$objSheet->getCell('B6')->setValue('Device'); 
	$objSheet->getCell('C6')->setValue('Material Name'); 
	$objSheet->getCell('D6')->setValue(' '); 
	$objSheet->getCell('E6')->setValue('# of Cavity'); 
	$objSheet->getCell('F6')->setValue('Product Wt.(g)'); 
	$objSheet->getCell('G6')->setValue('Sprue Wt.(g)'); 
	$objSheet->getCell('H6')->setValue('Shot Weight(g)'); 
	$objSheet->getCell('I6')->setValue('Labor Cost/hr($)'); 
	$objSheet->getCell('J6')->setValue('Cycle Time'); 
	$objSheet->getCell('K6')->setValue('Process Cost'); 
	$objSheet->getCell('L6')->setValue('Material Cost (per piece)'); 
	$objSheet->getCell('M6')->setValue('2nd Process Time (secs)'); 
	$objSheet->getCell('N6')->setValue('2nd Process Cost'); 
	$objSheet->getCell('O6')->setValue('Profit'); 
	$objSheet->getCell('P6')->setValue('Molding Product Quotation($)'); 
		
	unset($styleArray);
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=PPSDatabaseSystem.xlsx');
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
?>