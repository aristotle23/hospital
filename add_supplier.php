<?php
require_once "script/ini.php";
$name = null;
$address = null;
$phone = null;
$email = null;
if(isset($_REQUEST['sp'])){
    $supplier = $db->getOne("select * from supplier where id = ?",array($_REQUEST['sp']));
    $name = $supplier['name'];
    $phone = $supplier['phone'];
    $email = $supplier['email'];
    $address = $supplier['address'];
}
if(isset($_REQUEST['register']) && $_REQUEST['register'] == 'save'){

$name = $_REQUEST['name'];
$address = $_REQUEST['address'];
$phone = $_REQUEST['phone'];
$email = $_REQUEST['email'];
if(!isset($_REQUEST['sp'])) {
    $supplierId = $db->executeGetId("INSERT INTO supplier (`name`, `address`, `phone`,`email`) 
								VALUES (?, ?, ?, ?)", array($name, $address, $phone, $email));
    if ($supplierId) {
        $db->execute("INSERT INTO `log` (`activity`, `user_id`) VALUES (?, ?);", array("new supplier added by", $_SESSION['user_id']));
        header("location:?success=supplier registered successfully");

    } else {
        header("location:?failed=unable to register supplier due to unknown error");
    }
}elseif(isset($_REQUEST['sp'])) {
    $db->execute("update supplier set name = ?, address = ?, phone = ?, email = ? where id = ?", array($name, $address, $phone, $email, $_REQUEST['sp']));
    header("location:supplier_list.php?success=Supplier edited successfully");
    exit;
}
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">New Supplier</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <?php
                if(isset($_REQUEST['sp'])){
                    echo "<input type='hidden' name='sp' value='".$_REQUEST['sp']."' />";
                }
                ?>
                <div class="row ">
                    <div class="form-group" >
                    <label class="control-label col-md-2">Full Name</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="text" name="name" required="required" value="<?php echo $name ?>" >
                    </div>
                    </div>
                    <div class="form-group" >
                    <label class="control-label col-md-2">Address</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="text" name="address" required="required" value="<?php  echo $address ?>">
                    </div>
                    </div>
                     <div class="form-group" >
                    <label class="control-label col-md-2">Phone</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="tel" name="phone" required="required" onkeypress="return isNumber(event)" value="<?php  echo $phone ?>">
                    </div>
                    </div>
                    
                     <div class="form-group" >
                    <label class="control-label col-md-2">Email</label>
                    <div class="col-md-4">
                    	 <input class="form-control " type="email" name="email" required="required" value="<?php  echo $email ?>" >
                    </div>
                    </div>

                     <div class="form-group" >
                    <div class="col-md-4 col-md-offset-2">
                    	 <button type="submit" class="btn btn-outline btn-primary btn-lg btn-block" name="register" value="save">Save</button>
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
?>