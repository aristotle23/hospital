<?php
class preport{
	private $db ;
	private $from;
	private $to;
	private $offset;
	private $rowsperpage;
	public $servicetitle;
	public function __construct($from , $to, $offset, $rowsperpage){
		$this->db = new dbHandler();
		$this->from = $from;
		$this->to = $to;
		$this->offset = $offset;
		$this->rowsperpage = $rowsperpage;
	}
	
	public function patientinfo($patientid){
		$result = $this->db->getOne("select * from patient where id = ?",array($patientid));
		return $result;
	}
	public function patientReport(){
		$titles = $this->db->getAll("select id, title from service_title");
		//array_push($titles,array("id"=>0,"title"=>"Debt"));
		$this->servicetitle = $titles;
		$preport = array();
		$bills = $this->db->getAll("select * from billing where date between ? and ? order by date desc limit ".$this->offset.",".$this->rowsperpage,
									array($this->from, $this->to));
		foreach($bills as $bill){
			$bdate = $bill['date'];
			$bid = $bill['id']; // card no;
			$pname = $this->patientinfo($bill['patient_id']);
			$pname = $pname['name'];
			$amtpaid = $bill['paid'];
			$amt = $bill['amount'];
			$tlabel = array();
			$dbill = $bill['dbill'];
			if($bill['completed'] == 1){
				$status = "Completed";
			}else if($bill['completed'] == 0){
				$status = "Debtor";
			}else if($bill['completed'] == 2){
				$status = "Change";
			}
			foreach($titles as $title){
				$sum = $this->titlesummation($title['id'],$bid);
				array_push($tlabel,$sum);
			}
			$balance = $this->balance($dbill,$amt,$amtpaid,$bid);
			//array_push($tlabel,$this->tdebt($dbill));
			array_push($preport, array($bdate,$bid,$pname,$tlabel,$amt,$amtpaid,$status,$this->tdebt($dbill),$balance));
		}
		return $preport;
	}
	private function tdebt($dbill){
		if($dbill != 0){
			$date = $this->db->getOne("select date from billing where id = ? ",array($dbill));
			return "FROM ".$date['date'];
		}else{
			return "NULL";
		}
	}
	private function balance($dbill,$amt,$amtpaid,$bid){
		if($dbill != 0){
			$balance = $this->db->getOne("select sum(amount) - sum(paid) as bal from billing where (id = ? or dbill = ?) and id <= ?",
											array($dbill,$dbill,$bid));
			return $balance['bal'];
		}else{
			return $amt - $amtpaid ;
		}
		
	}
	private function titlesummation($titleid,$billid){
		$summation = $this->db->getOne("SELECT sum(bs.amount) as sum FROM belling_services bs inner join services s on s.id = bs.services 
							where s.service_title_id = ? and bs.billing_id = ?;",array($titleid,$billid));
		return $summation['sum'];
	}
}
class sreport{
	private $from;
	private $to;
	private $db;
	private $offset;
	private $rowsperpage;
	public $servicetitle;
	public function __construct($from, $to,$offset, $rowperpage){
		$this->db = new dbHandler();
		$this->from = $from;
		$this->to = $to;
		$this->offset = $offset;
		$this->rowsperpage = $rowperpage;
	}
	public function report(){
		$titles = $this->db->getAll("SELECT distinct(title), st.id FROM services s inner join belling_services bs on bs.services = s.id inner join
									 service_title st on st.id = s.service_title_id inner join billing b on b.id = bs.billing_id order by st.id");
		//array_push($titles,"Debt");
		$this->servicetitle = $titles;
		$dates = $this->db->getAll("select distinct(date) from billing where date between ? and ? order by date desc
									limit ".$this->offset.",".$this->rowsperpage,array($this->from, $this->to));
		$sreport = array();
		foreach($dates as $date){
			$dtitle = array($date['date']); 
			$summ = 0;
			foreach($titles as $title){
				$tsum = $this->tsum($title['id'],$date['date']);
				//$summ += $tsum;
				array_push($dtitle,$tsum);
			}
			$summ = $this->dcharge($date['date']);
			$receive = $this->treceive($date['date']);
			$dpaid = $this->dpaid($date['date']);
			$balance = ($summ + $dpaid) - $receive;
			array_push($dtitle,$summ);
			array_push($dtitle,$dpaid);
			array_push($dtitle,$receive);
			array_push($dtitle,$balance);
			array_push($sreport,$dtitle);
		}
		return $sreport;
	}
	private function treceive($date){
		$summ = $this->db->getOne("SELECT sum(paid) as paid from billing where date = ?",array($date));
		return $summ['paid'];
	}
	private function dpaid($date){
		$summ = $this->db->getOne("select sum(paid) as paid from billing where dbill != 0 and date = ?",array($date));
		return $summ['paid'];
	}
	private function tsum($tid, $date){
		$sum = $this->db->getOne("SELECT sum(bs.amount) as tsum FROM services s inner join belling_services bs on bs.services = s.id inner join 
							service_title st on st.id = s.service_title_id inner join billing b on b.id = bs.billing_id where 
							service_title_id = ? and b.date = ? ",array($tid,$date));
		return $sum['tsum'];
	}
	private function dcharge($date){
		$sum = $this->db->getOne("SELECT sum(amount) as sum from billing where date = ?",array($date));
		return $sum['sum'];
	}
}
class ureport{
	private $db ;
	private $from;
	private $to;
	private $offset;
	private $rowsperpage;
	public $amtpaid = 0;
	public $amtcharge = 0;
	public function __construct($from , $to, $offset,$rowsperpage){
		$this->db = new dbHandler();
		$this->from = $from;
		$this->to = $to;
		$this->offset = $offset;
		$this->rowsperpage = $rowsperpage;
	}
	public function userInfo($uid){
		$result = $this->db->getOne("SELECT access, name, username FROM user u inner join access_right ar on 
										ar.level = u.access_right where u.id = ?",array($uid));
		return $result;
	}
	public function userReport(){
		
		$ureport = array();
		$bdates = $this->db->getAll("select distinct(date) from billing_hist where date between ? and ? 
									order by date desc limit ".$this->offset.",".$this->rowsperpage,array($this->from, $this->to));
		foreach($bdates as $bdate){
			$date = $bdate['date'];
			$busers = $this->db->getAll("select distinct(user_id) as uid from billing_hist where date = ?",array($date));
			foreach($busers as $buser){
				$userinfo = $this->userInfo($buser['uid']);
				$uname = $userinfo['name'];
				$right = $userinfo['access'];
				$billing = $this->db->getOne("select sum(paid) as paid, sum(amount) as amount from billing_hist where user_id = ? and date = ?",
												array($buser['uid'],$date));
				$this->amtpaid += $billing['paid'];
				$this->amtcharge += $billing['amount'];
				$services = $this->services($buser['uid'],$date);

				$chck = $this->db->getOne("select id,balance from ureport where date = ? and user_id = ?",array($date,$buser['uid']));
				if(!$chck){
					$statId = $this->db->executeGetId("INSERT INTO `ureport` (`user_id`, `date`) VALUES (?,?);",array($buser['uid'],$date));	
					$stat = 0;
				}else{
					$stat = $chck['balance'];
					$statId = $chck['id'];
				}
				array_push($ureport,array($statId,$stat,array($date,$uname,$right,$services,$billing['paid'],$billing['amount'])));
			}
		}
		return $ureport;
	}
	private function services($userid,$date){
		$servs = "";
		$bills = $this->db->getAll("SELECT  bill_id as id FROM billing_hist WHERE user_id = ? and date = ?",array($userid,$date));
		foreach($bills as $bill){
			$serv = "";
			$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
								where billing_id = ?",array($bill['id']));
			if(count($services) > 0){
			  foreach($services as $key => $service){
				  if($key == 0){
					  $serv .= $service['service'];
				  }else{
					  $serv .= ", ".$service['service'];
				  }
			  }
			}else{
				$serv = "Debt";
			}
			$servs .= $serv;
		}
		return $servs;
	}
}
class incomeReport{
	private $db;
	private $from;
	private $offset;
	private $rowsperpage;
	private $to;
	public function __construct($from, $to, $offset, $rowsperpage){
		$this->db = new dbHandler();
		$this->from = $from;
		$this->to = $to;
		$this->offset = $offset;
		$this->rowsperpage = $rowsperpage;
	}
	public function getBilling(){
		$result = array();
		$distdate = $this->db->getAll("select distinct(date) as date from billing where date between ? and ? order by date asc
										limit ".$this->offset.",".$this->rowsperpage,array($this->from,$this->to));
		$ttlpaid = 0;
		foreach($distdate as $date){
			$billing = $this->db->getAll("select * from billing where date = ? order by date asc",array($date['date']));
			$service = array();
			foreach ($billing as $bill){
				$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
								where billing_id = ?",array($bill['id']));
				if($bill['dbill'] == 0){
				  foreach($services as $serv){
					  array_push($service,$serv['service']);
				  }
				}else{
					array_push($service,"Debt");
				}
			}
			$income = $this->db->getAll("select type from income where date = ? ",array($date['date']));
			foreach($income as $type){
				array_push($service,$type['type']);
			}
			$service = array_unique($service);
			$service = implode(", ",$service);

			$paid = $this->db->getOne("select sum(paid) as paid from billing where date = ?",array($date['date']));
			$paid = $paid['paid'];
			$inpaid = $this->db->getOne("select sum(amount) as paid from income where date = ?",array($date['date']));
			
			$paid += $inpaid['paid'];
			
			$ttlpaid += $paid;
			array_push($result, array($date['date'],$service,$paid));
			
		}
		return array($result,$ttlpaid);
	}
}
