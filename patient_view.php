<?php
if(!isset($_REQUEST['pid'])){
	header("location:".$_SERVER['HTTP_REFERER']);
}
require_once "script/ini.php";
require_once "script/rbilling.php";
require_once "script/helper.php";

if(isset($_REQUEST['emergency']) && $_REQUEST['emergency'] == true){
	$emergency = helperClass::setEmergency($_REQUEST['pid']);
	if($emergency){
		header("location:?pid=".$_REQUEST['pid']);
		exit;
	}
}
if(isset($_REQUEST['discharge']) && $_REQUEST['discharge'] == true){
	$db->execute("update patient set ward = NULL where id = ?",array($_REQUEST['pid']));
	header("location:?success=You have ordered patient to be discharged&pid=".$_REQUEST['pid']);
	exit;
}
if(isset($_REQUEST['history']) && (isset($_REQUEST["submit"]) && $_REQUEST['submit'] == "save")) {
    $db->execute("update patient_history set history = ? where patient_id = ? ", array( $_REQUEST['history'],$_REQUEST['pid'],));

    header("location:?pid=".$_REQUEST['pid']."&success=History saved successfully ");
    exit;

}

$patient = $db->getOne("select * from patient where id = ?",array($_REQUEST['pid']));
$billings = new patientBilling($patient['id']);
$pbill = $billings->getBilling();

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $name = $patient['name']; ?> <small> [<?php echo $name = $patient['hospital_no']; ?>]</small>
                        <small class="pull-right text-danger" style="cursor:pointer;" title="&#x20a6;<?php echo helperClass::debtOwed($patient['id']) ?>" ><b>Debtor</b> <i class="fa text-danger fa-arrow-circle-o-down"></i> </small></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
				<div class="col-lg-1 pull-left" style="margin-bottom: 20px; padding-top:25px">
                	<a title="Delete patient record" href="#" id="delPatient" data-pid="<?php echo $_REQUEST['pid'] ?>"
                    class="btn btn-danger  " ><i class="fa fa-times"></i></a>
                </div>
            	<?php
					if($_SESSION['right'] == 1){
				?>
            	<div class="col-lg-1 pull-left" style="margin-bottom: 20px; padding-top:25px">
                	<a title="Edit patient registration detail" href="editp.php?edit=true&pid=<?php echo $_REQUEST['pid'] ?>" 
                    class="btn btn-default  " ><i class="fa fa-edit"></i></a>
                </div>
            	
                
                <?php
					}
					if($_SESSION['right'] == 6){
				?>
                <div class="col-lg-2 pull-right" style="margin-bottom: 20px">
                	<label>Appointment</label>
                	<a href="appointment.php?pid=<?php echo $_REQUEST['pid'] ?>" class="btn btn-block btn-primary " >New</a>
                </div>
                <?php
						if($patient['ward'] != NULL){
				?>
                <div class="col-lg-2 pull-right" style="margin-bottom: 20px">
                	<label>Admission</label>
                	<a href="?pid=<?php echo $_REQUEST['pid'] ?>&discharge=true" class="btn btn-block btn-primary " >Discharge</a>
                </div>
                <?php
						}
				?>
                <div class="col-lg-3 pull-right" style="margin-bottom: 20px">
                <label>Amitted to </label>
                <select  class="form-control" id="admit" data-pid="<?php echo $_REQUEST['pid'] ?>"  >
                <option disabled="disabled" selected="selected"  >Select ward...</option>
                <?php
					$result = $db->getAll("select id, name from ward where state = 0");
					foreach($result as $ward){
						$name = $ward['name'];
						$free = $db->getOne("select count(id) as bed from bed where ward_id = ? and taken = 0",array($ward['id']));
						if($patient['ward'] == $ward['id']){
							echo '<option selected="selected" value="'.$ward['id'].'">'.$name.' ('.$free['bed'].')</option>';
						}else{
							echo '<option value="'.$ward['id'].'">'.$name.' ('.$free['bed'].')</option>';
						}
					}
				?>
                </select>	
                </div>
                
                <?php
					}
				?>
                

                <?php
					$user_ward = helperClass::myWard($_SESSION['user_id']);
				 	if($user_ward != false && $user_ward == $patient['ward']){
						
				?>
                <div class="col-lg-2 pull-right" style="margin-bottom: 20px">
                <label>Asigned bed  </label>
                <select  class="form-control" id="bed" data-idx"0" data-pid="<?php echo $_REQUEST['pid'] ?>"  >
                <option disabled="disabled" selected="selected"  >Select bed...</option>
                <?php
					$result = $db->getAll("SELECT b.id, number,patient_id FROM bed b inner join ward w on ward_id = w.id where w.user_id = ? ",
											array($_SESSION['user_id']));
					foreach($result as $bed){
						if($bed['patient_id'] == $_REQUEST['pid']){
							echo '<option selected="selected" value="'.$bed['id'].'">No. '.$bed['number'].'</option>';
						}else{
							echo '<option value="'.$bed['id'].'">No. '.$bed['number'].'</option>';
						}
					}
				?>
                </select>
                </div>
                <?php
					}else if($user_ward ){
				?>
                <div class="col-lg-2 pull-right" style="margin-bottom: 20px">
                <label>Emergency</label>
                	<a href="?pid=<?php echo $_REQUEST['pid'] ?>&emergency=true" class="btn btn-block btn-primary " >Admission</a>
                </div>
                <?php
					}
				?>
                <?php
				if($_SESSION['right'] == 5){
				?>
                <div class="col-lg-3 pull-right" style="margin-bottom: 20px">
                	<label> Assigned doctor</label>
                	<select  class="form-control" id="assign-staff" data-pid="<?php echo $_REQUEST['pid'] ?>" >
                <option disabled="disabled" selected="selected"  >Assign to doctor...</option>
                <?php
					$result = $db->getAll("select u.id , u.name from user u inner join access_right a on a.level = u.access_right where level = 6 ");
					foreach ($result as $doc){

						if($patient['user_id'] == $doc['id']){
							echo '<option selected="selected" value="'.$doc['id'].'">'.$doc['name'].'</option>';
						}else{
							echo '<option value="'.$doc['id'].'">'.$doc['name'].'</option>';
						}
						
					}
				?>
                </select>
                </div>
                <?php
				}
				?>
                <div class="col-lg-3 pull-right" style="margin-bottom: 20px">
                <label>Assign to group</label>
                <select  class="form-control" id="group" data-pid="<?php echo $_REQUEST['pid'] ?>"  >
                <option value="0" selected="selected"  >Select group...</option>
                <?php
					for($i = 0 ; $i < 2 ; $i++){
						$label = ($i == 0 ? "Family": "Organization");
						$opt .= "<optgroup label='".$label."'>";
						$groups = $db->getAll("select id, name from patient_group where type = ?",array($i));
						if(!$groups){
							continue;
						}
						foreach($groups as $group){
							if($group['id'] == $patient['group_id']){
								$opt .= '<option selected="selected" value="'.$group['id'].'">'.$group['name'].'</option>';
							}else{
								$opt .= '<option value="'.$group['id'].'">'.$group['name'].'</option>';
							}
						}
						$opt .= "</optgroup>";
						echo $opt;
					}
					
				?>
                </select>	
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body ">
                        	<div class="table-responsive">
                            <table width="100%" class="table table-striped table-bordered table-hover" >
          
                                <thead>
                                    <tr>
                                      
                                        <th width="12%">Phone</th>                                        
                                        <th width="10%">D.O.B</th>
                                        <th width="8%">Sex</th>
                                        <th width="12%">M. Status</th>
                                        <th width="12%">Reg. Date</th>
                                        <th >Address</th>
                                        <th >Occupation</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
                                <?php
                                    
									$trs = "";
									
										
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
										$trs .= "<tr><td >".$phone."</td><td >".$dob."</td><td >".$sex."</td><td>"
										.$marital."</td><td>".$regdate."</td><td>".$address."</td><td>".$occupation."</td></tr>";
									
									echo $trs;
                                    
                                    ?>
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                         </div>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#histmodal">My History</button>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4>Bill To Be Paid By</h4>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        
                            <table width="100%" class="table table-striped table-bordered table-hover" >
          
                                <thead>
                                    <tr>
                                      
                                        <th >Full Name</th>                                        
                                        <th >Address</th>
                                        <th >Telephone</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
                                <?php
                                    
									$by = $db->getOne("select * from paidby where patient_id = ?",array($_REQUEST['pid']));
									echo "<tr><td >".$by['name']."</td><td >".$by['address']."</td><td >".$by['telephone']."</td></tr>";
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
            <?php
            if($_SESSION['right'] == 8 || ( $_SESSION['right'] > 3 and $_SESSION['right'] <= 6 )) {
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Vital Signs</h4>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body ">

                                <table width="100%" class="table table-striped table-bordered table-hover"
                                       id="dataTables-example">

                                    <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th>Temperature</th>
                                        <th>Blood Pressure</th>
                                        <th>Respiratory</th>
                                        <th>Weight</th>
                                        <th>Pressure</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $vitals = $db->getAll("select * from vitals where patient_id = ? order by date desc", array($_REQUEST['pid']));
                                    foreach ($vitals as $vital) {
                                        echo "<tr >";
                                        echo "<td >" . $vital['date'] . "</td>";
                                        echo "<td >" . $vital['temperature'] . "</td>";
                                        echo "<td >" . $vital['blood_pressure'] . "</td>";
                                        echo "<td >" . $vital['respiratory'] . "</td>";
                                        echo "<td >" . $vital['weight'] . "</td>";
                                        echo "<td >" . $vital['pressure'] . "</td>";
                                        echo "</tr >";
                                    }
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Diagnosis</h4>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body ">

                                <table width="100%" class="table table-striped table-bordered table-hover"
                                       id="dataTables-example">

                                    <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th>Complaint</th>
                                        <th>Observation</th>
                                        <th>Procedures</th>
                                        <th>Laboratory</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $dates = $db->getAll("select distinct(date) as date from diagnosis where patient_id = ? order by date desc",
                                        array($_REQUEST['pid']));
                                    foreach ($dates as $date) {
                                        $complaint = "";
                                        $observation = "";
                                        $procedure = "";
                                        $lab = "<table class=\"table table-striped table-bordered table-hover\" >";
                                        $lab .= "<thead><tr><th >Test </th><th >Result</th></tr></thead><tbody>";
                                        echo "<tr >";
                                        echo "<td >" . $date['date'] . "</td>";
                                        $diagnosis = $db->getAll("select diagnose, result, services_id, lab from diagnosis where date = ? and patient_id = ? ",
                                            array($date['date'], $_REQUEST['pid']));
                                        foreach ($diagnosis as $key => $diagnose) {
                                            if ($diagnose['services_id'] != null) {
                                                $complaint .= $diagnose['diagnose'] . "<br />";
                                                $observation .= $diagnose['result'] . "<br />";
                                            } else {
                                                if ($diagnose['lab'] > 0) {
                                                    $lab .= "<tr></tr><td>" . $diagnose['diagnose'] . "</td><td>" . $diagnose['result'] . "</td></tr>";
                                                } else {
                                                    $procedure .= $diagnose['diagnose'] . " | ";
                                                }

                                            }
                                        }
                                        $lab .= "</tbody></table>";
                                        echo "<td >" . $complaint . "</td>";
                                        echo "<td >" . $observation . "</td>";
                                        echo "<td >" . $procedure . "</td>";
                                        echo "<td >" . $lab . "</td>";
                                        echo "</tr >";
                                    }
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

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Treatment</h4>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body ">

                                <table width="100%" class="table table-striped table-bordered table-hover"
                                       id="dataTables-example">

                                    <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th>Prescription</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $dates = $db->getAll("select distinct(date) as date from treatment where patient_id = ? order by date desc",
                                        array($_REQUEST['pid']));
                                    foreach ($dates as $date) {
                                        echo "<tr >";
                                        echo "<td >" . $date['date'] . "</td>";

                                        $complaint = "";
                                        $observation = "";
                                        $procedure = "";
                                        $prescription = "<table class=\"table table-striped table-bordered table-hover\" >";
                                        $prescription .= "<thead><tr><th >Drug</th><th >Route Of Admin.</th><th >Dosage</th><th >Frequency</th><th >Nursing</th></tr></thead><tbody>";

                                        $treatment = $db->getAll("select name, route, dosage, routine,t.id from treatment t inner join medicine m on t.medicine_id = m.id where t.date = ? and patient_id = ?",
                                            array($date['date'], $_REQUEST['pid']));
                                        foreach ($treatment as $medicine) {
                                            $prescription .= "<tr >";
                                            $prescription .= "<td >" . $medicine['name'] . "</td>";
                                            $prescription .= "<td >" . $medicine['route'] . "</td>";
                                            $prescription .= "<td >" . $medicine['dosage'] . "</td>";
                                            $prescription .= "<td >" . $medicine['routine'] . "</td>";
                                            if(helperClass::is_admitted($_REQUEST['pid'])){
                                                $prescription .= "<td ><button class='btn btn-block btn-primary btnthist' data-toggle='modal' data-tid='".$medicine['id']."' data-tname='".$medicine['name']."' data-target='#thistmodal'>Detail</button></td>";
                                            }

                                            $prescription .= "</tr >";
                                        }

                                        $prescription .= "</tbody></table>";

                                        echo "<td >" . $prescription . "</td>";
                                        echo "</tr >";
                                    }
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Appointment</h4>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body ">

                                <table width="100%" class="table table-striped table-bordered table-hover dataTables-example">

                                    <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th width="10%" >Phone</th>
                                        <th width="7%">Sex</th>
                                        <th >Doctor</th>
                                        <th >Purpose</th>
                                        <th width="8%">Task</th>
                                    </tr>
                                    </thead>
                                    <tbody id="ptblbody">
                                    <?php
                                    $appointments = $db->getAll("select * from appointment where patient_id = ? order by date desc",
                                        array($_REQUEST['pid']));
                                    foreach ($appointments as $appointment){
                                        $date = $appointment['date'];
                                        $doctor = $appointment['doctor'];
                                        $purpose = $appointment['purpose'];
                                        $patient_id = $appointment['patient_id'];
                                        $patient = $db->getOne("select * from patient where id = ?",array($patient_id));
                                        $trs = "";

                                        $name = $patient['name'];
                                        $phone = $patient['telephone'];
                                        $id = $patient['hospital_no'];
                                        $sex = $patient['sex'];

                                        $trs .= "<tr><td >".$date."</td><td>".$id."</td><td >".$name."</td><td >".$phone."</td><td >".$sex."</td><td >"
                                            .$doctor."</td><td>".$purpose."</td><td>";
                                        if($appointment['seen'] == 0){
                                            $trs .= "
										<a data-id='".$appointment['id']."' class='btn btn-primary btn-block seen'>Seen</a>";
                                        }
                                        $trs .= "</td></tr>";
                                        echo $trs;
                                    }


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
                <?php
            }
            if($_SESSION['right'] > 1 and $_SESSION['right'] <= 4 ) {
                ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Billing</h4>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body ">

                                <table width="100%" class="table table-striped table-bordered table-hover"
                                       id="dataTables-example">

                                    <thead>
                                    <tr>
                                        <th width="12%">Date</th>
                                        <th>Services</th>
                                        <th>Amt. Charged</th>
                                        <th>Amt. Paid</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Operations</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    for ($x = 0; $x < count($pbill); $x++) {
                                        $bill = $pbill[$x][1];
                                        $tr = "<tr>";
                                        for ($i = 0; $i < count($bill); $i++) {
                                            $tr .= "<td>" . $bill[$i] . "</td>";
                                        }
                                        $tr .= "<td>";
                                        if ($_SESSION['right'] == 2) {
                                            $tr .= "<a title='Edit Request' data-pid='" . $patient['id'] . "' data-pbill='" . $pbill[$x][0] . "' class='btn
										   btn-primary editreq'> <i class='fa fa-edit'></i></a>";
                                        }
                                        if ($_SESSION['right'] >= 3) {
                                            $tr .= "<a title='Edit' href='edit_billing.php?pid=" . $_REQUEST['pid'] . "&bid=" . $pbill[$x][0] . "' 
										  class='btn btn-primary'><i class='fa fa-edit'></i></a>";
                                        }
                                        $tr .= "
									<a title='Print' href='receipt.php?pid=" . $_REQUEST['pid'] . "&bill=" . $pbill[$x][0] . "' 
										  class='btn btn-primary print'> <i class='fa fa-print'></i></a>";
                                        $tr .= "</td></tr>";
                                        echo $tr;
                                    }

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
                <?php
            }
            ?>
            <!-- /.row -->
            

        </div>

    <div class="modal fade " id="histmodal" role="dialog">
        <div class="modal-dialog modal-lg ">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Patient History</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="post">
                        <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>" />
                        <div class="form-group">

                            <div class="col-md-12 ">
                                <textarea class="form-control" rows="20" name="history"><?php echo helperClass::getPatientHistory($_REQUEST['pid']) ?></textarea>
                                <span class="help-block" style="margin-bottom: 0px; padding-bottom: 0px">Any changes made to patient history should be <b>saved</b> to prevent loss of changes after patient file reload</span>
                            </div>

                        </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="submit" value="save"  >Save</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade " id="thistmodal" role="dialog">
        <div class="modal-dialog modal-lg ">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Treament History</h4>
                </div>
                <div class="modal-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                        <tr>
                            <th >Date Time</th>
                            <th >Note</th>
                            <th >Nurse</th>
                        </tr>
                        </thead>
                        <tbody id="histCon">
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
        <!-- /#page-wrapper -->
