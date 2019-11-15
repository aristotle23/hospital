<?php
if(!isset($_REQUEST['med'])){
    header("location: medicine_list.php");
}
require_once "script/ini.php";
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
    $quantity = $_REQUEST['quantity'];
    $supplier = $_REQUEST['supplier'];
    $medId = $_REQUEST['med'];

    $db->execute("insert into supply (item_id, supplier_id, quantity) VALUES (?,?,?)",array($medId,$supplier,$quantity));
    $db->execute("update medicine set quantity = quantity + ? where id = ?",array($quantity,$medId));
    header("location:medicine_list.php?success=Top-up Successful");
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Top-up Medicine</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <input type="hidden" name="med" value="<?php echo $_REQUEST['med'] ?>" />
                <div class="form-group">
                <label class="col-md-1 control-label">Quantity</label>
                <div class="col-md-5">
                <input type="text" class=" form-control" name="quantity" required="required" autocomplete="off" />
                </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 control-label">Supplier</label>
                    <div class="col-md-5">
                        <select name="supplier" class="form-control" >
                            <option value="null" disabled selected>Please select supplier</option>
                            <?php
                            $supplierAr = $db->getAll("select * from supplier ");
                            foreach($supplierAr as $supplier ){
                                if($supplierId == $supplier['id']){
                                    echo "<option selected value='".$supplier['id']."' >".$supplier['name']."</option>";
                                    continue;
                                }
                                echo "<option value='".$supplier['id']."' >".$supplier['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                </div>
                <div class="form-group">
                <div class="col-md-3 col-md-offset-1">
                <button type="submit" class="btn btn-block btn-primary btn-lg" value="Save" name="submit" required="required">Top-up</button>
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