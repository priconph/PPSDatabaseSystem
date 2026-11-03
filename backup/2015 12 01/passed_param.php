<?php
$yecControlNoDeviceName = str_replace("[]","#",$_GET['yecControlNoDeviceName']);
$Category = str_replace("[]","#",$_GET['Category']);

echo "SearcItem: ".$yecControlNoDeviceName."<br>";
echo "Category: ".$Category."<br>";
