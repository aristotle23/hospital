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
	private $useDate = array();
	private $useDiagnose = array();
	private $useMedicine = array();
	public function __construct(){
		$this->db = new dbHandler();
	}
	
	private function getDiagnosis($patient, $date ){
        $diagnosisStr = "";

        $result = $this->db->getAll("SELECT diagnose  from diagnosis where date = ? and patient_id = ? and payment != 0",array($date, $patient));
        foreach($result as $key => $diagnose){
            if(in_array($diagnose['diagnose'],$this->useDiagnose)){
                continue;
            }
            array_push($this->useDiagnose, $diagnose['diagnose']);
            if($key == 0){
                $diagnosisStr = $diagnose['diagnose'];
            }else{
                $diagnosisStr .= ", ".$diagnose['diagnose'];
            }

        }
        array_push($this->useDate,$date);
        return $diagnosisStr;
	}
	private function getTreatment($patient,$date){
        $medicineStr = "";

        $result = $this->db->getAll("SELECT name from medicine m inner join treatment t on t.medicine_id = m.id   
										where t.date = ? and t.patient_id = ?",array($date, $patient));
        foreach($result as $key => $medicine){
            if(in_array($medicine['name'],$this->useMedicine)){
                continue;
            }
            array_push($this->useMedicine, $medicine['name']);
            if($key == 0){
                $medicineStr = $medicine['name'];
            }else{
                $medicineStr .= ", ".$medicine['name'];
            }

        }
        array_push($this->useDate,$date);
        return $medicineStr;
    }
	
	public function getData($query){
	    //$qdate = date("Y-m-d",strtotime($query['date']));
		if(in_array($query['date'],$this->useDate) ){
			return false;
		}
		$this->useService = array();
		$data = array();
		$pdetail = $this->db->getOne("select * from patient where id = ?",array($query['patient_id']));
		$data[] = $query['date'];
		$data[] = $pdetail['hospital_no'];
		$data[] = $pdetail['name'];
		$data[] = $this->getTreatment($query['patient_id'],$query['date']);
        $data[] = $this->getDiagnosis($query['patient_id'],$query['date']);
		$data[] = $pdetail['sex'];
		$data[] = "<div class='row'>
					<div class='col-md-6' style='padding-right: 0px'>
					<a href='bill_approval.php?pid=".$query['patient_id']."&date=".$query['date']."' class='btn btn-primary btn-block'>Bill</a></div>
					<div class='col-md-6' style='padding-left: 5px'>
					<a href='patient_view.php?pid=".$query['patient_id']."' class='btn btn-primary btn-block'>View</a></div></div>";;
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
	1 => 'patient_id',
	2=> 'patient_id',
	3=> 'patient_id',
	4 => 'date',
	5 => 'date',
);

// getting total number records without any search
$sql = "SELECT distinct(date) as date, patient_id ";
$sql.=" FROM diagnosis where payment = 1";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

// getting total number records without any search
$sql = "SELECT distinct(date) as date, patient_id ";
$sql.=" FROM treatment where payment = 0";
$query2=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData2 = mysqli_num_rows($query2);
$totalFiltered2 = $totalData2;  // when there is no search parameter then total number rows = total number filtered rows.

$totalFiltered += $totalFiltered2;
$totalData += $totalData2;


$sql = "SELECT distinct(d.date) as date, patient_id ";
$sql.=" FROM diagnosis d INNER JOIN patient p ON p.id = d.patient_id WHERE payment = 1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR hospital_no LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR d.date LIKE '".$requestData['search']['value']."%' )";
}
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$sql = "SELECT distinct(d.date) as date, patient_id ";
$sql.=" FROM treatment d INNER JOIN patient p ON p.id = d.patient_id WHERE payment = 0 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR hospital_no LIKE '".$requestData['search']['value']."%' ";

	$sql.=" OR d.date LIKE '".$requestData['search']['value']."%' )";
}
$query2=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered += mysqli_num_rows($query2); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query2=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$data = array();
$manage = new manage();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	$new = $manage->getData($row);
	if($new == false){
		continue;
	}
	$data[] = $new;
}
while( $row=mysqli_fetch_array($query2) ) {  // preparing an array
	$new = $manage->getData($row);
	if($new == false){
		continue;
	}
	$data[] = $new;
}


$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format