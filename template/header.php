
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Hospital</title>
	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap-datepicker.css">
    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

	    <!-- DataTables CSS -->
    <link href="vendor/datatables-plugins/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="vendor/morrisjs/morris.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="dist/css/style.css">
    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
    <script src="js/chart.min.js"></script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php"><?php echo "Hello! ". $_SESSION['name'] ?></a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
               <?php if($_SESSION['right'] <= 1){ ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts" id="app-notice">
                        
                        <li>
                            <a class="text-center" href="appointment_view.php">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <?php } ?>
                <!-- /.dropdown -->
                <?php if($_SESSION['right'] >= 3){ ?>
				
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts " id="edit-notification">
					<?php
					$result = $db->getAll("SELECT a.date,name,billing_id,patient_id,hospital_no,a.id FROM edit_alert a inner join 
											patient p on p.id = patient_id where status = 0 and ping = 1 order by a.date desc limit 8 ");
					foreach ($result as $alert){
						$date = $alert['date'];
						$date = timeAgo($date);
						print '<li><a class="bnotify" data-id="'.$alert['id'].'" href="edit_billing.php?pid='.$alert['patient_id'].'&bid='.
								$alert['billing_id'].'" >
							  <div>
								  <i class="fa fa-exchange fa-fw"></i> Bill edit req. for [ '.$alert['hospital_no'].' ]
								  <span class="pull-right text-muted small">'.$date.'</span>
							  </div>
								</a>
							</li>
							<li class="divider"></li>';
					}
					?>
                    <li data-id="0">
					  <a class="text-center" href="billing_view.php">
						  <strong>See All Alerts</strong>
						  <i class="fa fa-angle-right"></i>
					  </a>
					</li>
                        
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
				<?php }	?>
                
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#" data-toggle="modal" data-target="#modal-edituser"><i class="fa fa-user fa-fw"></i> Edit User</a>
                        </li>
                        <?php if($_SESSION['right'] > 2) { ?>
                        <li><a href="#" data-toggle="modal" data-target="#modal-newuser"><i class="fa fa-user fa-fw"></i> New User</a>
                        </li>
                        <li><a href="users.php"><i class="fa fa-user fa-fw"></i> View User</a>
                        </li>   
                        <?php } ?>                     
                        <li class="divider"></li>
                        <li><a href="login.php?logout=true"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

  <script>
  $("#chatAudio").play();
  </script>
