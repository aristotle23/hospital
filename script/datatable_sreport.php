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
	
	public function report(){
		$titles = $this->db->getAll("SELECT distinct(title), st.id FROM services s inner join belling_services bs on bs.services = s.id inner join
									 service_title st on st.id = s.service_title_id inner join billing b on b.id = bs.billing_id order by st.id");
		//array_push($titles,"Debt");
		$this->servicetitle = $titles;
		$dates = $this->db->getAll("select distinct(date) from billing where date between ? and ? order by date desc",array($this->from, $this->to));
		$sreport = array();
		foreach($dates as $date){
			$dtitle = array($date['date']); 
			$summ = 0;
			foreach($titles as $title){
				$tsum = $this->tsum($title['id'],$date['date']);
				//$summ += $tsum;
				array_push($dtitle,$tsum);
			}
			$summ = $this->dcharge($date['date']);
			$dpaid = $this->dpaid($date['date']);
			$receive = $this->treceive($date['date']);
			$balance = ($summ + $dpaid) - $receive;
			array_push($dtitle,$summ);
			array_push($dtitle,$dpaid);
			array_push($dtitle,$receive);
			array_push($dtitle,$balance);
			array_push($sreport,$dtitle);
		}
		return $sreport;
	}
	private function treceive($date){
		$summ = $this->db->getOne("SELECT sum(paid) as paid from billing where date = ?",array($date));
		return $summ['paid'];
	}
	private function dpaid($date){
		$summ = $this->db->getOne("select sum(paid) as paid from billing where dbill != 0 and date = ?",array($date));
		return $summ['paid'];
	}
	private function tsum($tid, $date){
		$sum = $this->db->getOne("SELECT sum(bs.amount) as tsum FROM services s inner join belling_services bs on bs.services = s.id inner join 
							service_title st on st.id = s.service_title_id inner join billing b on b.id = bs.billing_id where 
							service_title_id = ? and b.date = ? ",array($tid,$date));
		return ($sum['tsum'] == ""? 0.0: $sum['tsum']);
	}
	private function dcharge($date){
		$sum = $this->db->getOne("SELECT sum(amount) as sum from billing where date = ?",array($date));
		return $sum['sum'];
	}	
	public function getData($date){
		$data = array();
		$data[] = $date;
		$titles = $this->db->getAll("SELECT distinct(title), st.id FROM services s inner join belling_services bs on bs.services = s.id inner join
									 service_title st on st.id = s.service_title_id inner join billing b on b.id = bs.billing_id order by st.id");
		foreach($titles as $title){
			$tsum = $this->tsum($title['id'],$date);
			$data[] = $tsum;
		}
		$data[] = $this->dcharge($date);
		$data[] = $this->dpaid($date);
		$data[] = $this->treceive($date);
		$data[] = ($summ + $dpaid) - $receive;
		
		return $data;
	}
}

$conn = mysqli_connect(SERVER, USERNAME, PASSWORD, DBNAME) or die("Connection failed: " . mysqli_connect_error());

/* Database connection end */


// storing  request (ie, get/post) global array to a variable  
$requestData= $_REQUEST;

$header = 5 + $_REQUEST['autocol'];
$columns = array();
for($i = 0 ; $i < $header ; $i++){
	$columns[$i] = 'date';
}

// getting total number records without any search
$sql = "select distinct(date) ";
$sql.=" FROM billing WHERE date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalData = mysqli_num_rows($query);
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.


$sql = "SELECT distinct(date) ";
$sql.=" FROM billing WHERE date between '".$_REQUEST['from']."' AND '".$_REQUEST['to']."'";
if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	$sql.=" AND ( date LIKE '".$requestData['search']['value']."%' ) ";    
}
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");
$totalFiltered = mysqli_num_rows($query); // when there is a search parameter then we have to modify total number filtered rows as per search result. 
$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */	
$query=mysqli_query($conn, $sql) or die("datatable_bv.php: get billings");

$data = array();
$manage = new manage();
while( $row=mysqli_fetch_array($query) ) {  // preparing an array
	
	$data[] = $manage->getData($row['date']);
}



$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $totalData ),  // total number of records
			"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
			);

echo json_encode($json_data);  // send data as json format