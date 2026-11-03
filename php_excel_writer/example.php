<?php

require_once("../ppsdbs-oop.php");
include('ExcelWriterXML.php');

/**
 * @source
 */
$xml = new ExcelWriterXML;
$xml->docAuthor('PPS');

$format = $xml->addStyle('StyleHeader');
$format->fontBold();
$format->border('All');

$border = $xml->addStyle('StyleBorder');
$border->border('All');

$xml->columnWidth(1,100);

//$format->alignRotate(45);
$sheet = $xml->addSheet('PO Received');



//row then column
$sheet->writeString(1,1,'Date Issued','StyleHeader');
$sheet->writeString(1,2,'Category','StyleHeader');
$sheet->writeString(1,3,'Product P.O. No','StyleHeader');
$sheet->writeString(1,4,'Header','StyleHeader');
$sheet->writeString(1,5,'Item Code','StyleHeader');
$sheet->writeString(1,6,'Item Name','StyleHeader');
$sheet->writeString(1,7,'Order No.','StyleHeader');
$sheet->writeString(1,8,'Order Balance','StyleHeader');
$sheet->writeString(1,9,'Target for S/O','StyleHeader');
$sheet->writeString(1,10,'Shipment Date','StyleHeader');
$sheet->writeString(1,11,'Remarks','StyleHeader');

//	 							

$xml->sendHeaders();
$xml->writeData();
?>