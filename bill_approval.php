<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid']) || !isset($_REQUEST['date'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
}else{
	header("location:index.php");
}
$window = false;
if(isset($_REQUEST['submit']) && isset($_REQUEST['pid'])){
	if(isset($_REQUEST['service']) && isset($_REQUEST['amount'])){
		$services = $_REQUEST['service'] ;
		$amounts = $_REQUEST['amount'];
		$patientID = $_REQUEST['pid'];
		$paid = $_REQUEST['paid'];
		$date = $_REQUEST['date'];
		$updatehist = false;
		//$consul = ($_SESSION['right'] == 1) ? 0 : 1;

        $chck = $db->getOne("SELECT id FROM billing where date = ? and patient_id = ? ",array($date,$patientID));
        if($chck){
            $db->execute("UPDATE billing set totalserv = totalserv + ?,paid = paid + ?, consul = 1, user_id = ? where id = ?",
                array(count($services),$paid,$_SESSION['user_id'],$chck['id']));
            $bill_id = $chck['id'];
            $updatehist = true;
        }else{
            $bill_id = $db->executeGetId("INSERT INTO `billing` (`totalserv`, `patient_id`, `user_id`, `paid`,`date`,`consul`,`reception`)
											 VALUES (?, ?, ?, ?, ?, ?,?)",array(count($services),$patientID,$_SESSION['user_id'],$paid,$date,1,1));
        }
		/*if($consul == 1){
			$chck = $db->getOne("SELECT id FROM billing where date = ? and patient_id = ? ",array($date,$patientID));
			if($chck){
				$db->execute("UPDATE billing set totalserv = totalserv + ?,paid = paid + ?, consul = 1, user_id = ? where id = ?",
							array(count($services),$paid,$_SESSION['user_id'],$chck['id']));
				$bill_id = $chck['id'];
				$updatehist = true;
			}else{
				$bill_id = $db->executeGetId("INSERT INTO `billing` (`totalserv`, `patient_id`, `user_id`, `paid`,`date`,`consul`)
											 VALUES (?, ?, ?, ?, ?, ?)",array(count($services),$patientID,$_SESSION['user_id'],$paid,$date,$consul));
			}
			
		}else{
			$bill_id = $db->executeGetId("INSERT INTO `billing` (`totalserv`, `patient_id`, `user_id`, `paid`,`date`,`consul`,`reception`) 
							VALUES (?, ?, ?, ?, ?, ?, ?)",	array(count($services),$patientID,$_SESSION['user_id'],$paid,$date,$consul,1));
		}*/
		try{
		  for( $x = 0; $x < count($services) ; $x++){
			  $ok = $db->executeGetId("INSERT INTO `belling_services` (`billing_id`, `services`,`amount`) VALUES (?,?,?)",
			  							array($bill_id,$services[$x],$amounts[$x]));
			  //print $services[$x].' '.$amounts[$x].'<br />';
			}
			$db->execute("update billing set amount = amount + ? where id = ?",array(array_sum($amounts),$bill_id));
			$db->execute("update billing set completed = 1 where amount = paid and id = ?",array($bill_id));
			$db->execute("update billing set completed = 0 where amount > paid and id = ?",array($bill_id));
			$db->execute("update billing set completed = 2 where amount < paid and id = ?",array($bill_id));
			$histId = $db->executeGetId("insert into billing_hist (date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,bill_id) 
					( select date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,id from billing where id = ? )",array($bill_id));
			if($updatehist){
				$db->execute("UPDATE billing_hist set totalserv =  ?,paid =  ?, consul = 1, user_id = ?, amount = ? where id = ?",
							array(count($services),$paid,$_SESSION['user_id'],array_sum($amounts),$histId));
			}
			$db->execute("UPDATE ureport SET balance = 0 WHERE date = ? and user_id = ?",array($date, $_SESSION['user_id']));
			$db->execute("UPDATE `diagnosis` SET `payment`= '0' WHERE `patient_id` = ? AND `date` = ?",array($patientID,$_REQUEST['diagdate']));
			$db->execute("UPDATE `treatment` SET `payment`= '1' WHERE `patient_id` = ? AND `date` = ?",array($patientID,$_REQUEST['diagdate']));
		}catch(Exception $e){
			
			$db->execute("delete from belling_services where billing_id = ?",array($bill_id));
			$db->execute("delete from billing where id = ?",array($bill_id));
		}
		$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",
				  array("Creating Billing For Patient SID [".$patientID."] and receipt no [".$bill_id."]",$_SESSION['user_id'])); 
		
		$window = "receipt.php?bill=".$bill_id."&pid=".$patientID;
	}
	
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Patient Billing <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                <div class="row" style="margin-bottom:20px;">
                  
                  <div class="col-md-offset-1 col-md-2" ><h4>TOTAL (&#8358;) </h4></div><div class="col-md-6" ><h4 id="bill-total">0.0</h4></div>
                </div>
                <form class="form-horizontal" role="form" id="approval-form" method="post">
                <input type="hidden" name="diagdate" value="<?php echo $_REQUEST['date'] ?>" />
                <div class="form-group">
                <label class="col-md-1 control-label">Date</label>
                  <div class="col-md-2">
                  	<input class="form-control date" placeholder="YYYY-MM-DD"  required="required" name="date" autocomplete="off"/>
                  </div>
                <label class="col-md-2 control-label">Amount Paid</label>
                  
                  <div class="col-md-2">
                  	<input class="form-control" placeholder="0.00" dir="rtl" required="required" autocomplete="off" name="paid" onkeypress="return isNumber(event)"/>
                  </div>
                </div>
                <?php
				$diagnosisArr = $db->getAll("select diagnose,id,result,lab from diagnosis  where patient_id = ? and date = ? and payment = 1 ", array($_REQUEST['pid'],$_REQUEST['date']));
				$treatmentArr = $db->getAll("SELECT name, t.id, m.selling_price, t.quantity from medicine m inner join treatment t on t.medicine_id = m.id   
										where t.date = ? and t.patient_id = ? and t.payment = 0 ",
								array($_REQUEST['date'],$_REQUEST['pid']));
				
				//$result = array_merge($result1,$result2);
			

				foreach($diagnosisArr as $diagnose){
				    $amount = null;
				    if($diagnose['lab'] == 1){
				        $labTest = $db->getOne("select amount from lab_test where test = ?",array($diagnose['diagnose']));
				        $amount = $labTest['amount'];
                    }
					echo '<div class="form-group">';
					echo '<div class="col-md-offset-1 col-md-4">
                    <select class="form-control"  name="service[]"  >';
                    echo '<option value="null">Please Select...</option>';
                    $services = $db->getAll("SELECT id, services FROM services where hide = '0' and access_right < ? or access_right = ? ",
                        array($_SESSION['right'],$_SESSION['right']));
                    foreach($services as $service){
                        print '<option value="'.$service['id'].'">'.$service['services'].'</option>';
                    }
                    echo '</select>
                    </div>';
					echo'
					<div class="col-md-2">
                  	<input class="form-control approval-amount" name="amount[]" placeholder="Service amount" dir="rtl" 
						onkeypress="return isNumber(event)" value="'.$amount.'" />
                    </div>
                    <label class="control-label">'.$diagnose['diagnose'].' </label>
                    </div>';
					
				}
                foreach($treatmentArr as $treatment){
                    $amount = $treatment['selling_price'] * $treatment['quantity'];
                    echo '<div class="form-group">';
                    echo '<div class="col-md-offset-1 col-md-4">
                    <select class="form-control"  name="service[]"  >';
                    echo '<option value="null">Please Select...</option>';
                    $services = $db->getAll("SELECT id, services FROM services where hide = '0' and access_right < ? or access_right = ? ",
                        array($_SESSION['right'],$_SESSION['right']));
                    foreach($services as $service){
                        print '<option value="'.$service['id'].'">'.$service['services'].'</option>';
                    }
                    echo '</select>
                    </div>';
                    echo'
					<div class="col-md-2">
                  	<input class="form-control approval-amount" name="amount[]" placeholder="Service amount" dir="rtl" 
						onkeypress="return isNumber(event)" value="'.$amount.'" />
                    </div>
                    <label class="control-label">'.$treatment['name'].' </label>
                    
                    </div>';

                }
				?>
                
                
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
                
                
                <div class="form-group">
                 
                  <div class="col-md-offset-1 col-md-2">
                    <button type="submit" class="btn btn-block btn-primary " name="submit" value="submit" ><b>print</b></button>
                  </div>
                  <div class="col-md-2">
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
if(isset($window) && !empty($window) && $_REQUEST['submit'] == 'submit'){
	print  "<script>";
	print "window.open('".$window."','_blank','width=233,height=500');";
	
	//print "setTimeout(' window.location.href =".' "billing_view.php"'."; ',1000)";
	print "</script>";
	print $window;
}
require_once "template/footer.php";
?>
