<?php
require_once "script/ini.php";
if(isset($_REQUEST['discharge']) && $_REQUEST['discharge'] == true){
	$hist = $db->getOne("select ward_id, taken, date,patient_id,number,id from bed where patient_id = ?",array($_REQUEST['pid']));
	$db->execute("insert into bed_hist (ward_id,taken,date,patient_id,number,bed_id) values (:ward_id,:taken,:date,:patient_id,:number,:id)",$hist);
	$db->execute("update bed set patient_id = ? , date = ?, taken = 0 where patient_id = ?",array(NULL,NULL, $_REQUEST['pid']));
	header("location:?success=You have successfully discharge patient&pid=".$_REQUEST['pid']);
}
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
                                    	<th> Date</th>
                                    	<th >Bed No.</th>
                                        <th>Hospital ID</th>
                                        <th >Patient Name</th>  
                                        <th >Sex</th>  
                                        <th>Task</th>
                                    </tr>
                                </thead>
                                <tbody>
                                	<?php
									$result = $db->getAll("select b.number, b.date, patient_id from bed b inner join ward w on w.id = b.ward_id 
														where user_id = ? and taken = 1", array($_SESSION['user_id']));
									
									foreach($result as $bed){
										$tr = "<tr>";
										$tr .= "<td>".$bed['date']."</td><td>".$bed['number']."</td>";
										$pdata = $db->getOne("select id, name, hospital_no, sex,ward from patient where id = ?",
																array($bed['patient_id']));
										$tr .="<td>".$pdata['hospital_no']."</td><td>".$pdata['name']."</td><td>".$pdata['sex']."</td><td>
										<a href='?pid=".$pdata['id']."&discharge=true' ";
										if($pdata['ward'] != NULL){
											$tr .= "disabled='disabled' ";
										}
										$tr .="class='btn btn-primary'>Discharge</a>";
										$tr .="  <a class='btn btn-primary' href='nurse_treatment.php?pid=".$bed['patient_id']."'>treatment</a>";
										$tr .="  <a  class='btn btn-primary' href='vitals.php?pid=".$bed['patient_id']."'>Vitals</a>";
										$tr .= "</td></tr>";
										echo $tr;
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