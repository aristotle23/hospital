<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(!isset($_REQUEST['npid']) && !isset($_REQUEST['date'])){
    header("location:index.php");
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save'){
	
	$ids = $_REQUEST['id'];
	$results = $_REQUEST['result'];
	for($i = 0 ; $i < count($ids) ; $i++){
		$id = $ids[$i];
		$result = $results[$i];
		if($result == "" || $result == null){
		    continue;
        }
		$db->execute("update nonpatient_lab set result = ? where id = ?",array($result,$id));
	}
	header("location:lab-nonhistory.php?success=Edit successful");
}
$nonPatient = $db->getOne("select * from nonpatient where id = ?",array($_REQUEST['npid']));

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Laboratory <small><?php echo $nonPatient['name'] ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                
                <form class="form-horizontal" role="form"  method="post">
                    <span class="help-block form-group col-md-12"><b>Note:</b> Any Changes made and not saved before page reload will be lost </span>
                <?php
				$result = $db->getAll("select test, id, result from nonpatient_lab where date = ? and nonpatient_id = ? ",
										array($_REQUEST['date'],$_REQUEST['npid']));
				foreach($result as $lab){
					echo '<div class="form-group " id="vitals-group">
                  <div class="col-md-4" >
				  <input type="hidden" name="id[]" value="'.$lab['id'].'" />
				  <input class="form-control" disabled="disabled"   value="'.$lab['test'].'"  />';
				  echo ' </div>
                  <div class="col-md-8" id="diag-result-con" >
                  <textarea rows="2" name="result[]" class="form-control" placeholder="Result">'.$lab['result'].'</textarea>
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
