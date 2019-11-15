<?php
require_once "script/ini.php";
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
  
}
$ward = $db->getOne("select * from ward where user_id = ? ",array($_SESSION['user_id']));

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Ward</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            	<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <div class="form-group">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-4">
                <input type="text" class="form-control" name="name" value="<?php echo $ward['name'] ?>" required="required" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Total Bed</label>
                <div class="col-md-4">
                <input type="number" class=" form-control" min="1" value="value="<?php echo $ward['ttl_bed'] ?>" " name="ttlbed" required="required" />
                </div>
                </div>
                
                <div class="form-group">
                <label class="col-md-2 control-label">Assigned Staff</label>
                <div class="col-md-4">
                <select name="staff" class="form-control" >
                <option disabled="disabled" selected="selected" >Please select...</option>
                <?php
					$result = $db->getAll("SELECT distinct(u.id), u.name, access FROM user u inner join access_right a on a.level = u.access_right  where a.level = ?",array(5));
					foreach ($result as $user){
						$chck = $db->getAll("SELECT id FROM ward WHERE user_id = ?", array($user['id']));
						if($chck){
							continue;
						}
						if($user){
						  echo '<optgroup label="'.$user['access'].'">';
						  
						  
							  
							  echo '<option value="'.$user['id'].'">'.$user['name'].'</option>';
						  
						  echo '</optgroup>';
						}
					}
				?>
                </select>
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