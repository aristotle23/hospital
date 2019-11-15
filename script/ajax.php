<?php
require_once "dbHandler.php";
require_once "functions.php";
date_default_timezone_set("Africa/Lagos");
session_start();
$db = new dbHandler();
if(isset($_REQUEST['modal']) ){
	if($_REQUEST['modal'] == 'addservicetitle'){
		$title = $_REQUEST['title'];
		$chck = $db->getOne("select id from service_title where title = ?",array($title));
		if($chck){
			echo json_encode("exist");
		}else{
			$success = $db->executeGetId("insert into service_title (title) values (?)",array($title));
			if($success){
				echo json_encode(true);
			}else{
				echo json_encode(false);
			}
		}
	}else if($_REQUEST['modal'] == 'addservicetype'){
		$title = $_REQUEST['title'];
		$type = $_REQUEST['type'];
		$chck = $db->getOne("select id from service_type where service_title_id = ? and type = ?",array($title,$type));
		if($chck){
			echo json_encode("exist");
		}else{
			$success = $db->executeGetId("insert into service_type (service_title_id,type) values (?,?)",array($title,$type));
			if($success){
				echo json_encode(true);
			}else{
				echo json_encode(false);
			}
		}
	}else if($_REQUEST['modal'] == 'newuser'){
		$name = $_REQUEST['name'];
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
		$question = $_REQUEST['question'];
		$ans = $_REQUEST['ans'];
		$right = $_REQUEST['right'];
		$chck = $db->getOne("select username from user where username = ?",array($username));
		if($chck){
			echo json_encode("exist");
		}else{
			$success = $db->executeGetId("INSERT INTO `user` (`name`,`username`, `password`, `access_right`, `question`, `ans`) 
										VALUES (?,?, md5(?), ?, ?, ?);",array($name,$username,$password,$right,$question,$ans));
			if($success){
				echo json_encode(true);
			}else{
				echo json_encode(false);
			}
		}
	}else if($_REQUEST['modal'] == 'edituser'){
		$oldpass = $_REQUEST['oldpass'];
		$newpass = $_REQUEST['newpass'];
		$chck = $db->getOne("select id from user where id = ? and password = md5(?)",array($_SESSION['user_id'],$oldpass));
		if(!$chck){
			echo json_encode("exist");
		}else{
			$db->execute("update user set password = md5(?) where password = md5(?) and id = ?",array($newpass,$oldpass,$_SESSION['user_id']));
			echo json_encode(true);
		}
		
	}
}else if(isset($_REQUEST['form'])){
	if(strtolower($_REQUEST['form']) == 'addservices'){
		$service = $_REQUEST['service'];
		$access = $_REQUEST['access'];
		if($_REQUEST['type'] != 'null' && $_REQUEST['title'] != 'null'){
			$title = $_REQUEST['title'];
			$type = $_REQUEST['type'];
			$chck = $db->getOne("select id from services where service_title_id = ? and service_type_id = ? and services = ?",
								array($title,$type,$service));
			if($chck){
				echo json_encode("exist");
			}else{
				$success = $db->executeGetId("INSERT INTO `services` (`service_title_id`, `service_type_id`, `services`, `access_right`)
											 VALUES (?, ?, ?, ?)",array($title,$type,$service,$access));
				if($success){
					echo json_encode(true);
				}else{
					echo json_encode(false);
				}											 					
			}
		}else{
			$chck = $db->getOne("select id from services where services = ? ",array($service));
			$tchck = $db->getOne("select id from service_title where title = ?",array($service)); 
			if($chck || $tchck){
				echo json_encode("exist");
			}else{
				$tid = $db->executeGetId("insert into service_title (title) values (?)",array($service));
				if($tid){
					$success = $db->executeGetId("INSERT INTO `services` (`service_title_id`, `services`, `access_right`) VALUES (?, ?, ?)",
													array($tid,$service,$access));
					if($success){
						echo json_encode(true);
					}else{
						$db->execute("delete from service_title where id = ?",array($tid));
						echo json_encode(false);
					}
				}else{
					echo json_encode(false);
				}
			}
		}
	}else if (strtolower($_REQUEST['form']) == 'patient'){
		$patients = $db->getAll("select * from patient");
		$trs = "";
		foreach ($patients as $patient){
			$name = $patient['name'];
			$phone = $patient['telephone'];
			$id = $patient['hospital_no'];
			$sex = $patient['sex'];
			$marital = $patient['marital_status'];
			$regdate = date_create($patient['date']);
			$regdate = date_format($regdate,"Y-m-d");
			$dob = date_create($patient['dob']);
			$dob = date_format($dob,"Y-m-d");
			
			$trs .= "<tr><td >".$id."</td><td>".$name."</td><td >".$phone."</td><td >".$dob."</td><td >".$sex."</td><td >"
			.$marital."</td><td>".$regdate."</td><td>Tasks</td></tr>";
		}
		echo json_encode($trs);
	}else if(strtolower($_REQUEST['form']) == 'rprofit'){
		$year = $_REQUEST['year'];
		$month = array('01','02','03','04','05','06','07','08','09','10','11','12');
		$data = array();
		for($x = 0 ; $x < count($month); $x++){
			$from = $year.'-'.$month[$x].'-00';
			$to =  $year.'-'.$month[$x].'-31';
			$bill = $db->getOne("select sum(paid) as sum from billing where date between ? and ?",array($from,$to));
			$bill = ($bill) ? $bill['sum'] : 0;
			$income = $db->getOne("select sum(amount) as sum from income where date between ? and ?",array($from,$to));
			$income = ($income) ? $income['sum'] : 0;
			$bill += $income;
			$expense = $db->getOne("select sum(amount) as sum from expenditure where hide = 0 and date between ? and ?",array($from,$to));
			$expense = ($expense) ? $expense['sum'] : 0;
			$figure = $bill - $expense;
			array_push($data,$figure);
		}
		echo json_encode($data);
	}
}else if(isset($_REQUEST['state'])){
	if($_REQUEST['state'] == 'getservtype'){
		$title = $_REQUEST['title'];
		$types = $db->getAll("select id, type from service_type where service_title_id = ? ",array($title));
		echo json_encode($types);
	}else if($_REQUEST['state'] == 'delete'){
		$tbl = $_REQUEST['tbl'];
		$tblid = $_REQUEST['tblid'];
		$sql = "UPDATE ".$tbl." SET `hide`='1' WHERE `id`= ?";
		$db->execute($sql,array($tblid));
		$db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);",array($tbl." Deleted with SID [".$tblid."]",$_SESSION['user_id']));
		echo json_encode(true);

	}else if($_REQUEST['state'] == 'delundo'){
		$tbl = $_REQUEST['tbl'];
		$tblid = $_REQUEST['tblid'];
		$sql = "UPDATE ".$tbl." SET `hide`='0' WHERE `id`= ?";
		$db->execute($sql,array($tblid));
		echo json_encode(true);

	}else if($_REQUEST['state'] == 'editalert'){
		$bill = $db->executeGetId("INSERT INTO `edit_alert` (`patient_id`,  `billing_id`) VALUES (?, ?);",array($_REQUEST['pid'],$_REQUEST['pbill']));
		if($bill){
			echo json_encode(true);
		}
	}else if($_REQUEST['state'] == 'enotification'){
		$aid = $_REQUEST['aid'];
		$alert = $db->getOne("SELECT a.date,name,billing_id,patient_id,hospital_no,a.id FROM edit_alert a inner join patient p on p.id = patient_id 
								where status = 0 and ping = 0 and a.id > ?  order by a.date asc ",array($aid));
		if($alert){
			$li = "";
			$date = $alert['date'];
			$date = timeAgo($date);
			$li .= '<li data-id="'.$alert['id'].'"><a class="bnotify" data-id="'.$alert['id'].'" 
						href="edit_billing.php?pid='.$alert['patient_id'].'&bid='.$alert['billing_id'].'" >
				  <div>
					  <i class="fa fa-exchange fa-fw"></i> Bill edit req. for [ '.$alert['hospital_no'].' ]
					  <span class="pull-right text-muted small">'.$date.'</span>
				  </div>
					</a>
				</li>
				<li class="divider"></li>';
			echo json_encode($li);
		}else{
			echo json_encode("");
		}
	}else if ($_REQUEST['state'] == 'updatenotify'){
		$id = $_REQUEST['id'];
		$db->execute("UPDATE `edit_alert` SET `status`= 1, `ping` = 1 WHERE `id`= ?",array($id));
		echo json_encode(true);
	}else if($_REQUEST['state'] == 'updatedashboard'){
		$today = date("Y-m-d");
		$intakep = $db->getOne("select count(*) as count FROM billing where reception = 1 and date = ?",array($today));
		$billed = $db->getOne("select count(*) as count from billing where date = ?",array($today));
		$register = $db->getOne("SELECT count(*) as count FROM patient where date = ?",array($today));
		$amtpaid = $db->getOne("select sum(paid) as sum from billing where date = ?",array($today));
		$expense = $db->getOne("select sum(amount) as amt from expenditure where date = ?",array($today));
		$incomeMan = $db->getOne("select sum(amount) as amt from income where date = ? and hide = 0",array($today));

		$expense = ($expense['amt'] == "" || $expense['amt'] == NULL) ? 0 : $expense['amt'];
		$incomeMan = ($incomeMan['amt'] == "" || $incomeMan['amt'] == NULL) ? 0 : $incomeMan['amt'];
		$ttlincome = ($amtpaid['sum'] + $incomeMan) - $expense;
		//echo json_encode(array($intakep['count'],$billed['count'],$register['count'],$amtpaid['sum'],$expense,$incomeMan,$ttlincome));
        echo json_encode(array($intakep['count'],$billed['count'],$register['count'],$amtpaid['sum'],$expense,$incomeMan,$ttlincome));
	}else if($_REQUEST['state'] == 'seen'){
		$id = $_REQUEST['id'];
		$db->execute("UPDATE `appointment` SET `seen`='1' WHERE `id`= ?",array($id));
		echo json_encode(true);
	}else if($_REQUEST['state'] == 'appnotice'){
		$today = date('Y-m-d',strtotime('today'));
		$tomorrow = date('Y-m-d',strtotime('+1 day'));
		$apps = $db->getAll("SELECT a.id,hospital_no FROM appointment a inner join patient p on a.patient_id = p.id  where seen = 0 and ping = 0 
							and a.date between ? and ? ",array($today,$tomorrow));
		$li = '';
		foreach($apps as $app){
			$li .= '<li><a href="appointment_view.php?from='.$today.'&to='.$tomorrow.'" class="appnotice" data-id="'.$app['id'].'">
			  <div>
				  <i class="fa fa-upload fa-fw"></i> Medical appointment for '.$app['hospital_no'].' 
				  
			  </div>
             </a></li>
			<li class="divider"></li>';
		}
		echo json_encode($li);
	}else if($_REQUEST['state'] == 'appupdate'){
		$id = $_REQUEST['id'];
		$db->execute("UPDATE `appointment` SET `ping`='1' WHERE `id`= ?",array($id));
		echo json_encode(true);
	}else if($_REQUEST['state'] == 'baluser'){
		$rid = $_REQUEST['rid'];
		$db->execute("UPDATE ureport SET balance = 1 WHERE id = ?",array($rid));
		echo json_encode(true);
	}else if($_REQUEST['state'] == 'balundouser'){
		$rid = $_REQUEST['rid'];
		$db->execute("UPDATE ureport SET balance = 0 WHERE id = ?",array($rid));
		echo json_encode(true);
	}else if($_REQUEST['state'] == 'assignstaff'){
		$uid = $_REQUEST['uid'];
		$pid = $_REQUEST['pid'];
		$db->execute("UPDATE patient set user_id = ? WHERE id = ?",array($uid, $pid));
		echo json_encode(true);
	}else if($_REQUEST['state'] == 'admit'){
		$pid = $_REQUEST['pid'];
		$ward = $_REQUEST['ward'];
		$db->execute("UPDATE patient set ward = ? WHERE id = ?",array($ward, $pid));
		echo json_encode(true);
	}
	else if($_REQUEST['state'] == 'bed'){
		$pid = $_REQUEST['pid'];
		$bed = $_REQUEST['bed'];
		$today = date("Y-m-d h:i:s");
		$ward = $db->getOne("select id from ward where user_id = ?",array($_SESSION['user_id']));
		$db->execute("UPDATE patient set ward = ? WHERE id = ? ",array($ward['id'], $pid));
		$db->execute("update bed set patient_id = ? , date = ?, taken = 0 where patient_id = ?",array(NULL,NULL, $pid));
		$db->execute("update bed set patient_id = ? , date = ?, taken = 1 where id = ?",array($pid,$today, $bed));
		$state = $db->getOnce("select b.id from bed b inner join ward w on ward_id = w.id where w.user_id = ? and b.taken = 0",
									array($_SESSION['user_id']));
		if(!$state){
			$db->execute("update ward set state = 1 where user_id = ?",array($_SESSION['user_id']));
		}
		echo json_encode($today);
	}else if($_REQUEST['state'] == 'addgroup'){
		$pid = $_REQUEST['pid'];
		$group = $_REQUEST['gid'];
		if($group == 0){
			$db->execute("update patient set group_id = NULL where id = ?",array($pid));
		}else{
			$db->execute("update patient set group_id = ? where id = ? ",array($group,$pid));
		}
		echo json_encode(true);
	}else if($_REQUEST['state'] == "delpatient"){
		$pid = $_REQUEST['pid'];
		$db->execute("delete from bed_hist where patient_id = ?;
		delete from billing where patient_id = ?;
		delete from billing_hist where patient_id = ?;
		delete from diagnosis where patient_id = ?;
		delete from edit_alert where patient_id = ?;
		delete from patient where id = ?;
		delete from treatment where patient_id = ?;
		delete from appointment where patient_id = ?;
		delete from vitals where patient_id = ?;
		delete from appointment_alert where patient_id = ?;
		update bed set patient_id = NULL where patient_id = ?;",array($pid,$pid,$pid,$pid,$pid,$pid,$pid,$pid,$pid,$pid,$pid));
		echo json_encode(true);
	}
	
	
}else if(isset($_REQUEST['generate'])){
	if($_REQUEST['generate'] == 'patient'){
		$patients = $db->getAll("select * from patient order by date desc limit ".$_REQUEST['start'].",".$_REQUEST['count']);
		echo json_encode($patients);
	}elseif ($_REQUEST['generate'] == "medhist"){
	    $medId = $_REQUEST['medid'];
        $supplies = $db->getAll("SELECT s.date, m.name, su.name as supplier, s.quantity FROM supply s inner join medicine m on s.item_id = m.id 
    inner join supplier su on s.supplier_id = su.id  where m.id = ? order by s.date desc ",array($medId));

	    echo json_encode($supplies);
    }elseif ($_REQUEST['generate'] == "treatmenthist"){
	    $treatmentId = $_REQUEST['tid'];
	    $treatments = $db->getAll("SELECT date,note, name FROM nursing n inner join user u on n.user_id = u.id where treatment_id = ? order by date desc;",
            array($treatmentId));
	    echo json_encode($treatments);
    }elseif ($_REQUEST['generate'] == "prehist"){
	    $data = array();
	    $date = $_REQUEST['date'];
	    $pid = $_REQUEST['pid'];
        $result = $db->getAll("select name, dosage,routine,t.quantity, t.id, medicine_id from treatment t inner join medicine m on t.medicine_id = m.id where patient_id = ? and date = ? and sign = 1 ",
            array($pid,$date));
        foreach ($result as $prescription){
            $tid = $prescription['id'];
            $refill = $db->getOne("select count(id) from treatment_refill where treatment_id = ?",array($tid));
            if($refill){
                $refill = true;
            }else{
                $refill = false;
            }
            $ttlsigned = $db->getOne("select count(id) as ttl from nursing where treatment_id = ?",array($tid));
            $ttlsigned = $ttlsigned['ttl'];
            $quantity = $prescription['quantity'];
            $dosage = $prescription['dosage'];
            $preTaken = $dosage * $ttlsigned;
            $name = $prescription['name'];
            $medicineId = $prescription['medicine_id'];
            $return = $quantity - $preTaken;

            array_push($data,array("name"=>$name,"quantity"=>$quantity,"used"=>$preTaken,"mid"=>$medicineId,"return"=>$return,"tid"=>$tid,"refill"=>$refill));
        }
        echo json_encode($data);
    }elseif ($_REQUEST["generate"] == "refillhist"){
	    $tid = $_REQUEST['tid'];
	    $result = $db->getAll("SELECT date,quantity,name FROM treatment_refill tf inner join user u on tf.user_id = u.id where treatment_id = ? order by date desc ",array($tid));
	    echo json_encode($result);
    }

}

?>