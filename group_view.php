<?php
if(!isset($_REQUEST['gid'])){
	header("location:".$_SERVER['HTTP_REFERER']);
}
require_once "script/ini.php";
require_once "script/rbilling.php";
require_once "script/helper.php";

$group = $db->getOne("select * from patient_group where id = ?",array($_REQUEST['gid']));
$gpatient = $db->getAll("select * from patient where group_id = ?",array($_REQUEST['gid']));
$parray = array();
$ptr = "";
foreach($gpatient as $patient){
	$idx = $patient['hospital_no'];
	$name = $patient['name'];
	$sex = $patient['sex'];
	$phone = $group['phone'];
	$address = $group['address'];
	array_push($parray,$patient['id']);
	$ptr .= "<tr><td>".$idx."</td><td >".$name."</td><td >".$sex."</td><td >".$phone."</td>
			<td>".$address."</td><td>
			<a class='btn btn-primary btn-block' href='patient_view.php?pid=".$patient['id']."'>View</a>
			</td></tr>";
	
}
$billings = new groupBilling($parray);
$pbill = $billings->getBilling();
$btr = "";
for($x = 0; $x < count($pbill) ; $x++){
	$bill = $pbill[$x][2];
	$btr .= "<tr>";
	for($i = 0 ; $i < count($bill) ; $i++){
		$btr .= "<td>".$bill[$i]."</td>";
	}
	$btr .= "<td>";
		
		$btr .= " <a title='Pay Debt' href='debt_billing.php?pid=".$pbill[$x][1]."&bid=".$pbill[$x][0]."' class='btn 
		btn-primary debt' >	<i class='fa fa-reply'></i></a> ";
		$btr .= " <a title='Print' href='receipt.php?pid=".$pbill[$x][1]."&bill=".$pbill[$x][0]."' class='btn 
		btn-primary print'><i class='fa fa-print'></i></a>";

	$btr .="</td></tr>";
	
}
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?php echo $name = $group['name']; ?> 
                    <small class="pull-right"><?php echo $billings->gbal ?></small></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            	<div class="col-lg-1 pull-left" style="margin-bottom: 20px; padding-top:25px; padding-right:0px">
                	<a title="Register patient to HMO <?php echo ($group['type'] == 0 ? "family" : "organisation") ?>" 
                    href="register.php?gid=<?php echo $_REQUEST['gid'] ?>" class="btn btn-default  " ><i class="fa fa-plus"></i> Patient</a>
                </div>  
                   
                       
                <!--<div class="col-lg-2 pull-right" style="margin-bottom: 20px">
                	<label>Billing</label>
                	<a href="#" data-toggle="modal" data-target="#hmo-serivce" class="btn btn-block btn-primary " >Upload</a>
                </div>-->
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
										$phone = $group['phone'];
										$address = $group['address'];
										$email = $group['email'];
										$id = $group['hospital_id'];
										$regdate = date_create($group['date']);
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
                        
                            <table width="100%" class="table table-striped table-bordered table-hover dataTables-example" >
          
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
                                <tbody>
                                <?php
									
									echo $ptr;
								?>
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
            
            
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           <h4>Billing </h4>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body ">
                        	
                            <table width="100%" class="table table-striped table-bordered table-hover  dataTables-example" >
          
                                <thead>
                                    <tr>
                                        <th width="12%">Date</th>
                                        <th>Name</th>
                                        <th>Services</th>                                        
                                        <th>Amt. Charged</th>
                                        <th >Paid</th>
                                        <th >Balance</th>
                                        <th >Task</th>
                                    </tr>
                                </thead>
                                <tbody >
                                <?php
								
								
                                  echo $btr;  
                                ?>  
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