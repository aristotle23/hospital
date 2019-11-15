<?php
require_once "script/ini.php";
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
    $amount = $_REQUEST['amount'];
    $test = $_REQUEST['test'];

    if(isset($_REQUEST['edit'])){
        $db->execute("update lab_test set test = ?, amount = ? where id = ?",array($test, $amount, $_REQUEST['edit']));
        header("location: lab-list.php");
    }else{
        try {
            $ok = $db->executeGetId("INSERT INTO `lab_test` (test, amount) VALUES (?, ?)", array($test, $amount));
            header("location:" . $_SERVER['PHP_SELF'] . "?success=New laboratory test added successfully");
        }catch (Exception $e){
            header("location:" . $_SERVER['PHP_SELF'] . "?failed=Laboratory test already exist");
        }
    }
}

$test = null;
$amount = null;
$labTestId = null;
if(isset($_REQUEST["edit"]) ){
    $input = "Edit";
    $labTest = $db->getOne("select * from lab_test where id = ?", array($_REQUEST['edit']));

    $test = $labTest['test'];
    $amount = $labTest['amount'];
    $labTestId = "<input type='hidden' name='edit' value='".$_REQUEST['edit']."' />";
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">New Laboratory Test</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <?php echo $labTestId ?>

                <div class="form-group">
                <label class="col-md-1 control-label">Test</label>
                <div class="col-md-5">
                <input type="text" class=" form-control" name="test" value="<?php echo $test ?>" required="required" autocomplete="off" />
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-1 control-label">Amount</label>
                <div class="col-md-3">
                <input type="text" class=" form-control" name="amount" required="required" autocomplete="off" value="<?php echo $amount ?>" onkeypress="return isNumber(event)" />
                </div>
                </div>
                <div class="form-group">
                <div class="col-md-3 col-md-offset-1">
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