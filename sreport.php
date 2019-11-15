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

$reportClass = new sreport($_REQUEST['from'],$_REQUEST['to'],$offset,$rowperpage); //generate report for this date
$reports = $reportClass->report();

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Services</h1>
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
                    <button type="submit" class="btn btn-default">Submit</button>
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
                                <table class="table table-striped table-bordered sreport-table" >
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                                                                    
                                        <?php
											$titles = $reportClass->servicetitle;
											foreach($titles as $title){
												print '<th>'. strtoupper($title['title']).'</th>';
											}
										?>
                                        	<th>TOTAL CHARGE</th>
                                            <th>DEBT PAID</th>
                                            <th>TOTAL RECEIVE</th>
                                            <th>DEBT OWNED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    <?php
										for($i = 0 ; $i < count($reports); $i++){
											$titles = $reports[$i];
											$tr = '<tr>';
											for ($x = 0 ; $x < count($titles); $x++){
												if(empty($titles[$x])){
													$tval = 0;
												}else{
													$tval = $titles[$x];
												};
												$tr .= "<td>".$tval."</td>";
											}
											$tr .= "</tr>";
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