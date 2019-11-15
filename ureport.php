<?php
require_once "script/ini.php";
require_once "script/report.php";
if(!isset($_REQUEST['from']) || !isset($_REQUEST['to'])){
	header("location:index.php");
}
$ttlbills = $db->getOne("select count(distinct(date)) as ttl from billing_hist where date between ? and ? ",array($_REQUEST['from'], $_REQUEST['to']));

$rowperpage = 10;							
$ttlpage = ceil($ttlbills['ttl']/$rowperpage);
$curpage = (isset($_REQUEST['page']) ? $_REQUEST['page'] : 1);
$curpage = ($curpage > $ttlpage ? $ttlpage : ($curpage < 1 ? 1 :$curpage));
$offset = ($curpage - 1) * $rowperpage;

$reportClass = new ureport($_REQUEST['from'],$_REQUEST['to'],$offset,$rowperpage); //generate report for this date
$reports = $reportClass->userReport();

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">User Report</h1>
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
                                <table class="table table-striped table-bordered ureport-table">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>NAMES</th>                                        
                                        	<th>ACCESS</th>
                                            <th>SERVICES</th>
                                            <th>AMOUNT RECEIVE</th>
                                            <th>AMOUNT CHARGE</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
										for($i = 0 ; $i < count($reports); $i++){
											$report = $reports[$i][2];
											$tr = '<tr>';
											for ($x = 0 ; $x < count($report); $x++){
												$tr .= "<td>".$report[$x]."</td>";
											}
											$stat = $reports[$i][1];
											$dis = ($stat == 1) ? "display: none":"";
											$btn = "<a title='Balance' class='btn btn-primary ubal' style='".$dis."' data-rid='".$reports[$i][0]."'>
											<i class='fa fa-times'></i></a>";
											$dis = ($stat == 0) ? "display: none":"";
											$btn .= "<a title='Balanced already' class='btn btn-success' style='".$dis."' ><i class='fa fa-check'></i></a>";
											$tr .="<td>".$btn ."</td>";
											$tr .="</tr>";
											print $tr;
										}
									?>
                                    </tbody>
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