<?php
require_once "script/ini.php";
if(isset($_REQUEST['register']) && $_REQUEST['register'] == 'register'){
$date = $_REQUEST['date'];
$hospital_no = $_REQUEST['hospital_no'];
$name = $_REQUEST['name'];
$telephone = $_REQUEST['telephone'];
$dob = $_REQUEST['dob'];
$sex = $_REQUEST['sex'];
$marital = $_REQUEST['marital_status'];
$occupation = $_REQUEST['occupation'];
$address = $_REQUEST['address'];

$patientid = $db->executeGetId("INSERT INTO `patient` (`date`, `hospital_no`, `name`, `dob`, `occupation`, `marital_status`, `address`, `telephone`,
						 `sex`,`hmo`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
						 array($date,$hospital_no,$name,$dob,$occupation,$marital,$address,$telephone,$sex,$_REQUEST['hmo']));
  if($patientid){
	$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",array("HMO Patient Registration",$_SESSION['user_id']));
	header("location:".$_SERVER['PHP_SELF']."?hmo=".$_REQUEST['hmo']."&success=HMO patient registered successfully");
  }else{
	header("location:".$_SERVER['PHP_SELF']."?hmo=".$_REQUEST['hmo']."&failed=unable to register HMO patient due to unknown error");
  }
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Registration</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <input type="hidden" name="hmo" value="<?php  echo $_REQUEST['hmo'] ?>"  />
                <div class="row ">
                    <div class="col-lg-12 ">
                    
                        <div class="col-lg-4" >
                                        
                            <div class="form-group">
                                <label>Dete</label>
                                <input class="form-control date" type="text" name="date" required="required" >
                            </div>
    
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Hospital No.</label>
                                <input type="text" class="form-control" name="hospital_no" required="required" >
                                <!--<p class="help-block ">Hospital Number Already Exists.</p>-->
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
                                        
                            <div class="form-group">
                                <label class="control-label" >Name</label>
                                <input type="text" class="form-control" name="name" required="required" >
                            </div>
    
                         </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                <div class="row">
                    <div class="col-lg-12">

                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Telephone</label>
                                <input type="text" class="form-control" name="telephone" required="required">
                                
                            </div>
                                        
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Date Of Birth</label>
                                <input type="text" class="form-control date" name="dob" required="required" >
                                
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
                         <div class="form-group ">
                                <label class="control-label" >Sex</label>
                                <select  class="form-control" name="sex" >
                                <option value="Null">Please Select ...</option>
                                <option>Male</option>
                                <option>Female</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
	                    <div class="col-lg-4">
                                        
                            <div class="form-group">
                                <label class="control-label" >Marital Status</label>
                                <select  class="form-control" name="marital_status" >
                                <option value="Null">Please Select ...</option>
                                <option>Single</option>
                                <option>Married</option>
                                </select>
                            </div>
    
                         </div>
                        
                    	<div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Occuptation</label>
                                <input type="text" class="form-control" name="occupation" required="required" >
                                
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Address</label>
                                <input type="text" class="form-control" name="address" required="required" >
                                
                            </div>
                                        
                         </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                <div class="row">
                    <div class="col-lg-12">
                    <div class=" col-lg-6 col-lg-offset-3">
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