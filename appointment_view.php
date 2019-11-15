<?php

require_once "script/ini.php";
if(!isset($_REQUEST['from']) || !isset($_REQUEST['to'])){
	header("location:index.php");
}
/*$date = date('Y-m-d',strtotime('today'));
print_r($date);*/

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Appointments</h1>
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
                            <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
          
                                <thead>
                                    <tr>
                                    	<th width="10%">Date</th>
                                        <th>ID</th>
                                        <th>Full Name</th>
                                        <th width="10%" >Phone</th> 
                                    	<th width="7%">Sex</th>
                                        <th >Doctor</th>
                                        <th >Purpose</th>
                                        <th width="8%">Task</th>
                                    </tr>
                                </thead>
                                <tbody id="ptblbody">
                                <?php
									$appointments = $db->getAll("select * from appointment where seen = '0' and doctor = ? and date between ? and ?",
														array($_SESSION['user_id'], $_REQUEST['from'],$_REQUEST['to']));
									foreach ($appointments as $appointment){
										$date = $appointment['date'];
										$doctor = $appointment['doctor'];
										$purpose = $appointment['purpose'];
										$patient_id = $appointment['patient_id'];
										$patient = $db->getOne("select * from patient where id = ?",array($patient_id));
										$trs = "";
										
										$name = $patient['name'];
										$phone = $patient['telephone'];
										$id = $patient['hospital_no'];
										$sex = $patient['sex'];
										
										$trs .= "<tr><td >".$date."</td><td>".$id."</td><td >".$name."</td><td >".$phone."</td><td >".$sex."</td><td >"
										.$doctor."</td><td>".$purpose."</td><td>
										<a data-id='".$appointment['id']."' class='btn btn-primary btn-block seen'>Seen</a></td></tr>";
										echo $trs;
									}
										
                                    
                                    ?>
                                    
                                </tbody>
                            </table>
                            </div>
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