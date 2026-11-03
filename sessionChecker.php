<?php
session_start();
$username = $_SESSION["username"];

if(isset($username)){
	echo "ACTIVE";
}else{
	echo "EXPIRED";
}

?>