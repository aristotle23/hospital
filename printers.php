<?php
require_once "script/ini.php";

if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
	$id = $db->getOne("select id from settings where user_id = ?",array($_SESSION['user_id']));
	if($id){
		$db->execute("update settings set printer = ? where id = ? ",array($_REQUEST['name'],$id['id']));
		header("location:?success=You have set your printer");
	}else{
		$id = $db->executeGetId("insert into settings (user_id, printer) values (?,?)",array($_SESSION['user_id'], $_REQUEST['name']));
		if($id){
			header("location:?success=You have set your printer");
		}else{
			header("location:?failed=Unable to set your printer");
		}
		
	}
	
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Set your printer</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            	
                
                <div class="form-group">
                <label class="col-md-2 control-label">Printer Share Name</label>
                <div class="col-md-4">
                <input type="text" class=" form-control" name="name" required="required" />
                </div>
                </div>
                
                
                <div class="form-group">
                <div class="col-md-2 col-md-offset-2">
                <input type="submit" class="btn btn-block btn-primary btn-lg" value="Save" name="submit" required="required" />
                </div>
                
                
                
                
                
            </form>
            </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    <!-- /#wrapper -->

    <!-- jQuery -->
    
<?php
require_once "template/footer.php";
?>