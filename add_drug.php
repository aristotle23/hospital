<?php
require_once "script/ini.php";
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
  $name = $_REQUEST['name'];
  $tablet = $_REQUEST['pertablet'];
  $packet = $_REQUEST['ttlpacket'];
  $ttltablet = intval($tablet) * intval($packet);
  $exist = $db->getOne("select id, drug, pertablet from pharmacy where drug like  ?",array($name."%"));
  $create = true;
  if($exist){
	  if($exist['pertablet'] ==  $tablet){
		  $db->execute("update pharmacy set packet = packet + ?, ttltablet = ttltablet + ? where id = ?",array($packet,$ttltablet,$exist['id']));
		  $create = false;
	  	  header("location:add_drug.php?success=Drug updated successfully");
	  }
	  $name = $name. ' ('.$tablet.' tablet/packet)';
  }
  if($create){
  $id = $db->executeGetId("INSERT INTO `pharmacy` (`drug`, `pertablet`, `packet`, `ttltablet`) VALUES (?, ?, ?, ?);",
							  array($name,$tablet,$packet,$ttltablet));
  if($id){
	 header("location:?success=New drug created successfully");
  }else{
	 header("location:?failed=Unable to create drug");
  }}
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Drug</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
              <div class="col-lg-12">
              	<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Note</b> When using same name and tablet/packet new drug will not be added it will only add the packet to the old total packet
                 </div>
                 <!--<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Categorize services</b> are services that are under any service title or type e.g. LAB->TEST->Urinary Test , LAB->TEST->Blood Test 
                 </div>-->
              </div>
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="add_drug.php" method="post">
            	<input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                
                <div class="form-group">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-4">
                <input type="text" class="form-control" list="drug" name="name" autocomplete="off" />
                <datalist id="drug">
                <?php
					$drugs = $db->getAll("select drug from pharmacy ");
					foreach ($drugs as $drug){
						echo '<option value="'.$drug['drug'].'">';
					}
					
				?>
                </datalist>
                </div>
                </div>
                
                <div class="form-group">
                <label class="col-md-2 control-label">tablet / Packet</label>
                <div class="col-md-4">
                <input type="number" class=" form-control" min="1" value="1" name="pertablet" required="required" />
                </div>
                </div>
                
                <div class="form-group">
                <label class="col-md-2 control-label">Total Packet</label>
                <div class="col-md-4">
                <input type="number" class=" form-control" min="1" value="1" name="ttlpacket" required="required" />
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