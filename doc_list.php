<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Ward <small>Patients</small> </h1>
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
                        
                            <table width="100%" class="table table-striped table-bordered table-hover dataTables-example" >
          
                                <thead>
                                    <tr>
                                    	<th>Reg. Date</th>
                                    	<th >Hospital ID</th>
                                        <th>Name</th>
                                        <th>Admitted</th>
                                        <th >Task</th>  
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$result = $db->getAll("SELECT  date, hospital_no, name	where user_id = ?", array($_SESSION['user_id']));
									foreach($result as $bed){
										$tr = "<tr>";
										$tr .= "<td>".$bed['date']."</td><td>".$bed['number']."</td>";
										$pdata = $db->getOne("select name, hospital_no, sex from patient where id = ?",array($bed['patient_id']));										
										$tr .="<td>".$pdata['hospital_no']."</td><td>".$pdata['name']."</td><td>".$pdata['sex']."</td></tr>";
									}
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
/*tbody = $("#ptblbody")
$.ajax({
	url:"script/ajax.php?page=patient",
	dataType:"json",
	success: function(data){
		tr = $(data);
		tbody.append(tr)
	}
})*/
</script>

<?php
require_once "template/footer.php";
?>