<script type="text/javascript" async="async">
$(".editreq").on("click",function(e){
	$this = $(this);
	pid = $this.data("pid");
	pbill = $this.data("pbill")
	
	$.ajax({
		url:"script/ajax.php?state=editalert&pid="+pid+"&pbill="+pbill,
		dataType:"json",
		success: function(e){
			if(e == true){
				location = location.origin+location.pathname+"?pid="+pid+"&success=Edit Request Sent Successfully"
			}
		}
	})
})
$("#group").on("change",function(e){
	$this = $(this)
	pid = $this.data('pid');
	gid = $this.val();
	$.ajax({
		url:"script/ajax.php?state=addgroup&pid="+pid+"&gid="+gid,
		dataType:"json",
		success: function(e){
			if(e == true && gid != 0){
				location = location.origin+location.pathname+"?pid="+pid+"&success=Patient assigned to group"
			}else if(e == true){
				location = location.origin+location.pathname+"?pid="+pid+"&success=Patient removed from group"
			}
		}
	})
})
$("#delPatient").on("click",function(e){
	$this = $(this);
	console.log("alert o")
	e.preventDefault();
	pid = $this.data('pid');
	if(confirm("Any record deleted cannot be recovered\nDo you want to delete this patient record?")){
		$.ajax({
		url:"script/ajax.php?state=delpatient&pid="+pid,
		dataType:"json",
		success: function(e){
			if(e == true){
				location = "patients.php?success=Patient deleted successfully"
			}
		}
	})
	}
})

$(document).on("click",".btnthist",function (e) {
    e.preventDefault();
    let $this = $(this);
    let tid = $this.data("tid");
    let tr = null;
    let record = null;
    let con = $("#histCon");
    con.empty();
    $("#histmodal .modal-title").text($this.data("tname")).append($("<small> Treatment History</small>"));
    var param = {
        generate : "treatmenthist",
        tid : tid
    };
    $.post("script/ajax.php",param,function (data) {
        for(var i = 0 ; i < data.length ; i++){
            tr = $("<tr>");
            record  = data[i];
            tr.append($("<td>").text(record['date']));
            tr.append($("<td>").text(record['note']));
            tr.append($("<td>").text(record['name']));
            con.append(tr);

        }
    },"json")
})
</script>

<?php
require_once "template/footer.php";
?>