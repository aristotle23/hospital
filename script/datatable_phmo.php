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

/* Database connection start */

$conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'hospital_id', 
	1 => 'name',
	2=> 'sex',
	3=> 'phone',
	4 => 'address',
	5 => 'id',
);

// getting total number records without any search
$sql = "SELECT hospital_id, name, phone, address, sex, id ";
$sql.=" FROM hmo_patient where hmo_id = ".$_REQUEST['hmo'];
$query=mysqli_query($conn, $sql) or die("datatable_doc.php: doctor list");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT hospital_id, name, phone, address, sex, id ";
$sql.=" FROM hmo_patient WHERE hmo_id = ".$_REQUEST['hmo'];
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR hospital_id LIKE '".$requestData['search']['value']."%' ";
	$sql.=" OR address LIKE '".$requestData['search']['value']."%' )";
	$sql.=" OR phone LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$data = array();


$dis = mysqli_query($conn,"select count(id) as count from hmo_services where hmo_id = ".$_REQUEST['hmo']);
$bdis = mysqli_fetch_assoc($dis);
$disable = ($bdis['count'] > 0) ? "" : " disabled='disabled' " ;

while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array(); 

	$nestedData[] = $row["hospital_id"];
	$nestedData[] = $row["name"];
	$nestedData[] = $row["sex"];
	$nestedData[] = $row['phone'];
	$nestedData[] = $row["address"];
	$nestedData[] = "
					<a href='hmo_billing.php?pid=".$row['id']."' class='btn btn-primary btn-block'".$disable."  >Bill</a>
					";
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format