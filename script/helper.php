<?php
class helperClass {
	public static function myWard($user_id){
		global $db;
		$ward = $db->getOne("select id from ward where user_id = ? and state = 0", array($user_id));
		if(!$ward){
			return false;
		}
		return $ward['id'];
	}
	public static function setEmergency($pid){
		global $db;
		$pid = $pid;
		$bed = $db->getOne("select b.id from bed b inner join ward w on ward_id = w.id where w.user_id = ? and b.taken = 0",
							array($_SESSION['user_id']));
		if($bed){
			$today = date("Y-m-d h:i:s");
			$ward = $db->getOne("select id from ward where user_id = ?",array($_SESSION['user_id']));
			$db->execute("UPDATE patient set ward = ? WHERE id = ? ",array($ward['id'], $pid ));
			$db->execute("update bed set patient_id = ? , date = ?, taken = 0 where patient_id = ?",array(NULL,NULL, $pid));
			$db->execute("update bed set patient_id = ? , date = ?, taken = 1 where id = ?",array($pid,$today, $bed['id']));
			$state = $db->getOne("select b.id from bed b inner join ward w on ward_id = w.id where w.user_id = ? and b.taken = 0",
									array($_SESSION['user_id']));
			if(!$state){
				$db->execute("update ward set state = 1 where user_id = ?",array($_SESSION['user_id']));
			}
			return true;
		}
	}
	public static function hmoBalance($hmo){
		global $db;
		$charge = $db->getOne("select sum(charge) as charge from hmo_billing where hmo_id = ?",array($hmo));
		$paid  = $db->getOne("select sum(amount) as amount from hmo_payment where hmo_id = ?",array($hmo));
		$balance = $charge['charge'] - $paid['amount'];
		return $balance;
	}
	public static function is_admitted($pid){
	    global  $db;
	    $patient = $db->getOne("select ward from patient where id = ?",array($pid));
	    if($patient['ward'] == null){
	        return false;
        }
	    return true;
    }
    public static function debtOwed($pid){
	    global $db;
	    $summation = $db->getOne("select sum(amount) as amount, sum(paid) as paid from billing where patient_id = ?",array($pid));
	    $owed = $summation['amount'] - $summation['paid'];
	    return $owed;
    }
    public  static function getPatientHistory($pid){
	    global $db;
	    $hist = $db->getOne("select history from patient_history where patient_id = ? ",array($pid));
	    if(!$hist){
	        $db->execute("insert into patient_history (patient_id) values (?)",array($pid));
	        return null;
        }
	    return $hist['history'];
    }
    public static function getUser($user_id,$column = null){
	    /*if($dbObj == null){
            global $db;
            $dbObj = $db;
        }*/
	    global  $db;

	    $user = $db->getOne("select username,name,access from user u inner join  access_right a on u.access_right = a.level where u.id = ?",array($user_id));
	    if($column == null){
	        return $user;
        }
	    return $user[$column];
    }
	
}