<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Expenditure Report</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                        
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                            
                                <thead>
                                    <tr>
                                    	<th width="8%">#</th>
                                        <th width="10%">Date</th>
                                        <th>Type</th>                                        
                                        <th>Personnel</th>
                                        <th>Amount</th>
                                        <th width="15%">Tasks</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
								<?php
                                    $results = $db->getAll("select * from expenditure");
                                    foreach($results as $key=>$result){
										$index = $key + 1;
                                        print "<tr><td>".$index."</td><td>".$result['date']."</td><td>".$result['type']."</td>";
										print "<td>".$result['personnel']."</td><td>".$result['amount']."</td><td>
										<div class='row'>
										<div class='col-md-12'>";
										$dis = ($result['hide'] == 1) ? "display: none":"";
										$btn =	"<a class='btn btn-default delete' style='".$dis."' data-table='expenditure' 
                                                data-id='".$result['id']."'>Del</a>";
										if($_SESSION["right"] > 2) {
                                            print "<a class='btn btn-primary ' href='expenditure.php?edit=" . $result['id'] . "'>Edit</a> ";
                                        }
										$dis = ($result['hide'] == 0) ? "display: none":"";
										$btn .= "<a class='btn btn-link delundo' style='".$dis."' data-table='expenditure' 
                                                data-id='".$result['id']."'>Undo</a></div>";
                                        
										print $btn."</div></td></tr>";
                                        
                                    };
                                ?>
                                    
                                    
                                    
                                </tbody>
                            </table>
                            <!-- /.table-responsive -->
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <!-- /.row -->
            
            <!-- /.row -->
            
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
<script type="text/javascript" async="async">

</script>

<?php
require_once "template/footer.php";
?>