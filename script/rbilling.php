<?php

class patientBilling{
	private $db;
	private $pid;
	public function __construct($pid){
		$this->db = new dbHandler();
		$this->pid = $pid;
	}
	public function getBilling(){
		$result = array();
		$billing = $this->db->getAll("select * from billing where patient_id = ? order by date asc",array($this->pid));
		foreach ($billing as $bill){
			$service = "";
			$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
								where billing_id = ?",array($bill['id']));
			if($bill['dbill'] == 0){
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
			//$status = ($bill['completed'] == 1) ? 'Completed' : ($bill['completed'] == 0) ? 'Debtor' : "Change";
			$balance = $this->balance($bill['dbill'],$amount,$paid,$bill['id']);
			array_push($result, array($bill['id'],array($date,$service,$amount,$paid,$balance,$status)));
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
}

class groupBilling{
	private $db;
	private $pid;
	public $gbal;
	public function __construct($pid){
		$this->db = new dbHandler();
		$this->pid = $pid;
		$this->gbal = 0;
	}
	public function getBilling(){
		$result = array();
		if(empty($this->pid)){
			return $result;
		}
		$sql = "select * from billing where completed = 0 and dbill = 0 ";
		foreach($this->pid as $key => $pid){
			if($key == 0){
				$sql .= "and ( patient_id = ?";
				continue;
			}
			$sql .= " or patient_id = ? ";
		}
		$sql .= ") order by date asc";
		$billing = $this->db->getAll($sql,$this->pid);
		
		foreach ($billing as $bill){
			$service = "";
			$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
								where billing_id = ?",array($bill['id']));
			if($bill['dbill'] == 0){
			  foreach($services as $key => $serv){
				  if($key == 0){
					  $service .= $serv['service'];
				  }else{
					  $service .= ", ".$serv['service'];
				  }
			  }
			}
			$date = $bill['date'];
			$amount = $bill['amount'];
			$name = $this->db->getOne("select name from patient where id = ?",array($bill['patient_id']));
			$name = $name['name'];
			$paid = $this->db->getOne("select sum(paid) as paid from billing where dbill = ? or id = ? ",array($bill['id'],$bill['id']));
			$paid = $paid['paid'];
			$balance = $amount - $paid;
			$this->gbal += $balance;
			array_push($result, array($bill['id'],$bill['patient_id'],array($date,$name,$service,$amount,$paid,$balance)));
		}
		return $result;
	}
	
}
?>