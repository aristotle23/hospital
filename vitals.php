<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
}else{
	header("location:index.php");
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' && $_REQUEST['pid']){
	
	$bpressure = $_REQUEST['bpressure'];
	$temperature = $_REQUEST['temperature'];
    $pressure = $_REQUEST['pressure'];
    $respiratory = $_REQUEST['respiratory'];
    $weight = $_REQUEST['weight'];
	$pid = $_REQUEST['pid'];
    $date = date('Y-m-d',strtotime('today'));
    $db->execute("insert into vitals (date, patient_id, temperature, blood_pressure, respiratory, weight, pressure) VALUES (?,?,?,?,?,?,?)",
        array($date,$pid,$temperature,$bpressure,$respiratory,$weight,$pressure));
	header("location:patient_view.php?pid=".$pid."&success=Vital sign saved successfully");
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Vital Sign <small><?php echo $patient ?></small> </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            	<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <div class="row ">
                    <div class="col-lg-12 ">
                    
                        <div class="col-lg-4" >
                                        
                            <div class="form-group">
                                <label>Temperature</label>
                                <input type="hidden" name="vital[]" value="Temperature" />
                                <input class="form-control " type="text" name="temperature" required="required" >
                            </div>
    
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Blood Pressure</label>
                                 <input type="hidden" name="vital[]" value="Blood Pressure" />
                                <input type="text" class="form-control" name="bpressure" required="required" >
                                <!--<p class="help-block ">Hospital Number Already Exists.</p>-->
                            </div>
                                        
                         </div>
    					<div class="col-lg-4">
                                        
                            <div class="form-group">
                                <label class="control-label" >Respiratory</label>
                                <input type="hidden" name="vital[]" value="Respiratory" />
                                <input type="text" class="form-control" name="respiratory" required="required" >
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
                                <label class="control-label" >Weight</label>
                                <input type="hidden" name="vital[]" value="Weight" />
                                <input type="text" class="form-control" name="weight" required="required">
                                
                            </div>
                                        
                         </div>
                         
                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Pressure</label>
                                <input type="hidden" name="vital[]" value="Pressure" />
                                <input type="text" class="form-control" name="pressure" required="required" >
                                
                            </div>
                                        
                         </div>
    					
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                
                <div class="row" style="margin-top:30px">
                    <div class="col-lg-12">
                    <div class=" col-lg-6 col-lg-offset-3">
                    	<button type="submit" class="btn btn-block btn-primary btn-lg " name="submit" value="save" ><b>Save</b></button>

                    </div>
                    </div>
                <!-- /.col-lg-12 -->
            	</div>
            </form>
            <!-- /.row -->
        </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<?php
require_once "template/footer.php";
?>
