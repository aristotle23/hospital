<?php 
session_start();
require "script/dbHandler.php";
require "script/functions.php";

if(!isset($_REQUEST['bid'])){
	header("location:hmo.php");
}


$db = new dbHandler();
$billing = $db->getOne("select * from hmo_billing where id = ?",array($_REQUEST['bid']));
$patient = $db->getOne("select * from hmo_patient where id = ?",array($billing['hmo_patient_id']));
$billinfo = $db->getOne("select * from hmo_billing_info where id = ?",array($billing['info_id']));
$services = $db->getAll("SELECT charge,name,quantity FROM hmo_billing_services bs inner join hmo_services s on hmo_services_id = s.id 
						where hmo_billing_id = ?",array($_REQUEST['bid']));


require_once "script/ini.php";


?>
<html>
<head>
	<title>Billing Receipt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script type="text/javascript" src="vendor/jquery/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
  <script type="text/javascript" src="js/printThis.js"></script>
  <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <style>
  p{
	  margin-bottom: 10px;
  }
  </style>
  
</head>
<body>

<div class="container-fluid">
	
<div class="row" id="hprint">
	<div class="col-md-offset-2 col-md-8 " >
    	<div class="page-header">
        <h3 style="margin-bottom: 0px;">CLAIMS FORM</h3>
        </div>
        <div class="row">
        	<div class="col-xs-2" >
            	<strong>Enrollee Name:</strong>
            </div>
            <div class="col-xs-3 ">
            	<p><?php echo $patient['name'] ?></p>
            </div>
            <div class="col-xs-1 ">
            	<strong>ID No.:</strong>
            </div>
            <div class="col-xs-2 ">
            	<p><?php echo $patient['hospital_id'] ?></p>
            </div>
            <div class="col-xs-1 ">
            	<strong>Age:</strong>
            </div>
            <div class="col-xs-1 ">
            	<p><?php echo 'ageval' ?></p>
            </div>
            <div class="col-xs-1 ">
            	<strong>Sex:</strong>
            </div>
            <div class="col-xs-1 ">
            	<p><?php echo $patient['sex'] ?></p>
            </div>
        </div>
        <div class="row" >
        	<div class="col-xs-2" >
            	<strong>Attendance Date:</strong>
            </div>
            <div class="col-xs-2 ">
            	<p><?php echo $billinfo['attendance_date'] ?></p>
            </div>
            <div class="col-xs-2 ">
            	<strong>Admission Date:</strong>
            </div>
            <div class="col-xs-2 ">
            	<p><?php echo $billinfo['admission_date'] ?></p>
            </div>
            <div class="col-xs-2 ">
            	<p>Discharge Date:</p>
            </div>
            <div class="col-xs-2 ">
            	<p><?php echo $billinfo['discharge_date'] ?></p>
            </div>
            
        </div>
        <!--<div class="row" >
        	<div class="col-xs-3" >
            	<strong>Diagnosis:</strong>
            </div>
            <div class="col-xs-9 ">
            	<p><?php echo '0000-00-00' ?></p>
            </div>
        </div>-->
        <div class="row" >
        	<div class="col-xs-3" >
            	<strong>Authorization Code:</strong>
            </div>
            <div class="col-xs-9 ">
            	<p><?php echo $billinfo['apcode'] ?></p>
            </div>
        </div>
        <div class="row" >
        	<div class="col-xs-3" >
            	<strong>Complaints:</strong>
            </div>
            <div class="col-xs-9 ">
            	<p><?php echo $billinfo['complaint'] ?></p>
            </div>
        </div>
        <div class="row" >
        	<div class="col-xs-3" >
            	<strong>Examination:</strong>
            </div>
            <div class="col-xs-9 ">
            	<p><?php echo $billinfo['examination'] ?></p>
            </div>
        </div>
        <div class="row" >
        	<div class="col-xs-3" >
            	<strong>Findings:</strong>
            </div>
            <div class="col-xs-9 ">
            	<p><?php echo $billinfo['findings'] ?></p>
            </div>
        </div>
        <div class="row" >
        	<div class="col-xs-3" >
            	<strong>Investigation/Results:</strong>
            </div>
            <div class="col-xs-9 ">
            	<p><?php echo $billinfo['result'] ?></p>
            </div>
        </div>
        <div class="row" >
        	<div class="col-md-12">
            	<div class="table-responsive">
                  <table class="table serv-list">
                  <thead>
                  <tr>
                  <th>Services</th>
                  <th width="20%" >Quantity</th>
                  <th width="20%" >Amount</th>
                  </tr>
                  </thead>
                  
                  </thead>
                  <tbody>
                  <?php
				  $total = 0;
				  foreach($services as $serv){
					  $total += $serv['charge'];
					  echo "<tr>";
					  echo "<td>".$serv['name']."</td>";
					  echo "<td>".$serv['quantity']."</td>";
					  echo "<td>".$serv['charge']."</td>";
					  echo "</tr>";
				  }
				  ?>
                  </tbody>
                  </table>
               </div>
                
            </div>
           
 			<b>
            <div class="col-md-offset-8 col-md-1 col-xs-2">
            	<span>Total(N):</span>
            </div>
            <div class="col-md-2 col-xs-10 hv">
            	<span><?php echo $total ?></span>
            </div>
            
        	</b>
            
        </div>
        
        <div class="row" style="margin-top:20px" >
        	<div class="col-xs-3">
            	<span>Patient's Name / Signature:</span>
            </div>
            <div class= "col-xs-5 ">
            	
            </div>
        
            <div class="col-xs-1">
            	<span>Sign:</span>
            </div>
            <div class=" col-xs-3">
            	<span>Management</span>
            </div>
            
        </div>
    </div>

</div>
    
</div>


</body>
</html>