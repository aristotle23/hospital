<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid'])){
	$patient =  new patient($_REQUEST['pid']);
	$patientIno = $patient->info();
	//$patient = $patient['name'];
	
}else{
	header("location:index.php");
	exit;
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' && $_REQUEST['pid']){
	
	$drugs = $_REQUEST['drug'];
	$dosages = $_REQUEST['dosage'];
	$routines = $_REQUEST['routine'];
	$routes = $_REQUEST['route'];
	$quantities = (isset($_REQUEST['quantity'])) ? $_REQUEST['quantity'] : null;
    $tremarks = (isset($_REQUEST['tremark'])) ? $_REQUEST['tremark'] : null;
	$pid = $_REQUEST['pid'];
    /*$confId = null;
	if($patient->is_admitted()){
	    $chckId = $db->getOne("select id from treatment_conf where patient_id = ?",array($pid));
	    if($chckId == null) {
            $confId = $db->executeGetId("insert into treatment_conf (date, remark, patient_id) VALUES (current_date(),?,?)", array($_REQUEST['remark'], $pid));
        }else{
	        $confId = $chckId['id'];
        }
    }*/
	for($i = 0 ; $i < count($drugs) ; $i++){
		$drug = $drugs[$i];
		$dosage = $dosages[$i];
		$quantity = ($quantities != null) ? $quantities[$i] : null;
        $tremark = ($tremarks != null) ? $tremarks[$i] : null;
		$routine = $routines[$i];
		$route = $routes[$i];
		$id = $db->executeGetId("INSERT INTO `treatment` (medicine_id, `dosage`, `routine`,quantity,`patient_id`,user_id,route,note) VALUES (?, ?, ?, ?,?,?,?,?);",
								array($drug,$dosage,$routine,$quantity,$pid,$_SESSION['user_id'],$route,$tremark));
	}
	header("location:patient_list.php?pid=?".$pid."&success=Treatment saved successfully");
	exit;
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Treatment <small><?php echo $patientIno['name'] ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">

                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <?php
                                $diagnosedate = $db->getOne("select distinct(date) from diagnosis order by date desc limit 1");
                                ?>
                                <table width="100%" class="table table-striped table-bordered table-hover" >

                                    <thead>
                                    <tr>
                                        <th >Complaint</th>
                                        <th>Observe</th>
                                        <th >Diagnosis</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <?php
                                            $diagnose = $db->getOne("select diagnose from diagnosis where date = ? and services_id = 0",array($diagnosedate['date']));
                                            echo $diagnose['diagnose'];
                                            ?>
                                        </td>
                                    <td>
                                            <?php
                                            $diagnose = $db->getOne("select result from diagnosis where date = ? and services_id = 0",array($diagnosedate['date']));
                                            echo $diagnose['result'];
                                            ?>
                                        </td>
                                    <td>
                                            <?php
                                            $diagnosis = $db->getAll("select diagnose, result from diagnosis where date = ? and services_id is null",array($diagnosedate['date']));
                                                foreach ($diagnosis as $key => $diagnose){
                                                    if($key == 0){
                                                        echo $diagnose['diagnose']." => ".$diagnose['result'];
                                                        continue;
                                                    }

                                                    echo "<br />".$diagnose['diagnose']." => ".$diagnose['result'];
                                                }
                                            ?>
                                        </td>
                                    </tr>
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


                
                <form class="form-horizontal" role="form" id="treatment-form" method="post">
                <!--<div class="form-group">
                    <?php
/*                        if($patient->is_admitted()) {
                            */?>
                            <div class="col-md-6 col-md-offset-1">
                                <textarea class="form-control" name="remark" placeholder="Remark"></textarea>
                            </div>
                            <?php
/*                        }
                    */?>
                </div>-->
                <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <div class="form-group " id="treatment-group">
                
                 <div class="col-md-1">
                      <button class="btn btn-block btn-primary  "  id="treatment-plus" ><i class="fa fa-plus"></i></button>
                  </div>
                  <div class="col-md-3" >

                    <select id="treatment-drug" class="form-control">
                        <option >Please select</option>
					<?php
                        $drugs = $db->getAll("select id, name from medicine");
                        foreach ($drugs as $drug){
                            echo '<option value="'.$drug['id'].'">'.$drug['name']."</option>";
                        }
                    ?>
                    </select>
                  </div>
                    <div class="col-md-2" id="diag-result-con" >
                        <input class="form-control" id="treatment-route"  placeholder="Route of Administration"   />
                    </div>
                  <div class="col-md-2" id="diag-result-con" >
                  	<input class="form-control" id="treatment-dosage"  placeholder="Dosage" onkeypress="return isNumber(event)"   />
                  </div>

                  <div class="col-md-2" id="diag-result-con" >
                  	<input class="form-control" id="treatment-routine"  placeholder="Frequency" onkeypress="return isNumber(event)"  />
                  </div>
                    <?php
                    if(!$patient->is_admitted()) {
                        ?>
                        <div class="col-md-2" id="diag-result-con">
                            <input class="form-control" id="treatment-quantity" onkeypress="return isNumber(event)" placeholder="Quantity"/>
                        </div>
                        <?php
                    }else {
                        ?>
                        <div class="col-md-2" id="diag-result-con">
                            <textarea rows="3" class="form-control" id="treatment-remark" placeholder="Note"></textarea>
                        </div>
                        <?php
                    }
                    ?>
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
                 
                  
                  <div class="col-md-offset-1 col-md-2 ">
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
