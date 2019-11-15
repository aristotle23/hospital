<?php
require_once "script/ini.php";
require_once "script/report.php";
if(!isset($_REQUEST['from']) || !isset($_REQUEST['to'])){
	header("location:index.php");
}
/*$reportClass = new preport($_REQUEST['from'],$_REQUEST['to']); //generate report for this date
$reports = $reportClass->patientReport();*/

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Patient Report</h1>
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
                                <table class="table table-striped table-bordered" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>DATE</th>
                                            <th>CARD NO</th>
                                            <th>NAMES</th>                                        
                                        <?php
											/*$titles = $reportClass->servicetitle;
											$tsum = array();
											$key = 0;
											foreach($titles as $title){
												$tsum[$key] = 0;
												print '<th>'. strtoupper($title['title']).'</th>';
												$key += 1;
											}*/
											$titles = $db->getAll("SELECT * FROM service_title order by id asc");
											foreach($titles as $title){
												echo "<th>".$title['title']."</th>";
											}
										?>
                                        	<th>DEBT</th>
                                        	<th>CHARGE</th>
                                            <th>PAID</th>
                                            <th>BALANCE</th>
                                            <th>STATUS</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody>
                                       
                                    <?php
										$ttlcharge = 0;
										$ttlpaid = 0;
										$ttlowned = 0;
										for($i = 0 ; $i < count($reports); $i++){
											$tr = '<tr><td>'.$reports[$i][0].'</td><td>'.$reports[$i][1].'</td><td>'.$reports[$i][2].'</td>';
											$titles = $reports[$i][3];
											for ($x = 0 ; $x < count($titles); $x++){
												$tsum[$x] = $tsum[$x] + $titles[$x];
												$title = ($titles[$x] == "") ? 0.0 : $titles[$x];
												$tr .= "<td>".$title ."</td>";
											}
											//$owned = $reports[$i][4] - $reports[$i][5];
											$owned = $reports[$i][8];
											$ttlcharge += $reports[$i][4];
											$ttlpaid += $reports[$i][5];
											$ttlowned += $owned;
											$tr .= "<td>".$reports[$i][7]."</td>";
											$tr .= "<td>".$reports[$i][4]."</td>";
											$tr .= "<td>".$reports[$i][5]."</td>";
											$tr .= "<td>".$owned."</td>";
											$tr .= "<td>".$reports[$i][6]."</td></tr>";
											print $tr;
										}
									?>
                                    </tbody>
                                  <!--  <tfoot>
                                    <tr>
                                            <th>TOTAL</th>
                                            <th>-</th>
                                            <th>-</th>                                        
                                        <?php
											
										/*foreach($tsum as $key => $sum){
												print '<th>'.$sum.'</th>';
											}
										print '<th>-</th>';
										print '<th>'.$ttlcharge.'</th>';
										print '<th>'.$ttlpaid.'</th>';
										print '<th>'.$ttlowned.'</th>';*/
									?>
										
                                        	
                                            <th>-</th>
                                        </tr>
                                    </tfoot>-->
                                </table>
                            </div>
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