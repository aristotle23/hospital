<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Patient List</h1>
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
                        
                            <table width="100%" class="table table-striped table-bordered table-hover doc-list" >
          
                                <thead>
                                    <tr>
                                    	<th >Reg. Date</th>
                                        <th>Hospital ID</th>
                                        <th >Name</th>                                        
                                        <th >D.O.B</th>
                                        <th >Sex</th>

                                        <th >Tasks</th>
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