<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
}else{
	header("location:index.php");
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' && $_REQUEST['pid']){
	
	$vitals = $_REQUEST['vital'];
	$signs = $_REQUEST['sign'];
	$pid = $_REQUEST['pid'];
	for($i = 0 ; $i < count($vitals) ; $i++){
		$vital = $vitals[$i];
		$sign = $signs[$i];
		$id = $db->executeGetId("INSERT INTO `vitals` (`patient_id`, `vitals`, `sign`) VALUES ( ?, ?, ?)",array($pid,$vital,$sign));
	}
	header("location:patient_view.php?pid=".$pid."&success=Vital sign saved successfully");
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Vitals Signs <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                
                <form class="form-horizontal" role="form" id="vitals-form" method="post">
                <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <div class="form-group " id="vitals-group">
                
                 <div class="col-md-1">
                      <button class="btn btn-block btn-primary  "  id="vitals-plus" ><i class="fa fa-plus"></i></button>
                  </div>
                 
                  <div class="col-md-4" >
                  	<input class="form-control" id="vitals-vital"  placeholder="Vital"  />
                  </div>
                  <div class="col-md-2" id="diag-result-con" >
                  	<input class="form-control" id="vitals-sign"  placeholder="Sign"   />
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
                
                
                <div class="form-group" style="margin-top:30px">
                 
                  
                  <div class="col-md-offset-1 col-md-2 ">
                  <button type="submit" class="btn btn-block btn-primary " name="submit" value="save" ><b>Save</b></button>
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
