<?php
session_start();
require_once "dbHandler.php";
require_once "config.php";

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
class manage{
	private $db;
	public function __construct(){
		$this->db = new dbHandler();
	}
	public function userInfo($uid){
		$result = $this->db->getOne("SELECT access, name, username FROM user u inner join access_right ar on 
										ar.level = u.access_right where u.id = ?",array($uid));
		return $result;
	}
	private function services($userid,$date){
		$servs = array();
		$bills = $this->db->getAll("SELECT  bill_id as id FROM billing_hist WHERE user_id = ? and date = ?",array($userid,$date));
		foreach($bills as $bill){
			$serv = "";
			$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
								where billing_id = ?",array($bill['id']));
			if(count($services) > 0){
			  foreach($services as $key => $service){
				  /*if($key == 0){
					  $serv .= $service['service'];
				  }else{
					  $serv .= ", ".$service['service']." ";
				  }*/
				  //$serv .= $service['service'].", ";
				  if(in_array($service['service'],$servs)){
					  continue;
				  }
				  array_push($servs,$service['service']);
			  }
			}else{
				//$serv = "Debt";
				if(in_array($service['service'],$servs)){
					  continue;
				  }
				  array_push($servs,$service['service']);
			}
			//$servs .= $serv;
		}
		return implode(', ',$servs);
	}
	public function userReport(){
		
		$ureport = array();
		$bdates = $this->db->getAll("select distinct(date) from billing_hist where date between ? and ?",array($this->from, $this->to));
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
	public function getData($date,$query){
		$data = array();
		$userinfo = $this->userInfo($query['uid']);
		$services = $this->services($query['uid'],$date);
		$billing = $this->db->getOne("select sum(paid) as paid, sum(amount) as amount from billing_hist where user_id = ? and date = ?",
										array($query['uid'],$date));
		$data[] = $date;
		$data[] = $userinfo['name'];
		$data[] = $userinfo['access'];
		$data[] = $services;
		$data[] = $billing['paid'];
		$data[] = $billing['amount'];
		
		$chck = $this->db->getOne("select id,balance from ureport where date = ? and user_id = ?",array($date,$query['uid']));
		if(!$chck){
			$statId = $this->db->executeGetId("INSERT INTO `ureport` (`user_id`, `date`) VALUES (?,?);",array($query['uid'],$date));	
			$stat = 0;
		}else{
			$stat = $chck['balance'];
			$statId = $chck['id'];
		}
		
		$dis = ($stat == 1) ? "display: none":"";
		$btn = "<a title='Balance' class='btn btn-primary ubal' style='".$dis."' data-rid='".$statId."'>
		<i class='fa fa-times'></i></a>";
		$dis = ($stat == 0) ? "display: none":"";
		$btn .= "<a title='Balanced already' class='btn btn-success' style='".$dis."' ><i class='fa fa-check'></i></a>";
		
		$data[] = $btn;
		
		
		return $data;
	}
}

$conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'date', 
	1 => 'date',
	2=> 'date',
	3=> 'date',
	4 => 'date',
	5 => 'date',
	6 => 'date'
);

// getting total number records without any search
$sql = "SELECT distinct(date) ";
$sql.=" FROM billing_hist WHERE date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT distinct(b.date) ";
$sql.=" FROM billing_hist b INNER JOIN patient p ON p.id = b.patient_id WHERE 1 = 1 AND b.date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR b.id LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR b.date LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$data = array();
$manage = new manage();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$date = $row['date'];
	$sql2 = "select distinct(user_id) as uid from billing_hist where date = '".$date."'";
	$innerq = mysqli_query($conn,$sql2 );
	while($innrow = mysqli_fetch_array($innerq)){
		$data[] = $manage->getData($date,$innrow);
	}
	
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format