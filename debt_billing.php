<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid']) && isset($_REQUEST['bid'])){
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
		$consul = ($_SESSION['right'] == 1) ? 0 : 1;
		$reception = ($consul == 0)? 1 : 0;
		$amtpaid =  $_REQUEST['amtpaid'] + $paid;
		$charge = $_REQUEST['charge'] - $_REQUEST['amtpaid'];
		$dbill = $_REQUEST['dbill'];
		
		$bill_id = $db->executeGetId("INSERT INTO `billing` (`totalserv`, `patient_id`, `user_id`, `paid`,`date`,`consul`,`reception`,`debt`) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?)",array(count($services),$patientID,$_SESSION['user_id'],$paid,$date,$consul,$reception,1));
		$db->execute("update billing set debt = 1 where id = ?",array($_REQUEST['bid']));
		if($dbill == 0){
			$db->execute("update billing set dbill = ? where id = ?",array($_REQUEST['bid'],$bill_id));
		}else{
			$db->execute("update billing set dbill = ? where id = ?",array($dbill,$bill_id));
		}
		try{
		 /* for( $x = 0; $x < count($services) ; $x++){
			  $ok = $db->executeGetId("INSERT INTO `belling_services` (`billing_id`, `services`,`amount`) VALUES (?,?,?)",
			  							array($bill_id,$services[$x],$amounts[$x]));
			  //print $services[$x].' '.$amounts[$x].'<br />';
			}*/
			$db->execute("update billing set amount = amount + ? where id = ?",array(0,$bill_id));
			$db->execute("update billing set completed = 1 where paid = ? and id = ?",array($charge,$bill_id));
			$db->execute("update billing set completed = 0 where paid < ? and id = ?",array($charge,$bill_id));
			$db->execute("update billing set completed = 2 where paid > ? and id = ?",array($charge,$bill_id));
			$histId = $db->executeGetId("insert into billing_hist (date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,bill_id) 
					( select date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,id from billing where id = ? )",array($bill_id));	
					
			//
			$db->execute("DELETE FROM billing_hist WHERE bill_id = ?",array($_REQUEST['bid']));
			$stat = $db->getOne("select completed,dbill from billing where id = ?",array($bill_id));
			$db->execute("update billing set completed = ? where id = ? or dbill = ? ",array($stat['completed'],$stat['dbill'],$stat['dbill']));
			/*$db->execute("update billing set completed = 1 where amount = ? and id = ?",array($amtpaid,$_REQUEST['bid']));
			$db->execute("update billing set completed = 0 where amount > ? and id = ?",array($amtpaid,$_REQUEST['bid']));
			$db->execute("update billing set completed = 2 where amount < ? and id = ?",array($amtpaid,$_REQUEST['bid']));*/
			
			//$db->execute("update billing set paid = paid + ? where id = ?",array($paid,$_REQUEST['bid']));
			$histId = $db->executeGetId("insert into billing_hist (date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,bill_id) 
			( select date,totalserv,patient_id,user_id,amount,paid,completed,consul,reception,id from billing where id = ? )",array($_REQUEST['bid']));
			$db->execute("UPDATE ureport SET balance = 0 WHERE date = ? and user_id = ?",array($date, $_SESSION['user_id']));
		}catch(Exception $e){
			$db->execute("delete from billing_services where billing_id = ?",array($bill_id));
			$db->execute("delete from billing where id = ?",array($bill_id));
		}
		$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",
				  array("Paying debt for patient SID [".$patientID."] and receipt no [".$bill_id."]",$_SESSION['user_id'])); 
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
                        <h1 class="page-header">Pay Debt <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                <div class="row" style="margin-bottom:20px;">
                  <?php 
				   $bill = $db->getOne("select * from  billing where patient_id = ? and id = ? ",array($_REQUEST['pid'],$_REQUEST['bid']));
				   $dbill = $bill['dbill'];
				   if($dbill == 0 ){
					   $dbill = $_REQUEST['bid'];
				   }
				   $bill = $db->getOne("select sum(amount) as amount, sum(paid) as paid from billing where id = ? or dbill = ?",array($dbill,$dbill));
				  ?>
                  <div class="col-md-offset-1 col-md-3" ><h4>AMOUNT CHARGE (&#8358;) </h4></div><div class="col-md-8" >
                  <h4 id="bill-total"><?php echo $bill['amount'] ?></h4></div>
                  <div class="col-md-offset-1 col-md-3"><h4>INITIAL AMOUNT PAID (&#8358;) </h4></div><div class="col-md-8" >
                  <h4 id="bill-total"><?php echo $bill['paid'] ?></h4></div>
                  <div class="col-md-offset-1 col-md-3"><h4>BALANCE (&#8358;) </h4></div><div class="col-md-8" >
                  <h4 id="bill-total"><?php echo $bill['amount'] - $bill['paid'] ?></h4></div>
                </div>
                
                <form class="form-horizontal" role="form" id="billing-list" method="post">
           		<input type="hidden" name="charge" value="<?php echo $bill['amount'] ?>"  />
                <input type="hidden" name="amtpaid" value="<?php echo $bill['paid'] ?>"  />
                <input type="hidden" name="dbill" value="<?php echo $dbill ?>"  />
                <div class="form-group">
                <label class="col-md-1 control-label">Date</label>
                  
                  <div class="col-md-2">
                  	<input class="form-control date" onkeypress="return false"  required="required" name="date" placeholder="yyyy-mm-dd" />
                  </div>
                <label class="col-md-2 control-label">Amount Paid</label>
                  
                  <div class="col-md-2">
                  	<input class="form-control" placeholder="0.00" dir="rtl" required="required" name="paid" onkeypress="return isNumber(event)"/>
                  </div>
                </div>
                
                
                  <?php
				  $billservs = $db->getAll("select services as servid, amount from belling_services where billing_id = ?",array($dbill));
				  foreach ($billservs as $serv){
					  $amount = $serv['amount'];
					  $servid = $serv['servid'];
					  print '<div class="form-group">
					  		<div class=" col-md-4 col-md-offset-1">
								<select class="form-control" name="service[]">';
								$services = $db->getAll("SELECT id, services FROM services where access_right < ? or access_right = ?",
												array($_SESSION['right'],$_SESSION['right']));
						foreach($services as $service){
							if($servid == $service['id']){
								print '<option value="'.$service['id'].'" selected="selected">'.$service['services'].'</option>';
							}
						}
								
					  print	'</select>
							  </div>
							  <div class="col-md-2">
			<input class="form-control" onkeypress="return false" placeholder="0.00" dir="rtl" value="'.$amount.'" required="required" name="amount[]"/>
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
                    <button type="submit" class="btn btn-block btn-primary " name="submit" value="submit" ><b>Print</b></button>
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
