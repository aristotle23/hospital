<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
}else{
	$patient = NULL;
}
$window = false;
if(isset($_REQUEST['submit'])  && isset($_REQUEST['pid'])){
	if(isset($_REQUEST['service']) && isset($_REQUEST['amount'])){
		$services = $_REQUEST['service'] ;
		$amounts = $_REQUEST['amount'];
		$patientID = $_REQUEST['pid'];
		$paid = $_REQUEST['paid'];
		$date = $_REQUEST['date'];
		$bill_id = $_REQUEST['bid'];
		$db->execute("UPDATE `billing` SET `totalserv` = ?, `paid`= ?  WHERE `id`= ? and `patient_id` = ?",
					array(count($services),$paid,$_REQUEST['bid'],$patientID));
		$db->execute("DELETE FROM `belling_services` WHERE `billing_id`= ?",array($bill_id));
		$db->execute("DELETE FROM billing_hist WHERE bill_id = ?",array($bill_id));
		try{
		  for( $x = 0; $x < count($services) ; $x++){
			  $ok = $db->executeGetId("INSERT INTO `belling_services` (`billing_id`, `services`,`amount`) VALUES (?,?,?)",
			  							array($bill_id,$services[$x],$amounts[$x]));
			  //print $services[$x].' '.$amounts[$x].'<br />';
			}
			$db->execute("update billing set amount = ? where id = ?",array(array_sum($amounts),$bill_id));
			$db->execute("update billing set completed = 1 where amount = paid and id = ?",array($bill_id));
			$db->execute("update billing set completed = 0 where amount > paid and id = ?",array($bill_id));
			$db->execute("update billing set completed = 2 where amount < paid and id = ?",array($bill_id));
			$histId = $db->executeGetId("insert into billing_hist (date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,bill_id) 
					( select date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,id from billing where id = ? )",array($bill_id));
		}catch(Exception $e){
			$db->execute("delete from billing_services where billing_id = ?",array($bill_id));
			$db->execute("delete from billing where id = ?",array($bill_id));
		}
		$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",
				  array("Editing Billing For Patient SID [".$patientID."]",$_SESSION['user_id'])); 
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
                        <h1 class="page-header">Edit Billing <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                <div class="row" style="margin-bottom:20px;">
                  <?php 
				   $bill = $db->getOne("select * from  billing where patient_id = ? and id = ?",array($_REQUEST['pid'],$_REQUEST['bid']));
				  ?>
                  <div class="col-md-offset-1 col-md-2" ><h4>TOTAL (&#8358;) </h4></div><div class="col-md-6" >
                  <h4 id="bill-total"><?php echo $bill['amount'] ?></h4></div>
                </div>
                <form class="form-horizontal" role="form" id="billing-list" method="post">
                
                <div class="form-group">
                <label class="col-md-1 control-label">Date</label>
                  
                  <div class="col-md-2">
                  	<input class="form-control" onkeypress="return false"  required="required" name="date" value="<?php echo $bill['date'] ?>"/>
                  </div>
                <label class="col-md-2 control-label">Amount Paid</label>
                  
                  <div class="col-md-2">
                  	<input class="form-control" placeholder="0.00" dir="rtl" required="required" name="paid"  value="<?php echo $bill['paid'] ?>"
                    onkeypress="return isNumber(event)"/>
                  </div>
                </div>
                <div class="form-group">
                 <div class="col-md-1">
                      <button class="btn btn-block btn-primary  billplus" ><i class="fa fa-plus"></i></button>
                  </div>
                  <div class="col-md-4">
                    <select class="form-control"  id="bill-service"  >
                    <option value="null">Please Select...</option>
                    <?php
						$services = $db->getAll("SELECT id, services FROM services where hide = '0' and access_right < ? or access_right = ? ",
												array($_SESSION['right'],$_SESSION['right']));
						foreach($services as $service){
							print '<option value="'.$service['id'].'">'.$service['services'].'</option>';
						}
					?>
                    </select>
                  </div>
                  <div class="col-md-2">
                  	<input class="form-control" id="bill-amount" placeholder="0.00" dir="rtl" onkeypress="return isNumber(event)" />
                  </div>
                  
                  	
                </div>
                
                  <?php
				  $billservs = $db->getAll("select services as servid, amount from belling_services where billing_id = ?",array($_REQUEST['bid']));
				  foreach ($billservs as $serv){
					  $amount = $serv['amount'];
					  $servid = $serv['servid'];
					  print '<div class="form-group">
					  		<div class="col-md-1">
                      		<button class="btn btn-block btn-primary billminus" ><i class="fa fa-minus"></i></button>
                  			</div>
					  		<div class=" col-md-4">
								<select class="form-control" name="service[]">';
								$services = $db->getAll("SELECT id, services FROM services where access_right < ? or access_right = ?",
												array($_SESSION['right'],$_SESSION['right']));
						foreach($services as $service){
							if($servid == $service['id']){
								print '<option value="'.$service['id'].'" selected="selected">'.$service['services'].'</option>';
							}else{
								print '<option value="'.$service['id'].'" >'.$service['services'].'</option>';
							}
						}
								
					  print	'</select>
							  </div>
							  <div class="col-md-2">
								<input class="form-control" placeholder="0.00" dir="rtl" value="'.$amount.'" required="required" name="amount[]"/>
							  </div>
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
                    <button type="submit" class="btn btn-block btn-primary " name="submit" value="submit" >Edit Bill</button>
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
if($window == true && strtolower($_REQUEST['submit']) == 'submit'){
	print  "<script>";
	print "window.open('".$window."','_blank','width=233px');";
	print "setTimeout(' window.location.href =".' "billing_view.php"'."; ',1000)";
	print "</script>";
	
}else if($window == true){
	print  "<script>";
	print "setTimeout(' window.location.href =".' "billing_view.php"'."; ',1000)";
	print "</script>";
}
require_once "template/footer.php";
?>
