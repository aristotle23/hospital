<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid']) && isset($_REQUEST['date'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
}else{
	header("location:index.php");
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' && $_REQUEST['pid']){
	
	$ids = $_REQUEST['id'];
	$results = $_REQUEST['result'];
	$pid = $_REQUEST['pid'];
    $date = date("Y-m-d");
	for($i = 0 ; $i < count($ids) ; $i++){
		$id = $ids[$i];
		$result = $results[$i];
		if($result == "" || $result == null){
		    continue;
        }
		$db->execute("update diagnosis set result = ?, lab = 2, lab_date = ? where id = ?",array($result,$date,$id));
	}
	header("location:lab-history.php?success=Edit successful");
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Laboratory <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                
                <form class="form-horizontal" role="form"  method="post">
                <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <?php
				$result = $db->getAll("select diagnose, id, result from diagnosis where patient_id = ? and lab_date = ? and payment = 0 and lab = 2 and services_id is null",
										array($_REQUEST['pid'],$_REQUEST['date']));
				foreach($result as $diag){
					echo '<div class="form-group " id="vitals-group">
                  <div class="col-md-4" >
				  <input type="hidden" name="id[]" value="'.$diag['id'].'" />
				  <input class="form-control" disabled="disabled"   value="'.$diag['diagnose'].'"  />';
				  echo ' </div>
                  <div class="col-md-8" id="diag-result-con" >
                  
                  <textarea rows="2" name="result[]" class="form-control" placeholder="Result">'.$diag['result'].'</textarea>
                  </div>	
                </div>';
				}
				?>
                
                  	
                 
                
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
                
                
                <div class="form-group" style="margin-top:30px">
                 
                  
                  <div class="col-md-2 ">
                  <button type="submit" class="btn btn-block btn-primary " name="submit" value="save" ><b>Edit</b></button>
                  </div>
                  
                </div>
                <!-- /.row -->
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<?php
require_once "template/footer.php";
?>
