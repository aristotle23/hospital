<?php
 
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
require_once "config.php";
session_start();
// DB table to use
$table = 'patient';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => 'hospital_no', 'dt' => 0 ),
    array( 'db' => 'name',  'dt' => 1 ),
    array( 'db' => 'telephone',   'dt' => 2 ),
    array( 'db' => 'dob',     'dt' => 3 ),
	array( 'db' => 'sex',     'dt' => 4 ),
	array( 'db' => 'marital_status',     'dt' => 5 ),
	array( 'db' => 'date',     'dt' => 6 ),
    array(
        'db'        => 'id',
        'dt'        => 7,
        'formatter' => function( $d, $row ) {
			if($_SESSION['right'] == 5){
				return "<div class='row'>
					<div class='col-md-6' style='padding-right: 0px'>
					<a href='patient_view.php?pid=".$d."' class='btn btn-primary btn-block'>View</a></div>
					<div class='col-md-6' style='padding-left: 5px'>
					<a href='vitals.php?pid=".$d."' class='btn btn-primary btn-block'>Vital</a></div></div>";
			}else{
				return "<div class='row'>
					<div class='col-md-6' style='padding-right: 0px'>
					<a href='patient_view.php?pid=".$d."' class='btn btn-primary btn-block'>View</a></div>
					<div class='col-md-6' style='padding-left: 5px'>
					<a href='billing.php?pid=".$d."' class='btn btn-primary btn-block'>Bill</a></div></div>";
			}
        }
    )
);
 
// SQL server connection information
$sql_details = array(
    'user' => USERNAME,
    'pass' => PASSWORD,
    'db'   => DBNAME,
    'host' => SERVER
);
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( 'ssp.class.php' );
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);