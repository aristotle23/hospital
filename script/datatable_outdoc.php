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
	0 =>'date', 
	1 => 'hospital_no',
	2=> 'name',
	3=> 'dob',
	4 => 'sex',
    5 => 'id'
);

// getting total number records without any search
$sql = "SELECT id, date, hospital_no, name, dob , sex, ward ";
$sql.=" FROM patient where user_id = ".$_SESSION['user_id'];
$query=mysqli_query($conn, $sql) or die("datatable_doc.php: doctor list");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT id, date, hospital_no, name, dob , sex, ward";
$sql.=" FROM patient WHERE user_id = ".$_SESSION['user_id'];
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR hospital_no LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR date LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$data = array();

while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$nestedData=array();

    if($row['ward'] != null){
        continue;
    }
	$nestedData[] = $row["date"];
	$nestedData[] = $row["hospital_no"];
	$nestedData[] = $row["name"];
	$nestedData[] = $row['dob'];
	$nestedData[] = $row["sex"];

	$nestedData[] = "<div class='row'>
					<div class='col-md-4' style='padding-right: 0px'>
					<a href='diagnose.php?pid=".$row['id']."' class='btn btn-primary btn-block'>Diagnose</a></div>
					<div class='col-md-4' style='padding-left: 5px; padding-right:0px'>
					<a href='treatment.php?pid=".$row['id']."' class='btn btn-primary btn-block'>Treatment</a></div>
					<div class='col-md-4' style='padding-left: 5px'>
					<a href='patient_view.php?pid=".$row['id']."' class='btn btn-primary btn-block'>View</a></div></div>";
	
	$data[] = $nestedData;
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format