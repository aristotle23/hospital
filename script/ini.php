<?php
date_default_timezone_set("Africa/Lagos");
require_once "script/dbHandler.php";
require_once "script/functions.php";
$db = new dbHandler();
session_start();
if(basename($_SERVER['SCRIPT_NAME']) != 'login.php' && basename($_SERVER['SCRIPT_NAME']) != 'receipt.php'){
  if(!isset($_SESSION['user_id']) || !isset($_SESSION['right']) || !isset($_SESSION['username'])){
	 if(isset($_COOKIE['username']) && isset($_COOKIE['upas'])){
	  $username = $_COOKIE['username'];
	  $password = $_COOKIE['upas'];
	  $user = $db->getOne("select id,access_right from user where username = ? and password = ?",array($username,$password));
	  if($user){
		  $_SESSION['user_id'] = $user['id'];
		  $_SESSION['right'] = $user['access_right'];;
		  $_SESSION['username'] = $username;
		  $db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",array("User Login",$user['id']));
		  header("location:".$_COOKIE['page']);
	  }else{
		  header("location:login.php?logout=true");
	  }
	}else{
		header("location:login.php?logout=true");
	}
  }/*else{
	  if(!isset($_SERVER['HTTP_REFERER'])){
		  print 'cookie '.basename($_COOKIE['page']);
		  print ' server '.basename($_SERVER['PHP_SELF']);
		  if(basename($_COOKIE['page']) != basename($_SERVER['PHP_SELF'])){
			  setcookie('page',$_COOKIE['page'],strtotime("+4 week"),"/");
			  
			  //print 'cookie not same '.$_SERVER['HTTP_REFERER'];
			  //header("location:".$_COOKIE['page']);
		  }
		}
  }*/
}
setcookie('page',$_SERVER['SCRIPT_NAME'],strtotime("+4 week"),"/");
?>