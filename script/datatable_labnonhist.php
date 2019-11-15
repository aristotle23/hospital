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
	
	private function getTest($date, $npid ){
		$tests = "";
		$result = $this->db->getAll("SELECT test, result from nonpatient_lab where date = ? and nonpatient_id = ?",array($date, $npid));
		foreach($result as $key => $test){
			if($key == 0){
				$tests .= $test['test'];
				continue;
			}
			$tests .= ", ".$test['test'];
		}
		return $tests;
	}
	
	public function getData($query){
		$data = array();
		$user = $this->db->getOne("select username,name,access from user u inner join  access_right a on u.access_right = a.level where u.id = ?",
            array($query['user_id']));
		$data[] = $query['date'];
		$data[] = $query['name'];
		$data[] = $query['sex'];
        $data[] = $query['phone'];
		$data[] = $this->getTest($query['date'],$query['nonpatient_id']);
		$data[] = $user['username'];
		$data[] = "<div class='row'>
					<div class='col-md-12' style='padding-right: 0px'>
					<a href='view_nonplab.php?npid=".$query['nonpatient_id']."&date=".$query['date']."' class='btn btn-primary btn-block'>Edit</a></div>
					</div>";
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
	1 => 'name',
	2=> 'sex',
	3=> 'phone',
	4 => 'user_id',
	5 => 'date',
    6 => 'date'
);

// getting total number records without any search
$sql = "SELECT distinct(date),name,sex,phone,user_id,nonpatient_id";
$sql.=" FROM hospital.nonpatient_lab l inner join nonpatient p on l.nonpatient_id = p.id  ";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT distinct(date),name,sex,phone,user_id,nonpatient_id";
$sql.=" FROM hospital.nonpatient_lab l inner join nonpatient p on l.nonpatient_id = p.id ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR p.sex LIKE '".$requestData['search']['value']."%' ";
    $sql.=" OR p.phone LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR l.date LIKE '".$requestData['search']['value']."%' )";
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