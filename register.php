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
$gid = (isset($_REQUEST['gid']) ? $_REQUEST['gid'] : 0);


$patientid = $db->executeGetId("INSERT INTO `patient` (`date`, `hospital_no`, `name`, `dob`, `occupation`, `marital_status`, `address`, `telephone`,
	`sex`,`group_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",array($date,$hospital_no,$name,$dob,$occupation,$marital,$address,$telephone,$sex,$gid));
  if($patientid){
	  $name = $_REQUEST['pbname'];
	  $address = $_REQUEST['pbaddress'];
	  $phone = $_REQUEST['pbphone'];
	  $success = $db->executeGetId("INSERT INTO `paidby` (`patient_id`, `address`, `telephone`, `name`) VALUES (?, ?, ?, ?)",
									  array($patientid,$address,$phone,$name));
	  if($success){
		  $db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",array("Patient Registration",$_SESSION['user_id']));
		  header("location:".$_SERVER['PHP_SELF']."?gid=".$gid."&success=patient registered successfully"); 
	  }else{
		  $db->execute("delete from patient where id = ?",array($patientid));
		  header("location:".$_SERVER['PHP_SELF']."?gid=".$gid."&failed=unable to register patient due to unknown error");
	  }
  }else{
	header("location:".$_SERVER['PHP_SELF']."?gid=".$gid."&failed=unable to register patient due to unknown error");
  }
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Registration 
                    <?php
					if(isset($_REQUEST['gid'])){
						$gname = $db->getOne("select name from patient_group where id = ?",array($_REQUEST['gid']));
						echo '<small>'.$gname['name'].'</small>';
					}
                    ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            	<?php echo (isset($_REQUEST['gid']) ? '<input type="hidden" name="gid" value="'.$_REQUEST['gid'].'" >' : "") ?>
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
                        <div class="col-lg-12">
                        <h3 class="page-header" style="margin-top:10px">BILL TO BE PAID BY</h3>
                        </div>
                    </div>
                <!-- /.col-lg-12 -->
            	</div>
                
                <div class="row">
                    <div class="col-lg-12">
                    
                        <div class="col-lg-4">
                                        
                            <div class="form-group">
                                <label class="control-label" >Name</label>
                                <input type="text" class="form-control" name="pbname" required="required" >
                            </div>
    
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Address</label>
                                <input type="text" class="form-control" name="pbaddress" required="required">
                                
                            </div>
                                        
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Telephone</label>
                                <input type="text" class="form-control" name="pbphone" required="required" >
                                
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