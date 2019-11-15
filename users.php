<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Users</h1>
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
                                        <th>Name</th>                                        
                                        <th>Username</th>
                                        <th>Password</th>
                                        <th>Access Right</th>
                                        <th width="10%">Tasks</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
								<?php
                                    $results = $db->getAll("SELECT name,access,username,password,u.id FROM user u inner join access_right a 
															on u.access_right = a.level;");
                                    foreach($results as $key=>$result){
										$index = $key + 1;
                                        print "<tr><td>".$index."</td><td>".$result['name']."</td>";
										print "<td>".$result['username']."</td><td>".$result['password']."</td><td>".$result['access']."</td><td>
										<div class='row'>
										<div class='col-md-12'>
										<a class='btn btn-default btn-block delete' data-table='user' data-id='".$result['id']."'>Del</a></div>
										</div>
										</td></tr>";
                                        
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