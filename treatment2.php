<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
	$drug = "";
	$routine = "";
	$dosage = "";
	
	if(isset($_REQUEST['edit'])){
		
		$treatment = $db->getOne("select dosage, routine, drug from treatment where id = ?",array($_REQUEST['edit']));
		if(!$treament){
			header("location:index.php");
		}
		$drug = $treatment['drug'];
		$routine = $treatment['routine'];
		$dosage = $treatment['dosage'];
	}
	
}else{
	header("location:index.php");
}

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' && $_REQUEST['pid']){
	$drug = $_REQUEST['drug'];
	$routine = $_REQUEST['routine'];
	$dosage = $_REQUEST['dosage'];
	if(isset($_REQUEST['edit'])){
		$db->execute("update table treatment set drug = ?,routine = ? , dosage = ? where id = ?",array($drug,$dosage,$routine,$_REQUEST['edit']));
		header("location:?success=Treament edited successfully");
	}else{
		$id = $db->executeGetId("INSERT INTO `treatment` (`drug`, `dosage`, `routine`) VALUES (?, ?, ?)",array($drug,$dosage,$routine));
		if($id){
			header("location:?success=Treament saved successfully");
		}
	}
	//print_r($_REQUEST);
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
<style>
#diag-result-con{
	display:none
}
</style>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Patient Treatment <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                
                <form class="form-horizontal" role="form" method="post">
                <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <?php
				if(isset($_REQUEST['edit'])){
					echo '<input type="hidden" name="edit" value="'.$_REQUEST['pid'].'"  />';
				}
				?>

                <div class="form-group">
                	
                    <label class="control-label col-md-1">Drug</label>

                    <div class="col-md-4">
                    	<input type="text" name="drug" class="form-control" value="<?php echo $drug   ?>" />
                    </div>
                </div>
                <div class="form-group">
                	
                    <label class="control-label col-md-1">Dosage</label>

                    <div class="col-md-4">
                    	<input type="text" name="dosage" class="form-control" value="<?php echo $dosage   ?>" />
                    </div>
                </div>
                <div class="form-group">
                	
                    <label class="control-label col-md-1">Routine</label>

                    <div class="col-md-4">
                    	<input type="text" name="routine" class="form-control" value="<?php echo $routine   ?>" />
                    </div>
                </div>
                
               
                
                
                <div class="form-group" style="margin-top:30px">
                 
                  
                  <div class="col-md-offset-1 col-md-4 ">
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
