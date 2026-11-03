<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$excel_class = 'class/excel.php';
if(file_exists($excel_class)){
	include($excel_class);
}else{
	echo 'File '.$excel_class.' does not exist';
	exit;
}

$excel = new EXCEL;
/* set title */
$excel->title = 'Excel Shipout Per Device';
/* add a new page */
$excel->add_page();
/* use the default font style */
$excel->set_default_font_style();
/* select an active page */
$excel->set_active_sheet(0);

/* set width - Array Cells, Array Width */
// $array_cell = array('A','B','C','E');
// $array_width = array('20','20','20','20');
// $excel->set_width($array_cell,$array_width);

/* set height - Array Rows, Array Height */
// $array_row = array('2','3');
// $array_height = array(25,50);
// $excel->set_height($array_row,$array_height);

/* borders - Cell, Left, Right, Top, Bottom */
// $excel->set_borders('A2',1,1,1,1);
// $excel->set_borders('B2:B3',1,1,1,1);
/* set file name */

/* set font - cell, bold, color, size, style */
// $excel->set_font('A1',true,'',14,'Verdana');

/* place value - cell, value, type(string or blanl), alignment, wordwrap(true or false) */
$array_cell = array(
					"A",
					"B",
					"C"
					);
$array_width = array(
					30,
					15,
					20
					);					
$excel->set_width($array_cell,$array_width);
$excel->place_value('A1','Devicename','string','',true);
$excel->place_value('B1','Devicecode','string','',true);
$excel->place_value('C1','Total Shipout Quantity','string','',true);

require_once('ppsdbs-oop.php');
$row_cnt = 2;
$result = PPSDBS::getInstance()->shipout_per_device();
while($row = mysqli_fetch_array($result)){
	$excel->place_value('A'.$row_cnt,$row['DeviceName'],'string','',true);
	$excel->place_value('B'.$row_cnt,$row['ItemCode'],'string','',true);
	$excel->place_value('C'.$row_cnt,$row['ShipoutQty'],'number_0_decimal','',true);
	$row_cnt++;
}

$filename = "Shipout Per Device";
/* output excel - filename, excel version (2003,2007) */
$excel->output($filename,'2003');
