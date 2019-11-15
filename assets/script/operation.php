<?php
require_once "dbHandler.php";
if(isset($_REQUEST['setup'])){
	if($_REQUEST['setup'] == 'checkdb'){
		$pass = $_REQUEST['pass'];
		$db = new dbHandler($pass);
		$db->testconn();
		echo json_encode(true);
	}
}
?>