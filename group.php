<?php

require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Group</h1>
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
                        	<div class="row form-inline" style="margin-bottom:20px; margin-left:0px">	
                            	<div class="form-group">
                            		<label for="group-type" style="font-weight:normal">Type: </label>
                                	<select class="form-control" id="group-type">
                                    	<option value="0" <?php echo ($_REQUEST['type'] == 0 ? "selected='selected'": "") ?> >Family</option>
                                        <option value="1" <?php echo ($_REQUEST['type'] == 1 ? "selected='selected'": "") ?> >Organization</option>
                                    </select>
                                </div>
                               
                        	</div>
                            <table width="100%" class="table table-striped table-bordered table-hover group-list" >
          
                                <thead>
                                    <tr>
                                    	<th width="8%">ID</th>
                                        <th width="10%">Reg. Date</th>
                                        <th >Name</th>                                        
                                        <th >Address</th>
                                        <th >Phone</th>
                                        <th >Email</th>
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
$("#group-type").on("change",function(e){
	$val  = $(this).val();
	origin = location.origin;
	path = location.pathname;
	location = origin+path+'?type='+$val
	
})
</script>

<?php
require_once "template/footer.php";
?>