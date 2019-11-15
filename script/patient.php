<?php
require_once "dbHandler.php";
class patient{
	private $patientId = NULL;
	private $db;
	private $default;
	public function __construct($pid){
		$this->patientId = $pid;
		$this->db = new dbHandler();
	}
	public function info(){
		$result = $this->db->getOne("select * from patient where id = ?",array($this->patientId));
		return $result;
	}
	//set the default bill date to today - 2016-10-11 - format
	public function receipt($billid){
		$patientinfo = $this->info();
		$result = $this->db->getOne("select * from billing where patient_id = ? and id = ?",array($this->patientId, $billid));
		$pname = $patientinfo['name'];
		$rno = $billid;
		$id = $patientinfo['hospital_no'];
		$date = $result['date'];
		$services = patient::billservices($billid,$result['dbill'],$result['paid']);	
		$total = $this->charge($result['dbill'],$result['amount'],$result['id']);
		$balance = $this->balance($result['dbill'],$result['amount'],$result['paid'],$result['id']);
		
		$complete = ($result['completed'] == 1 ) ? "Completed" : "Owning";
		$issuer = $this->issuer($result['user_id']);
		$issuer = $issuer['name'].' ( '.$issuer['access'].' )';
		
		$receipt = compact('pname','rno','id','date','services','total','balance','complete','issuer');
		return $receipt;
		
	}
	private function balance($dbill,$amt,$amtpaid,$bid){
		if($dbill != 0){
			$balance = $this->db->getOne("select (sum(amount) - sum(paid)) as bal from billing where (id = ? or dbill = ?) and id <= ?",
											array($dbill,$dbill,$bid));
			return $balance['bal'];
		}else{
			$balance = $this->db->getOne("select (sum(amount) - sum(paid)) as bal from billing where id = ? or dbill = ?",
											array($bid,$bid));
			return $balance['bal'];
		}
		
	}
	private function charge($dbill,$amt,$bid){
		if($dbill != 0){
			$balance = $this->db->getOne("select (sum(amount) - sum(paid)) as bal from billing where (id = ? or dbill = ?) and id < ?",
											array($dbill,$dbill,$bid));
			
			return $balance['bal'] ;
		}else{
			return $amt;
		}
	}
	private function issuer($userid){
		$user = $this->db->getOne("SELECT name,access FROM user u inner join access_right ar on u.access_right = ar.level where u.id = ?",
									array($userid));
		return $user;
	}
	public function billservices($billid, $dbill,$amt){
		if($dbill == 0){
			$result = $this->db->getAll("SELECT s.services as service,bs.amount FROM belling_services bs inner join services s on bs.services = s.id  
										where billing_id = ?",array($billid));
			return $result;
		}else{
			$amount = $this->charge($dbill,$amt,$billid);
			$result = array(array('service' => 'Debt','amount' => $amount));
			 
			return $result;
		}
		
	}
	public  function service($servID){
		$service = $this->db->getOne("select services from services where id = ?",array($servID));
		return $service['services'];
	}
	public static function chckdebt($db, $pid, $bid){
		$chck = $db->getOne("select id from billing where patient_id = ? and id = ? and debt = 1",array($pid,$bid));
		return $chck;
	}
	public function is_admitted(){
	    $admitted = $this->db->getOne("select id from patient where ward is not null and id = ?",array($this->patientId));
	    if($admitted != null){
	        return true;
        }
	    return false;
    }
	
}
?>