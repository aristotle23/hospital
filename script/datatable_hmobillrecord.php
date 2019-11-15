<?php
session_start();
require_once "dbHandler.php";
$db = new dbHandler();
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
	private $year;
	private $month;
	public function __construct(){
		
		$this->db = new dbHandler();
	}
	private function ttl(){
		 $result = $this->db->getOne("SELECT count(distinct(hmo_patient_id)) as patient, sum(ttl_service) as service, sum(charge) as charge
		 							FROM hmo_billing where year(date) = ? and month(date) = ?",array($this->year, $this->month));
		 return $result;
	}
	private function paid($date){
		$amount = 0;
		$result = $this->db->getOne("SELECT amount FROM hmo_payment where date = ?",array($date));
		if($result){
			$amount = $result['amount'];
		}
		return $amount;
	}
	public function getData($year, $month){
		$this->year = $year;
		$this->month = $month;
		$data = array();
		
		$ttl = $this->ttl();
		$date = $month.'-'.$year;
		
		$paid = $this->paid($date);
		$balance = $ttl['charge'] - $paid;
		$data[] = $date;
		$data[] = $ttl['patient'];
		$data[] = $ttl['service'];
		$data[] = $ttl['charge'];
		$data[] = $paid;
		$data[] = $balance;
		$data[] = "<a href='#' data-toggle='modal' data-target='#hmo-payment' data-date='".$date."' data-hmo='".$_REQUEST['hmo']."' 
					class='btn btn-primary btn-block hmo-pay' ". ($balance == 0 ? "disabled='disabled'": "")." >Pay</a>";
		
		return $data;
	}
}

/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "developers";
$dbname = "hospital";

$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());

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
	6 => 'date',
);

// getting total number records without any search
$sql = "SELECT distinct(year(date)) as date ";
$sql.=" FROM hmo_billing WHERE hmo_id = ".$_REQUEST['hmo'];
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT distinct(year(date)) as date ";
$sql.=" FROM hmo_billing WHERE hmo_id = ".$_REQUEST['hmo']." AND 1 = 1  ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND date LIKE '".$requestData['search']['value']."%' ";    
}
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$data = array();
$manage = new manage();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$year = $row['date'];
	$months = $db->getAll("SELECT distinct(month(date)) as month FROM hmo_billing where year(date) = ? order by month asc",array($year));
	$col = array();
	foreach($months as $month){
		$data[] = $manage->getData($year,$month['month']);
		
	}
	//$data[] = $col;
	//$data[] = $manage->getData($query);
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format