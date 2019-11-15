<?php
require_once "script/ini.php";
if(!isset($_REQUEST['pid']) || !isset($_REQUEST['edit'])){
	header("location: index.php");
}
$patient = $db->getOne("select * from patient where id = ?",array($_REQUEST['pid']));
$phone = $patient['telephone'];
$id = $patient['hospital_no'];
$sex = $patient['sex'];
$marital = $patient['marital_status'];
$regdate = date_create($patient['date']);
$regdate = date_format($regdate,"Y-m-d");
$dob = date_create($patient['dob']);
$dob = date_format($dob,"Y-m-d");
$address = $patient['address'];
$occupation = $patient['occupation'];


if(isset($_REQUEST['update']) && $_REQUEST['update'] == 'update'){
$date = $_REQUEST['date'];
$hospital_no = $_REQUEST['hospital_no'];
$name = $_REQUEST['name'];
$telephone = $_REQUEST['telephone'];
$dob = $_REQUEST['dob'];
$sex = $_REQUEST['sex'];
$marital = $_REQUEST['marital_status'];
$occupation = $_REQUEST['occupation'];
$address = $_REQUEST['address'];
$db->execute("UPDATE `patient` SET `date`=?, `hospital_no`=?, `name`=?, `dob`=?, `occupation`=?, `marital_status`=?, `address`=?, 
			`telephone`=?, `sex`=? WHERE `id`=? ",array($date,$hospital_no,$name,$dob,$occupation,$marital,$address,$telephone,$sex,$_REQUEST['pid']));
$name = $_REQUEST['pbname'];
$address = $_REQUEST['pbaddress'];
$phone = $_REQUEST['pbphone'];
$db->execute("UPDATE `paidby` SET `address`=?, `telephone`=?, `name`=? WHERE `patient_id`=?",array($address,$phone,$name,$_REQUEST['pid']));
header("location:patient_view.php?pid=".$_REQUEST['pid']."&success=patient record updated successfully"); 
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
            	<input type="hidden" name="pid" value="<?php print $_REQUEST['pid'] ?>"  />
                <div class="row ">
                    <div class="col-lg-12 ">
                    
                        <div class="col-lg-4" >
                                        
                            <div class="form-group">
                                <label>Dete</label>
                                <input class="form-control date" type="text" name="date" required="required" value="<?php print $regdate ?>" />
                            </div>
    
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Hospital No.</label>
                                <input type="text" class="form-control" name="hospital_no" required="required" value="<?php echo $id ?>" >
                                <!--<p class="help-block ">Hospital Number Already Exists.</p>-->
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
                                        
                            <div class="form-group">
                                <label class="control-label" >Name</label>
                                <input type="text" class="form-control" name="name" required="required" value="<?php echo $patient['name'] ?>" >
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
                                <input type="text" class="form-control" name="telephone" required="required" value="<?php echo $phone ?>">
                                
                            </div>
                                        
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Date Of Birth</label>
                                <input type="text" class="form-control date" name="dob" required="required" value="<?php echo $dob ?>" >
                                
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
                         <div class="form-group ">
                                <label class="control-label" >Sex</label>
                                <select  class="form-control" name="sex" >
                                <option value="Null">Please Select ...</option>
                                <option <?php echo (strtolower($sex) == 'male') ? 'selected="selected"' : '' ?> >Male</option>
                                <option <?php echo (strtolower($sex) == 'female') ? 'selected="selected"' : '' ?>>Female</option>
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
                                <option <?php echo (strtolower($marital) == 'single') ? 'selected="selected"' : '' ?>>Single</option>
                                <option <?php echo (strtolower($marital) == 'married') ? 'selected="selected"' : '' ?>>Married</option>
                                </select>
                            </div>
    
                         </div>
                        
                    	<div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Occuptation</label>
                                <input type="text" class="form-control" name="occupation" required="required" value="<?php echo $occupation ?>" >
                                
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Address</label>
                                <input type="text" class="form-control" name="address" required="required" value="<?php echo $address ?>" >
                                
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
                        <?php
                        	$by = $db->getOne("select * from paidby where patient_id = ?",array($_REQUEST['pid']));
						?>
                        </div>
                    </div>
                <!-- /.col-lg-12 -->
            	</div>
                
                <div class="row">
                    <div class="col-lg-12">
                    
                        <div class="col-lg-4">
                                        
                            <div class="form-group">
                                <label class="control-label" >Name</label>
                                <input type="text" class="form-control" name="pbname" required="required" value="<?php echo $by['name'] ?>" >
                            </div>
    
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Address</label>
                                <input type="text" class="form-control" name="pbaddress" required="required" value="<?php echo $by['address'] ?>">
                                
                            </div>
                                        
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Telephone</label>
                                <input type="text" class="form-control" name="pbphone" required="required" value="<?php echo $by['telephone'] ?>" >
                                
                            </div>
                                        
                         </div>
    
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	
                <div class="row">
                    <div class="col-lg-12">
                    <div class=" col-lg-6 col-lg-offset-3">
                        <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block" name="update" value="update">Update</button>
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