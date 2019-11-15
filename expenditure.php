<?php
require_once "script/ini.php";
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
    $date = $_REQUEST['date'];
    $personnel = $_REQUEST['personnel'];
    $amount = $_REQUEST['amount'];
    $exptype = $_REQUEST['exptype'];
    
    if(isset($_REQUEST['edit'])){
        $db->execute("update expenditure set type = ?, personnel = ?, date = ?, amount = ? where id = ?",array($exptype, $personnel, $date, $amount, $_REQUEST['edit']));
        header("location: expense_analyst.php");
    }else{
        $ok = $db->executeGetId("INSERT INTO `expenditure` (`type`, `personnel`, `date`, `amount`) VALUES (?, ?, ?, ?)",array($exptype,$personnel,$date,$amount));
        if($ok){
            header("location:".$_SERVER['PHP_SELF']."?success=New expenditure added successfully");
        }else{
            header("location:".$_SERVER['PHP_SELF']."?failed=Unable to add new expenditure");
        }
    }

}
$date = NULL;
$exptype = NULL;
$personnel = NULL;
$amount = NULL;
$expenseId = NULL;
if(isset($_REQUEST["edit"]) ){
    $input = "Edit";
    $expense = $db->getOne("select * from expenditure where id = ?", array($_REQUEST['edit']));
    $date = $expense['date'];
    $exptype = $expense['type'];
    $personnel = $expense['personnel'];
    $amount = $expense['amount'];
    $expenseId = "<input type='hidden' name='edit' value='".$_REQUEST['edit']."' />";
}
require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">New Expenditure</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <?php echo $expenseId ?>
                <div class="form-group">
                <label class="col-md-2 control-label">Date</label>
                <div class="col-md-3">
                <input type="text" class="date form-control" name="date" value="<?php echo $date ?>" required="required" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Expenditure Type</label>
                <div class="col-md-5">
                <input type="text" class=" form-control" name="exptype" value="<?php echo $exptype ?>" required="required" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Personnel</label>
                <div class="col-md-5">
                <input type="text" class=" form-control" name="personnel" value="<?php echo $personnel ?>" required="required" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Amount</label>
                <div class="col-md-3">
                <input type="text" class=" form-control" name="amount" value="<?php echo $amount ?>" required="required" onkeypress="return isNumber(event)" />
                </div>
                </div>
                <div class="form-group">
                <div class="col-md-3 col-md-offset-2">
                <input type="submit" class="btn btn-block btn-primary btn-lg" name="submit" value="Save" required="required" />
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