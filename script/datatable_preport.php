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
	
	private function completed($completed){
		if($completed == 1){
			return "Completed";
		}else if($completed == 0){
			return "Debtor";
		}else if($completed == 2){
			return "Change";
		}
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
		$bills = $this->db->getAll("select * from billing where date between ? and ? order by date desc",array($this->from, $this->to));
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
	private function tlabel($bid){
		$tlabel = array();
		$titles = $this->db->getAll("SELECT * FROM service_title order by id asc");
		foreach($titles as $title){
		  $sum = $this->titlesummation($title['id'],$bid);
		  array_push($tlabel,$sum);
		}
		return $tlabel;
	}
	private function tdebt($dbill){
		if($dbill != 0){
			$date = $this->db->getOne("select date from billing where id = ? ",array($dbill));
			return "FROM ".$date['date'];
		}else{
			return "-";
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
	public function getData($query){
		$data = array();
		$patientInfo = $this->patientinfo($query['patient_id']);
		$titles = $this->tlabel($query['id']);
		$data[] = $query['date'];
		$data[] = $query['id'];
		$data[] =  $patientInfo['name'];
		for ($x = 0 ; $x < count($titles); $x++){
			$data[] = ($titles[$x] == "" ? 0.0 : $titles[$x]);
		}
		$data[] = $this->tdebt($query['dbill']);
		$data[] = $query['amount'];
		$data[] = $query['paid'];
		$data[] = $this->balance($query['dbill'],$query['amount'],$query['paid'],$query['id']);
		$data[] = $this->completed($query['completed']);
		
		return $data;
	}
}

$conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$header = 8 + $_REQUEST['autocol'];
$columns = array();
for($i = 0 ; $i < $header ; $i++){
	$columns[$i] = 'date';
}

// getting total number records without any search
$sql = "SELECT date, id, patient_id, paid, amount, dbill, completed, debt ";
$sql.=" FROM billing WHERE date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT b.date, b.id, patient_id, paid, amount, dbill, completed, debt ";
$sql.=" FROM billing b INNER JOIN patient p ON p.id = b.patient_id WHERE 1 = 1 AND b.date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
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
	
	$data[] = $manage->getData($row);
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format