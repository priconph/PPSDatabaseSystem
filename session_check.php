<?php
	if(is_ajax()) {
		if(isset($_POST["action"]) && !empty($_POST["action"])) {
			$action = $_POST["action"];
			switch($action) {
				case "session_check" : session_check(); break;
			}
		}
	}
	
	function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function session_check(){
		session_start();
		$return = $_POST;
		$result = "";
		$return["username"] = $_SESSION['username'];
		$return["result"] = json_encode($result);
		echo json_encode($return);
	}
?>