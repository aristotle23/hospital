<?php
require_once "script/ini.php";
if(isset($_REQUEST['logout'])){
	session_unset();
	session_destroy();
	setcookie("username",'username',strtotime("-4 week"),'/');
	setcookie("upas",'password',strtotime("-4 week"),'/');
	setcookie("page",'password',strtotime("-4 week"),'/');
	header("location:login.php");
}
if(isset($_REQUEST['login']) && $_REQUEST['login'] == 'login'){
	$username = $_REQUEST['username'];
	$password = $_REQUEST['password'];
	if(isset($_REQUEST['remember'])){
		setcookie("username",$username,strtotime("+4 week"),'/');
		setcookie("upas",md5($password),strtotime("+4 week"),'/');
	}
	$user = $db->getOne("select id,access_right,name from user where username = ? and password = ?",array($username,md5($password)));
	if($user){
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['right'] = $user['access_right'];
		$_SESSION['username'] = $username;
		$_SESSION['name'] = $user['name'];
		$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",array("User Login",$user['id']));
		header("location: index.php");
	}else{
		header("location:login.php?error=username or password not correct");
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hospital - Login</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style="background:url(img/b1.jpg)">

    <div class="container" >
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                       
                    </div>
                    <div class="panel-body">
                        <form role="form">
                        	<span class="help-block" style="color:red"><?php echo isset($_REQUEST['error']) ? $_REQUEST['error'] : NULL ?></span>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                               <span dir="rtl" class="help-block" style="margin-bottom:2px"><small><a href="reset.php">? Forgot your password</a></small></span>
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <div class="form-group">
                                <button type="submit" name="login" value="login" class="btn btn-lg btn-success btn-block">Login</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
