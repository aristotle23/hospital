<?php
class billing{
	private $db;
	private $pid;
	public function __construct(){
		$this->db = new dbHandler();
	}
	public function getBilling(){
		$result = array();
		$billing = $this->db->getAll("select * from billing order by date asc");
		foreach ($billing as $bill){
			$service = "";
			if($bill['dbill'] == 0){
				$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
									where billing_id = ?",array($bill['id']));
				foreach($services as $key => $serv){
				  if($key == 0){
					  $service .= $serv['service'];
				  }else{
					  $service .= ", ".$serv['service'];
				  }
				}
			}else{
				$service = "Debt";
			}
			$patientInfo = $this->pinfo($bill['patient_id']);
			$date = $bill['date'];
			$amount = $bill['amount'];
			$paid = $bill['paid'];
			if($bill['completed'] == 1){
				$status = "Completed";
			}else if($bill['completed'] == 0){
				$status = "Debtor";
			}else if($bill['completed'] == 2){
				$status = "Change";
			}
			//$status = ($bill['completed'] == 1) ? 'Completed' : ($bill['completed'] == 0) ? "Debtor" : "Change";
			$debt = ($bill['debt'] == 1) ? true : false;
			$pname = $patientInfo['name'];
			$hospitalno = $patientInfo['hospital_no'];
			//$balance = $amount - $paid;
			$dbill = $bill['dbill'];
			$balance = $this->balance($dbill,$amount,$paid,$bill['id']);
			array_push($result, array($bill['id'],$bill['patient_id'],
						array($date,$pname,$hospitalno,$service,$amount,$paid,$balance,$status),$debt,$dbill));
		}
		return $result;
	}
	private function balance($dbill,$amt,$amtpaid,$bid){
		if($dbill != 0){
			$balance = $this->db->getOne("select (sum(amount) - sum(paid)) as bal from billing where (id = ? or dbill = ?) and id <= ?",
											array($dbill,$dbill,$bid));
			return $balance['bal'];
		}else{
			return $amt - $amtpaid ;
		}
		
	}
	private function pinfo($pid){
		$result = $this->db->getOne("select * from patient where id = ?",array($pid));
		return $result;
	}
}
?>