<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Upload page</title>
<style type="text/css">
body {
	background: #E3F4FC;
	font: normal 14px/30px Helvetica, Arial, sans-serif;
	color: #2b2b2b;
}
a {
	color:#898989;
	font-size:14px;
	font-weight:bold;
	text-decoration:none;
}
a:hover {
	color:#CC0033;
}

h1 {
	font: bold 14px Helvetica, Arial, sans-serif;
	color: #CC0033;
}
h2 {
	font: bold 14px Helvetica, Arial, sans-serif;
	color: #898989;
}
#container {
	background: #CCC;
	margin: 100px auto;
	width: 945px;
}
#form 			{padding: 20px 150px;}
#form input     {margin-bottom: 20px;}
</style>
</head>
<body>
<div id="container">
<div id="form">
<?php
//includes
require_once("./ppsdbs-oop.php");

//Upload File
if (isset($_POST['submit']) && $_FILES['filename']['tmp_name']!="") {
	if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
		echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
		echo "<a href='./shipment.php'><button>GO BACK</button></a>";
		echo "<h2>Displaying contents:</h2>";
		//readfile($_FILES['filename']['tmp_name']);
	}

	//Import uploaded file to Database
	$handle = fopen($_FILES['filename']['tmp_name'], "r");
	$ctr = 0;
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		//$import="INSERT into tablename(item1,item2,item3,item4,item5) values('$data[0]','$data[1]','$data[2]','$data[3]','$data[4]')";
		//mysql_query($import) or die(mysql_error());
		if($ctr != 0){
			$item_name 		= $data[0];
			$date_issued 	= $data[1];
			$order_no 		= $data[2];
			$control_number	= $data[3];
			$product_code	= $data[4];
			$product_po_no	= $data[5];
			$quantity 		= $data[6];
			$up_old 		= $data[7];
			$amount_old		= $data[8];
			$up_new 		= $data[9];
			//$amount_new		= $data[10];
			//echo "<br><br>".$item_name."//".$date_issued."//".$order_no."//".$control_number."//".$product_code."//".$product_po_no."//".$quantity."//".$up_old."//".$up_new."//".$amount_old;
			if($control_number == "" || $order_no == ""){
				echo "fail update on PR ".$product_po_no." Order No and Control Number cannot be empty!<br>";
			}else{	
				$amount_new = 0;
				$amount_new = ($quantity * $up_new);
				//$amount_new = number_format((float)$amount_new, 2, '.', '');
				$result = PPSDBS::getInstance()->update_shipment_by_batch($control_number,$order_no,$quantity,$up_new,$amount_new);
				if($result == "1"){
					echo "<br>Update fail: '$order_no' with a control number of '$control_number' does not exist!;";
				}else{
					echo "<br>Update ".$order_no." Old Price =".$up_old.", New Price = ".$up_new.", New amount = ".$amount_new;
				}
			}
		}
		$ctr++;
	}

	fclose($handle);

	print "<br>Data Update! DONE!";
	//view upload form
}else {

	print "Upload new csv for update<br />\n";

	print "Please download the template for upload, Convert the file into csv and remove all comma's<br />\n";

	print "<form enctype='multipart/form-data' action='#' method='post'>";

	print "File name to import:<br />\n";

	print "<input size='50' type='file' name='filename'><br />\n";

	print "<input type='submit' name='submit' value='Upload'></form>";

}

?>

</div>
</div>
</body>
</html>
