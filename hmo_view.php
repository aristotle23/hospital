<?php
if(!isset($_REQUEST['hmo'])){
	header("location:".$_SERVER['HTTP_REFERER']);
}
require_once "script/ini.php";
require_once "script/rbilling.php";
require_once "script/helper.php";

require_once "script/php-excel-reader/excel_reader2.php";
require_once "script/SpreadsheetReader.php";
require_once "script/hmo_upload.php";

if(isset($_REQUEST['payment']) && $_REQUEST['payment'] == 'hmo'){
	$hmo = $_REQUEST['hmo'];
	$date = $_REQUEST['date'];
	$amount = $_REQUEST['amount'];
	$update = $db->getOne("select id from hmo_payment where date = ? and hmo_id = ?",array(trim($date),$hmo));
	if($update){
		$db->execute("update hmo_payment set amount = amount + ? where id = ?",array($amount,$update['id']));
	}else{
		$db->execute("INSERT INTO `hmo_payment` (`amount`, `hmo_id`,`date`) VALUES (?, ?, ?)",array($amount,$hmo,trim($date)));
	}
	
	
	header("location:?hmo=".$hmo."&success=HMO payment made successfully");
}
if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'service_category'){
	$name = $_REQUEST['name'];
	$hmo = $_REQUEST['hmo_id'];
	$db->execute("INSERT INTO `hmo_service_category` (`name`, `hmo_id`) VALUES (?, ?)",array($name,$hmo));
	header("location:?hmo=".$hmo."&success=Category saved successfully");
}
$hmo = $db->getOne("select * from hmo where id = ?",array($_REQUEST['hmo']));

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $name = $hmo['name']; ?> 
                    <small class="pull-right"><?php echo helperClass::hmoBalance($hmo['id']) ?></small></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-1 pull-left" style="margin-bottom: 20px; padding-top:25px; padding-right:0px">
                	<a title="Register patient to HMO" href="#" data-toggle="modal" data-target="#hmo-patient" class="btn btn-default  " >
                    <i class="fa fa-plus"></i> Patient</a>
                </div>  
                <div class="col-lg-1 pull-left" style="margin-bottom: 20px; padding-top:25px; padding-right:0px">
                	<a title="Register patient to HMO" href="#" data-toggle="modal" data-target="#hmo-category" class="btn btn-default  " >
                    <i class="fa fa-plus"></i> Service Category</a>
                </div>           
                <div class="col-lg-2 pull-right" style="margin-bottom: 20px">
                	<label>Billing</label>
                	<a href="#" data-toggle="modal" data-target="#hmo-serivce" class="btn btn-block btn-primary " >Upload</a>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body ">
                        	<div class="table-responsive">
                            <table width="100%" class="table table-striped table-bordered table-hover" >
          
                                <thead>
                                    <tr>
                                    	<th >ID</th>
                                       	<th >Reg. Date</th>                                       
                                        <th >Address</th>
                                        <th >Phone</th>
                                        <th >Email</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
                                <?php
                                    
									$tr = "";
										$phone = $hmo['phone'];
										$address = $hmo['address'];
										$email = $hmo['email'];
										$id = $hmo['hospital_id'];
										$regdate = date_create($hmo['date']);
										$regdate = date_format($regdate,"Y-m-d");
										$tr .= "<tr><td>".$id."</td><td >".$regdate."</td><td >".$address."</td><td >".$phone."</td><td>".$email."</td></tr>";
									
									echo $tr;
                                    
                                    ?>
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                         </div>   
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4>Patients</h4>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body ">
                        
                            <table width="100%" class="table table-striped table-bordered table-hover patients-hmo" >
          
                                <thead>
                                    <tr>
                                    	<th >ID</th>
                                        <th>Full Name</th>
                                        <th width="8%">Sex</th>
                                        <th >Phone</th>                                        
                                        <th >Address</th>
                                        <th width="12%">Tasks</th>
                                    </tr>
                                </thead>
                                
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4>Billing</h4>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body ">
                        
                            <table width="100%" class="table table-striped table-bordered table-hover hmo-billing" >
          
                                <thead>
                                    <tr>
                                        <th width="12%">Date</th>
                                        <th>Company ID.
                                        <th>Name</th>
                                        <th>Phone</th>                                        
                                        <th>Address</th>
                                        <th >Services</th>
                                        <th >Amt. Charged</th>
                                        <th >Task</th>
                                    </tr>
                                </thead>
                                <tbody >
                                
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4>Billing Record</h4>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body ">
                        
                            <table width="100%" class="table table-striped table-bordered table-hover hmo-billrecord" >
          
                                <thead>
                                    <tr>
                                        <th >Mon-Year</th>
                                        <th>TTL Patients</th>
                                        <th>TTL Services</th>
                                        <th>Amt. Charge</th>                                        
                                        <th>Amt. Paid</th>
                                        <th>Balance</th>
                                        <th>Task</th>
                                    </tr>
                                </thead>
                                <tbody >
                                
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
        </div>
        <!-- /#page-wrapper -->
<script type="text/javascript" async="async">
$(".editreq").on("click",function(e){
	$this = $(this);
	pid = $this.data("pid");
	pbill = $this.data("pbill")
	
	$.ajax({
		url:"script/ajax.php?state=editalert&pid="+pid+"&pbill="+pbill,
		dataType:"json",
		success: function(e){
			if(e == true){
				location = location.origin+location.pathname+"?pid="+pid+"&success=Edit Request Sent Successfully"
			}
		}
	})
})
</script>

<?php
require_once "template/footer.php";
?>