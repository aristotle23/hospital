<?php
require_once "script/dbHandler.php";
if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'reset'){
	$db = new dbHandler();
	$username = $_REQUEST['username'];
	$ques = $_REQUEST['ques'];
	$ans = $_REQUEST['ans'];
	$chck = $db->getOne("select id from user where username = ? and question = ? and ans = ?",array($username,$ques,$ans));
	if($chck){
		$pass = mt_rand();
		$db->execute("update user set password = md5(?) where id = ?",array($pass,$chck['id']));
		header("location:?info=New password :: ".$pass);
	}else{
		header("location:?info=Reset failure, Please check information");
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
                        	<span class="help-block" style="color:#03F"><?php echo isset($_REQUEST['info']) ? $_REQUEST['info'] : NULL ?></span>
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Username" name="username" type="text" autofocus autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Secret Question" name="ques" type="text" autocomplete="off" >
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Answer" name="ans" type="text" autocomplete="off" >
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <div class="form-group">
                                <button type="submit" name="submit" value="reset" class="btn btn-lg btn-success btn-block">Reset</button>
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
