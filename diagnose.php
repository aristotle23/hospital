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
	//$alert = $_REQUEST['alert'];
	//$a_service = $_REQUEST['service'];
	$a_test = (!isset($_REQUEST['test'])) ? array() : $_REQUEST['test'];
	//$a_result = $_REQUEST['result'];
	$p_id = $_REQUEST['pid'];
	$complaint = $_REQUEST['complaint'];
	$observe = $_REQUEST['observe'];
	$date = date("Y-m-d");
	$diag = $db->executeGetId("INSERT INTO `diagnosis` (`services_id`,`diagnose`, `result`,`payment`, `patient_id`, date )
									 VALUES (0,?, ?, ?, ?, ?)",array($complaint,$observe,0,$p_id,$date));
	if($diag){
		for($i = 0 ; $i < count($a_test) ; $i++){
			//$service = $a_service[$i];
			$test = $a_test[$i];
			//$result = ($a_result[$i] == "" ? NULL : $a_result[$i]);
			//$lab = (isset($_REQUEST['lab'.$i]) ? 1 : 0);
			//$lab = 1;
			$payment = (isset($_REQUEST['payment'.$i]) ? 1 : 0);
            $lab = (isset($_REQUEST['lab'.$i]) ? 1 : 0);

			$diag_id = $db->executeGetId("INSERT INTO `diagnosis` ( `diagnose`, `result`, `payment`, `patient_id`,date, lab)
										 VALUES (?, ?, ?, ?,?, ?)",array($test,null,$payment,$p_id,$date, $lab));
		}
	}
	//print_r($_REQUEST);
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
<style>
#diag-result-con{
	display:none
}
</style>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Patient Diagnosis <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                    <h4>Vitals</h4>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <?php
                                $vital = $db->getOne("select * from vitals order by date desc limit 1");
                                ?>
                                <table width="100%" class="table table-striped table-bordered table-hover" >

                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Temperature</th>
                                        <th>Blood Pressure</th>
                                        <th>Respiratory</th>
                                        <th>Weight</th>
                                        <th>Pressure</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                        echo "<tr >";
                                        echo "<td >" . date_format(date_create($vital['date']),"Y-m-d") . "</td>";
                                        echo "<td >" . $vital['temperature'] . "</td>";
                                        echo "<td >" . $vital['blood_pressure'] . "</td>";
                                        echo "<td >" . $vital['respiratory'] . "</td>";
                                        echo "<td >" . $vital['weight'] . "</td>";
                                        echo "<td >" . $vital['pressure'] . "</td>";
                                        echo "</tr >";
                                    ?>

                                    </tbody>

                                </table>
                                <!-- /.table-responsive -->

                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                
                <form class="form-horizontal" role="form" id="diag-form" method="post">
                <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <!--<input type="hidden" name="alert" value="0"  />-->
                <div class="form-group">
                	
                    <label class="control-label col-md-1">Complaint</label>

                    <div class="col-md-7">
                    	<textarea rows="4" class="form-control" name="complaint" ></textarea>
                    </div>
                </div>
                <div class="form-group">
                	
                    <label class="control-label col-md-1">Observe</label>

                    <div class="col-md-7">
                    	<textarea rows="4" class="form-control" name="observe" ></textarea>
                    </div>
                </div>
                <div class="form-group " id="diag-group">
                
                 <div class="col-md-1">
                      <button class="btn btn-block btn-primary  " data-idx="0" id="diag-plus" ><i class="fa fa-plus"></i></button>
                  </div>

                  <!--<div class="col-md-3">
                    <select class="form-control"  id="diag-service"  >
                    <option  disabled="disabled" selected="selected">Select service...</option>
                    <?php
/*						$services = $db->getAll("SELECT id, services FROM services where hide = '0' and access_right < ? or access_right = ?",
												array($_SESSION['right'],$_SESSION['right']));
						foreach($services as $service){
							print '<option value="'.$service['id'].'">'.$service['services'].'</option>';
						}
					*/?>
                    </select>
                  </div>-->
                  <div class="col-md-4" id="diag-test-con" >
                      <select class="form-control "id="diag-test"  >
                          <option disabled selected value="">Please Select Laboratory Test</option>
                          <?php
                          $labTest = $db->getAll("select * from lab_test");
                          foreach($labTest as $test){
                              echo "<option value='".$test['test']."' >".$test['test']."</option>";
                          }
                          ?>
                      </select>
                  	<input type="text" class="form-control" style="display: none"  placeholder="Surgery/ Procedure e.t.c"  />
                  </div>
                  <div class="col-md-2" id="diag-result-con" >
                  	<input class="form-control" id="diag-result"  placeholder="result"   />
                  </div>
                  
                  <div class="col-md-1 ">
                  <label class="checkbox" style="margin-left: 20px"  ><input type="checkbox"  id="diag-payment" checked  /> Payment</label>
                  </div>

                    <div class="col-md-4 ">
                        <label class="checkbox" style="margin-left: 20px"  ><input type="checkbox"  id="diag-lab" checked  /> Laboratory <small>(Unchecked if not laboratory)</small></label>
                    </div>
                  	
                </div>
                
                <!--<div class="form-group">
                  <div class="col-md-1">
                      <button class="btn btn-block btn-primary billminus" ><i class="fa fa-minus"></i></button>
                  </div>
                  <div class="col-md-4">
                    <select class="form-control" name="service[]">
                    <option>Please Select...</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                  	<input class="form-control" placeholder="0.00" dir="rtl" required="required" name="amount[]"/>
                  </div>
                </div>-->
                
                
                <div class="form-group" style="margin-top:30px">
                 
                  
                  <div class="col-md-offset-3 col-md-4 ">
                  <button type="submit" class="btn btn-block btn-primary " name="submit" value="save" ><b>Save</b></button>
                  </div>
                  
                </div>
                <!-- /.row -->
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<?php
require_once "template/footer.php";
?>
