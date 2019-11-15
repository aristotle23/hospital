<?php
require_once "script/ini.php";
if($_SESSION['right'] == 6){
    header("location: patient_list.php");
}elseif ($_SESSION['right'] == 7){
    header("location: lab-list.php");
}elseif ($_SESSION['right'] == 8){
    header("location: pharmacy_list.php");
}
require_once "template/header.php";
require_once "template/sidebar.php";
?>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Dashboard</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <?php
                if($_SESSION['right'] < 5){
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-bed fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="register">0</div>
                                    <div>Rigistered Patients</div>
                                </div>
                            </div>
                        </div>
                        <a href="patients.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-area-chart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="intake">0</div>
                                    <div>Intake Patients</div>
                                </div>
                            </div>
                        </div>
                        <a href="pbill.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                if ($_SESSION['right'] > 1){
                ?>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="billed">0</div>
                                    <div>Billed Patients</div>
                                </div>
                            </div>
                        </div>
                        <a href="billing_view.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                    if($_SESSION['right'] > 2){
                ?>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="amtpaid">0</div>
                                    <div>Amount Received</div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $datetime = new DateTime();
                        $curyear = $datetime->format('Y');
                        $from = $curyear . '-00-00';
                        $to = $curyear . '-12-31';
                        ?>
                        <a href="income.php?from=<?php echo $from ?>&to=<?php echo $to ?>">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="expense">0</div>
                                    <div>Total Expenditure</div>
                                </div>
                            </div>
                        </div>
                        <a href="expense_analyst.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="incomeMan">0</div>
                                    <div>Income Received</div>
                                </div>
                            </div>
                        </div>
                        <a href="expense_analyst.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge" id="ttlincome">0</div>
                                    <div>Total Income</div>
                                </div>
                            </div>
                        </div>
                        <a href="expense_analyst.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>


            </div>
            <?php
            }
            }
            }
            ?>
            <!-- /.row -->
            
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<script>
setInterval(function(){
$.ajax({
	url:"script/ajax.php?state=updatedashboard",
	dataType:"json",
	success: function(data){
		$("#intake").text(data[0]);
		$("#register").text(data[2]);
		if(data[3] == null){
			amtpaid = 0
		}else{
			amtpaid = data[3]
		}
		$("#amtpaid").text(amtpaid);
		$("#billed").text(data[1]);
        $("#expense").text(data[4]);
        $("#incomeMan").text(data[5]);
        $("#ttlincome").text(data[6]);
	}
})
},500);
</script>
    <?php
	require_once "template/footer.php";
	?>
