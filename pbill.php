<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Patients</h1>
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
                        
                            <table width="100%" class="table table-striped table-bordered table-hover patientTbl" >
          
                                <thead>
                                    <tr>
                                    	<th width="8%">ID</th>
                                        <th>Full Name</th>
                                        <th width="12%">Phone</th>                                        
                                        <th width="10%">D.O.D</th>
                                        <th width="8%">Sex</th>
                                        <th width="12%">M. Status</th>
                                        <th width="12%">Reg. Date</th>
                                        <th width="12%">Tasks</th>
                                    </tr>
                                </thead>
                                
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