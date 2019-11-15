<?php
require_once "script/ini.php";
if(!isset($_REQUEST['pid'])){
	header("location:index.php");
}
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
$date = $_REQUEST['date'];
$doctor = $_SESSION['user_id'];
$purpose = $_REQUEST['purpose'];

$ok = $db->executeGetId("INSERT INTO `appointment` (`patient_id`, `date`, `purpose`, `doctor`) VALUES (?, ?, ?, ?);",
							array($_REQUEST['pid'],$date,$purpose,$doctor));
if($ok){
	$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",
	array("New appointment created for SPID[".$_REQUEST['pid']."]",$_SESSION['user_id']));
	header("location:".$_SERVER['HTTP_REFERER']."&success=New appointment created successfully");
}else{
	header("location:".$_SERVER['HTTP_REFERER']."&failed=Unable to create appointment");
}
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">New Appointment</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            	<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <div class="form-group">
                <label class="col-md-1 control-label">Date</label>
                <div class="col-md-2">
                <input type="text" class="date form-control" name="date" required="required" />
                </div>
                </div>
                
                <div class="form-group">
                <label class="col-md-1 control-label">Purpose</label>
                <div class="col-md-4">
                <textarea rows="5" cols="8" class="form-control" name="purpose" required="required">
                </textarea>
                </div>
                </div>
                
                <div class="form-group">
                <div class="col-md-2 col-md-offset-1">
                <input type="submit" class="btn btn-block btn-primary btn-lg" value="Save" name="submit" required="required" />
                </div>
                
                
                
                
                
            </form>
            </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    <!-- /#wrapper -->

    <!-- jQuery -->
    
<?php
require_once "template/footer.php";
?>