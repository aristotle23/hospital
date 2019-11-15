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
	private function pname($patient_id){
		 $result = $this->db->getOne("select name from patient where id = ?",array($patient_id));
		 return $result['name'];
	}
	private function hospitalno($patient_id){
		
		$result = $this->db->getOne("select hospital_no from patient where id = ?",array($patient_id));
	    return $result['hospital_no'];
	}
	private function services($dbill, $id ){

		$service = "";
		if($dbill == 0){
			$services = $this->db->getAll("SELECT s.services as service FROM belling_services bs inner join services s on bs.services = s.id 
								where billing_id = ?",array($id));
			foreach($services as $key => $serv){
			  if($key == 0){
				  $service .= $serv['service'];
			  }else{
				  $service .= ", ".$serv['service'];
			  }
			}
		}else{
			$service = "Debt";
		}
		return $service;
	}
	private function balance($dbill, $id,$amount,$paid){
	
		if($dbill != 0){
			$balance = $this->db->getOne("select (sum(amount) - sum(paid)) as bal from billing where (id = ? or dbill = ?) and id <= ?",
											array($dbill,$dbill,$id));
			return $balance['bal'];
		}else{
			return $amount - $paid ;
		}
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
	private function operation($completed, $debt, $patient_id, $id, $dbill){
		$tr = "";
		if($completed == 1){
			$status = "Completed";
		}else if($completed == 0){
			$status = "Debtor";
		}else if($completed == 2){
			$status = "Change";
		}
		if($_SESSION['right'] == 2 && $debt == false){
			$tr .= " <a title='Edit Reqquest' data-pid='".$patient_id."' data-pbill='".$id."' class='btn 
			btn-primary editreq'><i class='fa  fa-edit'></i></a> ";
			}
			if($_SESSION['right'] >= 3 && $debt == false){
			$tr .= " <a title='Edit' href='edit_billing.php?pid=".$patient_id."&bid=".$id."' class='btn 
			btn-primary' ><i class='fa  fa-edit'></i></a> ";
			}
			if($_SESSION['right'] >= 3){
				$tr .= " <a title='Delete bill' class='btn btn-danger ' href='?del=1&bid=".$id."'>
				<i class='fa  fa-times'></i></a> ";
			}
			if(strtolower($status) == 'debtor' && $dbill == 0 ){
			$tr .= " <a title='Pay Debt' href='debt_billing.php?pid=".$patient_id."&bid=".$id."' class='btn 
			btn-primary debt' >	<i class='fa fa-reply'></i></a> ";
			}
		$tr .= "
				<a title='Add Bill' href='add_billing.php?pid=".$patient_id."&bid=".$id."' class='btn 
			btn-primary' ><i class='fa  fa-plus'></i></a>

			  <a title='Print' href='receipt.php?pid=".$patient_id."&bill=".$id."' class='btn btn-primary print'>
			  <i class='fa fa-print'></i></a>";
		return $tr;
	}
	public function getData($query){
		$data = array();
		$data[] = $query['date'];
		$data[] = $this->pname($query['patient_id']);
		$data[] = $this->hospitalno($query['patient_id']);
		$data[] = $this->services($query['dbill'],$query['id']);
		$data[] = $query['amount'];
		$data[] = $query['paid'];
		$data[] = $this->balance($query['dbill'],$query['id'],$query['amount'],$query['paid']);
		$data[] = $this->completed($query['completed']);

		$data[] = $this->operation($query['completed'], $query['debt'], $query['patient_id'] , $query['id'], $query['dbill'] );
		return $data;
	}
}

/* Database connection start */
$servername = "localhost";
$username = "root";
$password = "developers";
$dbname = "hospital";

$conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$columns = array( 
// datatable column index  => database column name
	0 =>'date', 
	1 => 'patient_id',
	2=> 'dbill',
	3=> 'id',
	4 => 'amount',
	5 => 'paid',
	6 => 'completed',
	7 => 'debt',
	8 => 'debt'
);

// getting total number records without any search
$sql = "SELECT date, patient_id, dbill, id, amount, paid, completed, debt ";
$sql.=" FROM billing";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT b.date, patient_id, dbill, b.id, amount, paid, completed, debt ";
$sql.=" FROM billing b INNER JOIN patient p ON p.id = b.patient_id WHERE 1 = 1 ";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( p.name LIKE '".$requestData['search']['value']."%' ";    
	$sql.=" OR hospital_no LIKE '".$requestData['search']['value']."%' ";

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
	$nestedData=array(); 

	$nestedData[] = $row["date"];
	$nestedData[] = $row["patient_id"];
	$nestedData[] = $row["dbill"];
	$nestedData[] = $row['id'];
	$nestedData[] = $row["amount"];
	$nestedData[] = $row["paid"];
	$nestedData[] = $row["completed"];
	$nestedData[] = $row['debt'];
	$nestedData[] = $row['debt'];
	
	$data[] = $manage->getData($row);
	//$data[] = $nestedData;
	
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format