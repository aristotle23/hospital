<?php
require_once "script/ini.php";

$name = null;
$type = null;
$expdate = null;
$category = null;
$supplierId = null;
$quantity = null;
$gname = null;
$level = null;
$unit = null;
$cost = null;
$price = null;
if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save') {
    $name = $_REQUEST['name'];
    $type = $_REQUEST['type'];
    $expdate = $_REQUEST['expdate'];
    $category = $_REQUEST['category'];
    $supplier = $_REQUEST['supplier'];
    $quantity = $_REQUEST['quantity'];
    $gname = $_REQUEST['gname'];
    $level = $_REQUEST['level'];
    $unit = $_REQUEST['unit'];
    $cost = $_REQUEST['cprice'];
    $price = $_REQUEST['price'];
    if(!isset($_REQUEST['med'])) {
        try {
            $medicineId = $db->executeGetId("insert into medicine (name, type, expdate, gname, category, quantity, unit, cost, low_level, selling_price)" .
                " VALUES (?,?,?,?,?,?,?,?,?,?)", array($name, $type, $expdate, $gname, $category, $quantity, $unit, $cost, $level, $price));
        }catch (Exception $e){
            header("location: ?failed=Medicine already exist");
        }
        if ($medicineId) {
            $db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);", array("Medicine added with name" . $name, $_SESSION['user_id']));
            $db->execute("insert into supply (item_id, supplier_id,quantity) VALUES (?,?,?)",array($medicineId,$supplier,$quantity));
            header("location: ?success=Medicine saved successfully");
            exit;
        } else {
            header("location?failed=Unable to save medicine due to an unknown error");
        }
    }elseif(isset($_REQUEST['med'])){
        $db->execute("update medicine set name = ?, type = ?, expdate = ?, gname = ?, category = ?, quantity = ?, unit = ?, cost = ?,".
            " low_level = ?, selling_price = ? where id = ?",array($name, $type, $expdate, $gname, $category,$quantity, $unit, $cost, $level, $price,$_REQUEST['med']));
        header("location:medicine_list.php?success=Medicine updated");
        exit;
    }
}
if(isset($_REQUEST['med']) && isset($_REQUEST['edit'])){

}
if(isset($_REQUEST['med'])){
    $medicine = $db->getOne("select * from medicine where id = ?",array($_REQUEST['med']));
    $name = $medicine['name'];
    $type = $medicine['type'];
    $expdate = $medicine['expdate'];
    $category = $medicine['category'];
    $supplierId = $medicine["supplier_id"];
    $quantity = $medicine['quantity'];
    $gname = $medicine['gname'];
    $level = $medicine['low_level'];
    $unit = $medicine['unit'];
    $cost = $medicine['cost'];
    $price = $medicine['selling_price'];
}
require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Medicine</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <?php
                if(isset($_REQUEST['med'])){
                    echo "<input type='hidden' name='med' value='".$_REQUEST['med']."' />";
                }
                ?>
                <div class="row ">
                    <div class="col-lg-12 ">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="control-label" >Name</label>
                                <input type="text" class="form-control" name="name" required="required" value="<?php echo $name ?>" >
                            </div>
                        </div>
                        <div class="col-lg-4">

                            <div class="form-group ">
                                <label class="control-label" >Type</label>
                                <input type="text" class="form-control" name="type" value="<?php echo $type ?>" />
                                <!--<p class="help-block ">Hospital Number Already Exists.</p>-->
                            </div>

                        </div>
                        <div class="col-lg-4" >

                            <div class="form-group">
                                <label>Expiring Date</label>
                                <input class="form-control date" type="text" name="expdate" required="required" value="<?php echo $expdate ?>" />
                            </div>

                         </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                <div class="row">
                    <div class="col-lg-12">

                         <div class="col-lg-4">
    
                            <div class="form-group ">
                                <label class="control-label" >Catetory of Medicine</label>
                                <input type="text" class="form-control" name="category" required="required" value="<?php echo $category ?>" />
                                
                            </div>
                                        
                         </div>

                         <div class="col-lg-4">

                            <div class="form-group ">
                                <label class="control-label" >Supplier</label>
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
    					<div class="col-lg-4">
                         <div class="form-group ">
                                <label class="control-label" >Quantity</label>
                             <input type="number" class="form-control" name="quantity" required="required" value="<?php echo $quantity ?>" >
                            </div>
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-lg-12">
	                    <div class="col-lg-4">

                            <div class="form-group">
                                <label class="control-label" >Unit</label>
                                <input type="text" class="form-control" value="<?php echo $unit ?>" name="unit" />
                            </div>

                         </div>

                    	<div class="col-lg-4">

                            <div class="form-group ">
                                <label class="control-label" >Cost Price</label>
                                <input type="text" class="form-control" name="cprice" required="required" value="<?php echo $cost ?>" >
                            </div>

                         </div>
    					<div class="col-lg-4">

                            <div class="form-group ">
                                <label class="control-label" >Selling Price</label>
                                <input type="text" class="form-control" name="price" required="required" value="<?php echo $price ?>" >

                            </div>

                         </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-4">

                            <div class="form-group ">
                                <label class="control-label" >Generic Name</label>
                                <input type="text" class="form-control" name="gname" required="required" value="<?php echo $gname ?>" >
                            </div>

                        </div>
                        <div class="col-lg-4">
                            <div class="form-group ">
                                <label class="control-label" >Low Stock Level</label>
                                <input type="text" class="form-control" name="level" required="required" value="<?php echo $level ?>" >
                            </div>

                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
            	
                <div class="row" style="margin-top: 50px">
                    <div class="col-lg-12">
                    <div class=" col-lg-6 col-lg-offset-3">
                        <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block" name="submit" value="save">Save</button>
                    </div>
                    </div>
                <!-- /.col-lg-12 -->
            	</div>
            </form>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    <!-- /#wrapper -->

    <!-- jQuery -->
    
<?php
require_once "template/footer.php";
