<?php
require_once "script/ini.php";
require_once "script/report.php";
if(!isset($_REQUEST['from']) || !isset($_REQUEST['to'])){
	header("location:index.php");
}

$ttlbills = $db->getOne("select count(distinct(date)) as ttl from billing where date between ? and ? order by date desc",
						array($_REQUEST['from'], $_REQUEST['to']));

$rowperpage = 10;							
$ttlpage = ceil($ttlbills['ttl']/$rowperpage);
$curpage = (isset($_REQUEST['page']) ? $_REQUEST['page'] : 1);
$curpage = ($curpage > $ttlpage ? $ttlpage : ($curpage < 1 ? 1 :$curpage));
$offset = ($curpage - 1) * $rowperpage;

$reportClass = new incomeReport($_REQUEST['from'],$_REQUEST['to'],$offset,$rowperpage); //generate report for this date
$report = $reportClass->getBilling();
$pbill = $report[0];
$total = $report[1];
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Income Report</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body" >
                	<form class="form-inline">
                    <div class="form-group">
                        <label class="sr-only" for="from">From</label>
                        <input type="text" class="form-control date" id="from" placeholder="From" name="from">
                    </div>
                    <div class="form-group">
                        <label class="sr-only" for="to">File input</label>
                       <input type="text" class="form-control date" id="to" placeholder="To" name="to">
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                    </form>
               </div>
               </div>
               </div>
               </div>
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered incomereport-table" >
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>TYPE</th>
                                            <th>AMOUNT</th>                                        
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                     <?php
								for($x = 0; $x < count($pbill) ; $x++){
									$bill = $pbill[$x];
									$tr = "<tr>";
									for($i = 0 ; $i < count($bill) ; $i++){
										$tr .= "<td>".$bill[$i]."</td>";
									}
									$tr .= "</tr>";
									/*$tr .= "<td>";
										if($_SESSION['right'] == 2){
										  $tr .= "<a title='Edit Request' data-pid='".$patient['id']."' data-pbill='".$pbill[$x][0]."' class='btn
										   btn-primary editreq'> <i class='fa fa-edit'></i></a>";
										}
										if($_SESSION['right'] >= 3){
										  $tr .= "<a title='Edit' href='edit_billing.php?pid=".$_REQUEST['pid']."&bid=".$pbill[$x][0]."' 
										  class='btn btn-primary'><i class='fa fa-edit'></i></a>";
										}
									$tr .= "
									<a title='Print' href='receipt.php?pid=".$_REQUEST['pid']."&bill=".$pbill[$x][0]."' 
										  class='btn btn-primary print'> <i class='fa fa-print'></i></a>";
									$tr .="</td></tr>";*/
									echo $tr;
								}
                                    
                                ?>       
                                    </tbody>
                                 <tfoot>
                                    <tr>
                                            <th>TOTAL</th>
                                            <th>-</th>
                                            <th><?php echo $total ?></th>                                        
                                        </tr>
                                    </tfoot>
                                    
                                </table>
                            </div>
                             <nav >
                              <ul class="pagination">
                              	<?php
									pagination($curpage,$ttlpage,$_REQUEST['from'],$_REQUEST['to']);
								?>
                                
                              </ul>
                            </nav>
                            <!-- /.table-responsive -->
                        </div>
                        
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<?php						
require_once "template/footer.php";
?>