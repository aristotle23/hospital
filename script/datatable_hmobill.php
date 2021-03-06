<?php
session_start();
require_once "dbHandler.php";

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
	private function pdetail($patient_id){
		 $result = $this->db->getOne("select name, hospital_id, phone, address from hmo_patient where id = ?",array($patient_id));
		 return $result;
	}
	private function services($id ){

		$service = "";
		$services = $this->db->getAll("SELECT distinct(name) FROM hmo_services s inner join hmo_billing_services bs 
								on hmo_services_id = s.id where hmo_billing_id = ?",array($id));
		foreach ($services as $key => $serv){
			if($key == 0){
				$service .= $serv['name'];
			}else{
				$service .= ", ".$serv['name'];
			}
		}			
		return $service;
	}
	public function getData($query){
		$data = array();
		$pdetail = $this->pdetail($query['hmo_patient_id']);
		$data[] = $query['date'];
		$data[] = $pdetail['hospital_id'];
		$data[] = $pdetail['name'];
		$data[] = $pdetail['phone'];
		$data[] = $pdetail['address'];
		$data[] = $this->services($query['id']);
		$data[] = $query['charge'];
		$data[] = "<a href='hmo_receipt.php?bid=".$query['id']."' class='btn btn-primary btn-block ' ><i class='fa fa-print'></i></a>";;
		
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
	1 => 'hmo_patient_id',
	2=> 'id',
	3=> 'charge',
	4 => 'id',
	5 => 'charge',
	6 => 'charge',
	7 => 'charge'
);

// getting total number records without any search
$sql = "SELECT date, hmo_patient_id, id, charge ";
$sql.=" FROM hmo_billing WHERE hmo_id = ".$_REQUEST['hmo'];
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT b.date, hmo_patient_id, b.id, charge ";
$sql.=" FROM hmo_billing b INNER JOIN hmo_patient p ON p.id = b.hmo_patient_id WHERE b.hmo_id = ".$_REQUEST['hmo']." AND 1 = 1  ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR hospital_id LIKE '".$requestData['search']['value']."%' ";

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