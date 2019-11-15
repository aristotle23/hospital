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
	private function services($date){
		$billing = $this->db->getAll("select * from billing where date = ? order by date asc",array($date));
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
		$service = array_unique($service);
		$service = implode(", ",$service);
		return $service;
		
	}
	public function getBilling(){
		$result = array();
		$distdate = $this->db->getAll("select distinct(date) as date from billing where date between ? and ? order by date asc",
									array($this->from,$this->to));
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
			$service = array_unique($service);
			$service = implode(", ",$service);
			$paid = $this->db->getOne("select sum(paid) as paid from billing where date = ?",array($date['date']));
			$paid = $paid['paid'];
			$ttlpaid += $paid;
			array_push($result, array($date['date'],$service,$paid));
			
		}
		return array($result,$ttlpaid);
	}
	public function getData($date){
		$data = array();
		$paid = $this->db->getOne("select sum(paid) as paid from billing where date = ?",array($date));
			
		$data[] = $date;
		$data[] = $this->services($date);
		$data[] = $paid['paid'];
		
		return $data;
	}
}

/* Database connection start */

$conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'date', 
	1 => 'date',
	2=> 'date',
);

// getting total number records without any search
$sql = "SELECT distinct(date) ";
$sql.=" FROM billing WHERE date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT distinct(date)";
$sql.=" FROM billing WHERE 1 = 1 AND date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( date LIKE '".$requestData['search']['value']."%') ";    
	
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
	
	$data[] = $manage->getData($date);
	
	
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format