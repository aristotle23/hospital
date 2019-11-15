<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Services</h1>
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
                                        <th>Service Title</th>
                                        <th>Service Type</th>
                                        <th>Service</th> 
                                        <th>Access Right</th>
                                        <th >Tasks</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
								<?php
                                    $results = $db->getAll("SELECT s.id, s.services, title, access, s.service_type_id,hide FROM services s 
															inner join service_title st on s.service_title_id = st.id inner join 
															access_right ar on ar.level = s.access_right ");
                                    foreach($results as $key=>$result){
										$index = $key + 1;
										$stat = $result['hide'];
										print "<tr><td>".$index."</td><td>".$result['title']."</td>";
										$type = $db->getOne("SELECT type FROM service_type WHERE id = ?",array($result['service_type_id']));
										print "<td>".$type['type']."</td><td>".$result['services']."</td><td>".$result['access']."</td><td>
										<div class='row'>
										<div class='col-md-12'>";
										$dis = ($stat == 1) ? "display: none":"";
										$btn = "<a class='btn btn-default  delete' style='".$dis."' data-table='services' 
												data-id='".$result['id']."'>Delete</a>";
										$dis = ($stat == 0) ? "display: none":"";
										$btn .= "<a class='btn btn-link delundo' style='".$dis."' data-table='services' 
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