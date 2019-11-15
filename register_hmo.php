<?php
require_once "script/ini.php";
if(isset($_REQUEST['register']) && $_REQUEST['register'] == 'register'){
$date = $_REQUEST['date'];
$hospital_id = $_REQUEST['hospital_id'];
$name = $_REQUEST['name'];
$address = $_REQUEST['address'];
$phone = $_REQUEST['phone'];
$phone2 = $_REQUEST['phone2'];
$phone3 = $_REQUEST['phone3'];
$email = $_REQUEST['email'];

$patientid = $db->executeGetId("INSERT INTO `hmo` (`date`, `hospital_id`, `name`, `address`, `phone`,`email`,phone2,phone3) 
								VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($date,$hospital_id,$name,$address,$phone,$email,$phone2,$phone3));
  if($patientid){
	  
	 
		  $db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",array("HMO Registration",$_SESSION['user_id']));
		  header("location:?success=HMO registered successfully"); 
	  
  }else{
	header("location:?failed=unable to register HMO due to unknown error");
  }
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">HMO Registration</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="row ">
                	<div class="form-group" >
                    <label class="control-label col-md-2">Date</label>
                    <div class="col-md-2">
                    	 <input class="form-control date" type="text" name="date" required="required" autocomplete="off" >
                    </div>
                    </div>
                    <div class="form-group" >
                    <label class="control-label col-md-2">Hospital ID</label>
                    <div class="col-md-3">
                    	 <input class="form-control " type="text" name="hospital_id" required="required" autocomplete="off" >
                    </div>
                    </div>
                    <div class="form-group" >
                    <label class="control-label col-md-2">Full Name</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="text" name="name" required="required" >
                    </div>
                    </div>
                    <div class="form-group" >
                    <label class="control-label col-md-2">Address</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="text" name="address" required="required" >
                    </div>
                    </div>
                     <div class="form-group" >
                    <label class="control-label col-md-2">Phone</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="tel" name="phone" required="required" onkeypress="return isNumber(event)" >
                    </div>
                    </div>
                    <div class="form-group" >
                    <label class="control-label col-md-2">Phone2</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="tel" name="phone2"  onkeypress="return isNumber(event)" >
                    </div>
                    </div>
                    <div class="form-group" >
                    <label class="control-label col-md-2">Phone3</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="tel" name="phone3"  onkeypress="return isNumber(event)" >
                    </div>
                    </div>
                     <div class="form-group" >
                    <label class="control-label col-md-2">Email</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="email" name="email" required="required" >
                    </div>
                    </div>
                     <div class="form-group" >
                    <div class="col-md-4 col-md-offset-2">
                    	 <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block" name="register" value="register">Register</button>
                    </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
            </form>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    <!-- /#wrapper -->

    <!-- jQuery -->
    
<?php
require_once "template/footer.php";
?>