<?php
require_once "script/patient.php";
require_once "script/ini.php";
if(isset($_REQUEST['pid'])){
	$hmo = $db->getOne("select hmo_id, name from hmo_patient where id = ?",array($_REQUEST['pid']));
	$patient = $hmo['name'];
	$hmoid = $hmo['hmo_id'];
	
}else{
	header("location:index.php");
}
$window = false;
if(isset($_REQUEST['submit']) && isset($_REQUEST['pid'])){
	if(isset($_REQUEST['service']) && isset($_REQUEST['cost']) && isset($_REQUEST['quantity'])){
		$services = $_REQUEST['service'];
		$hmo = $_REQUEST['hmoid'];
		$amounts = $_REQUEST['cost'];
		$quantities = $_REQUEST['quantity'];
		$pid = $_REQUEST['pid'];
		$attdate = ( !isset($_REQUEST['attdate']) || $_REQUEST['attdate'] == "" ? NULL : $_REQUEST['attdate']);
		$admdate = ( !isset($_REQUEST['admdate']) || $_REQUEST['admdate'] == "" ? NULL : $_REQUEST['admdate']);
		$disdate = ( !isset($_REQUEST['disdate']) || $_REQUEST['disdate'] == "" ? NULL : $_REQUEST['disdate']);
		$complaint = ( !isset($_REQUEST['complaint']) || $_REQUEST['complaint'] == "" ? NULL : $_REQUEST['complaint']);;
		$examination = ( !isset($_REQUEST['examination']) || $_REQUEST['examination'] == "" ? NULL : $_REQUEST['examination']);
		$finding = ( !isset($_REQUEST['finding']) || $_REQUEST['finding'] == "" ? NULL : $_REQUEST['finding']);;
		$result = ( !isset($_REQUEST['result']) || $_REQUEST['result'] == "" ? NULL : $_REQUEST['result']);
		$apcode  = ( !isset($_REQUEST['apcode']) || $_REQUEST['apcode'] == "" ? NULL : $_REQUEST['apcode']);
		$infoid = $db->executeGetId("INSERT INTO `hmo_billing_info` (`attendance_date`, `admission_date`, `discharge_date`, `complaint`, `examination`, `findings`, `result`,`apcode`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",array($attdate,$admdate,$disdate,$complaint,$examination,$finding,$result,$apcode));
		if($infoid){
			$initial = $db->getOne("select id, info_id from hmo_billing where hmo_patient_id = ? and date = ? and hmo_id = ?",
									array($pid,$date,$hmo));
			if($initial){
				$id = $initial['id'];
				$db->execute("delete from hmo_billing_info where id = ?",array($initial['info_id']));
				$db->execute("update hmo_billing set ttl_service = ttl_service + ?, charge = charge + ?, info_id = ? where id = ?",
							array(count($services), array_sum($amounts),$infoid,$id));
			}else{
				$id = $db->executeGetId("INSERT INTO `hmo_billing` (`ttl_service`, `hmo_patient_id`, `date`, `charge`,`hmo_id`,`info_id`) 
									VALUES (?, ?, ?, ?, ?, ?)", array(count($services),$pid,$attdate,array_sum($amounts),$hmo,$infoid));
			}
			
			
			for($i = 0 ; $i < count($services) ; $i++){
				$service = $services[$i];
				$amount = $amounts[$i];
				$quantity = $quantities[$i];
				$db->execute("INSERT INTO `hmo_billing_services` (`hmo_services_id`, `hmo_billing_id`, `charge`,`quantity`) VALUES (?, ?, ?, ?)", 
										array($service,$id,$amount,$quantity));
			}
			
			header("location:hmo_view.php?hmo=".$hmo.'&success=HMO bill created successfully');
		}
		
	}
	
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Patient Billing <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                <div class="row" style="margin-bottom:20px;">
                  
                  <div class="col-md-offset-1 col-md-2" ><h4>TOTAL (&#8358;) </h4></div><div class="col-md-6" ><h4 id="hmo-total">0.0</h4></div>
                </div>
                <form class="form-horizontal" role="form" id="hmo-form" method="post" action="">
                <input type="hidden" value="<?php echo $_REQUEST['pid'] ?>" name="pid"  />
                <input type="hidden" value="<?php echo $hmoid ?>" name="hmoid"  />
                <div class="form-group" >
                  <label class="col-md-1 control-label">Date</label> 
                  <div class="col-md-2">
                  	<input class="form-control date" placeholder="YYYY-MM-DD"  required="required" name="attdate"/>
                  </div>
                  <label class="col-md-2 control-label">Admission Date</label> 
                  <div class="col-md-2">
                  	<input class="form-control date" placeholder="YYYY-MM-DD"   name="admdate"/>
                  </div>
                  <label class="col-md-2 control-label">Discharge Date</label> 
                  <div class="col-md-2">
                  	<input class="form-control date" placeholder="YYYY-MM-DD"   name="disdate"/>
                  </div>
                </div>
                <div class="form-group">
                	<label class="col-md-1 control-label">Complaint</label> 
                  <div class="col-md-4">
                  	<textarea class="form-control" cols="3" name="complaint"></textarea>
                  </div>
                  <label class="col-md-2 control-label">Examination</label> 
                  <div class="col-md-4">
                  	<textarea class="form-control" cols="3" name="examination"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-1 control-label">Findings</label> 
                  <div class="col-md-4">
                  	<textarea class="form-control" cols="3" name="finding"></textarea>
                  </div>
                  <label class="col-md-2 control-label">Result</label> 
                  <div class="col-md-4">
                  	<textarea class="form-control" cols="3" name="result"></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-1 control-label">AP Code</label> 
                  <div class="col-md-4">
                  	<input type="text" class="form-control"  name="apcode">
                  </div>
                </div>
                <div class="form-group" id="hmo-group">
                 <div class="col-md-1">
                      <button class="btn btn-block btn-primary " id="hmo-plus" ><i class="fa fa-plus"></i></button>
                  </div>
                  <div class="col-md-4">
                    <select class="form-control"  id="hmo-service"  >
                    <option  disabled="disabled" selected="selected">Select service...</option>
                    
                    <?php
						$cats = $db->getAll("select id, name from hmo_service_category where hmo_id = 0 or hmo_id = ?",array($hmoid));
						foreach($cats as $cat){
							echo '<optgroup label="'.ucwords($cat['name']).'">';
							$services = $db->getAll("SELECT s.id, s.name, cost FROM hmo_services s inner join hmo_service_category hsc on s.cat_id = 
													hsc.id where s.hmo_id = ? and hsc.id = ?",array($hmoid,$cat['id']));
							foreach($services as $service){
								print '<option value="'.$service['id'].'" data-charge="'.$service['cost'].'">'.ucwords($service['name']).'</option>';
							}
							echo '</optgroup>';
						}
					?>
                    </select>
                  </div>
                  <div class="col-md-2">
                  	<input class="form-control" id="hmo-amount" placeholder="Service Cost" dir="rtl" onkeypress="return isNumber(event)" />
                  </div>
                  <div class="col-md-2">
                  	<input class="form-control" id="hmo-quantity" placeholder="Quantity" dir="rtl"  onkeypress="return isNumber(event)" />
                  </div>
                  	
                </div>
                
                <!--<div class="form-group">
                  <div class="col-md-1">
                      <button class="btn btn-block btn-primary billminus" ><i class="fa fa-minus"></i></button>
                  </div>
                  <div class="col-md-4">
                    <select class="form-control" name="service[]">
                    <option>Please Select...</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                  	<input class="form-control" placeholder="0.00" dir="rtl" required="required" name="amount[]"/>
                  </div>
                </div>-->
                
                
                <div class="form-group">
                 
                 <!-- <div class="col-md-offset-1 col-md-2">
                    <button type="submit" class="btn btn-block btn-primary " name="submit" value="submit" ><b>print</b></button>
                  </div>-->
                  <div class="col-md-2">
                  <button type="submit" class="btn btn-block btn-primary " name="submit" value="save" ><b>Save</b></button>
                  </div>
                  
                </div>
                <!-- /.row -->
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<script>
$("#hmo-service").on("change",function(e){
	$this = $(this);
	charge = $this.find("option:selected").data("charge");
	
	$("#hmo-amount").val(charge);
})
</script>
<?php
if(isset($window) && !empty($window) && $_REQUEST['submit'] == 'submit'){
	print  "<script>";
	print "window.open('".$window."','_blank','width=233,height=500');";
	
	//print "setTimeout(' window.location.href =".' "billing_view.php"'."; ',1000)";
	print "</script>";
	print $window;
}
require_once "template/footer.php";
?>
