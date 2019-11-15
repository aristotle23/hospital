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
		$services = patient::billservices($billid);
		$total = $result['amount'];
		$balance = $total - $result['paid'];
		$complete = ($result['completed'] == 1 ) ? "Completed" : "Owning";
		$issuer = $this->issuer($result['user_id']);
		$issuer = $issuer['name'].' ( '.$issuer['access'].' )';
		
		$receipt = compact('pname','rno','id','date','services','total','balance','complete','issuer');
		return $receipt;
		
	}
	private function issuer($userid){
		$user = $this->db->getOne("SELECT name,access FROM user u inner join access_right ar on u.access_right = ar.level where u.id = ?",
									array($userid));
		return $user;
	}
	public function billservices($billid){
		$result = $this->db->getAll("SELECT s.services as service,bs.amount FROM belling_services bs inner join services s on bs.services = s.id  
										where billing_id = ?",array($billid));
		return $result;
		
	}
	public  function service($servID){
		$service = $this->db->getOne("select services from services where id = ?",array($servID));
		return $service['services'];
	}
	public static function chckdebt($db, $pid, $bid){
		$chck = $db->getOne("select id from billing where patient_id = ? and id = ? and debt = 1",array($pid,$bid));
		return $chck;
	}
	
}
?>