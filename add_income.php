<?php
require_once "script/ini.php";
if(isset($_REQUEST['submit']) && strtolower($_REQUEST['submit']) == 'save'){
    $date = $_REQUEST['date'];
    $personnel = $_REQUEST['personnel'];
    $amount = $_REQUEST['amount'];
    $inctype = $_REQUEST['exptype'];

    if(isset($_REQUEST['edit'])){
        $db->execute("update income set type = ?, personnel = ?, date = ?, amount = ? where id = ?",array($inctype, $personnel, $date, $amount, $_REQUEST['edit']));
        header("location: income_analyst.php");
    }else{
        $ok = $db->executeGetId("INSERT INTO `income` (`type`, `personnel`, `date`, `amount`) VALUES (?, ?, ?, ?)",array($inctype,$personnel,$date,$amount));
        if($ok){
            header("location:".$_SERVER['PHP_SELF']."?success=New income added successfully");
        }else{
            header("location:".$_SERVER['PHP_SELF']."?failed=Unable to add new income");
        }
    }
}

$date = NULL;
$inctype = NULL;
$personnel = NULL;
$amount = NULL;
$incomeId = NULL;
if(isset($_REQUEST["edit"]) ){
    $input = "Edit";
    $income = $db->getOne("select * from income where id = ?", array($_REQUEST['edit']));
    $date = $income['date'];
    $inctype = $income['type'];
    $personnel = $income['personnel'];
    $amount = $income['amount'];
    $incomeId = "<input type='hidden' name='edit' value='".$_REQUEST['edit']."' />";
}

require_once "template/header.php";
require_once "template/sidebar.php";


?>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">New Income</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="row">
            <div class="col-md-12">
            <!-- /.row -->
            <form role="form" class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <?php echo $incomeId ?>
                <div class="form-group">
                <label class="col-md-2 control-label">Date</label>
                <div class="col-md-3">
                <input type="text" class="date form-control" name="date" value="<?php echo $date ?>" required="required" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Income Type</label>
                <div class="col-md-5">
                <input type="text" class=" form-control" name="exptype" value="<?php echo $inctype ?>" required="required" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Personnel</label>
                <div class="col-md-5">
                <input type="text" class=" form-control" name="personnel" required="required" value="<?php echo $personnel ?>" />
                </div>
                </div>
                <div class="form-group">
                <label class="col-md-2 control-label">Amount</label>
                <div class="col-md-3">
                <input type="text" class=" form-control" name="amount" required="required" value="<?php echo $amount ?>" onkeypress="return isNumber(event)" />
                </div>
                </div>
                <div class="form-group">
                <div class="col-md-3 col-md-offset-2">
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