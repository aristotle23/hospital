<?php

require_once "script/ini.php";
require_once "script/all_bill.php";

$billings = new billing();
$pbill = $billings->getBilling();

require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Billing</h1>
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
                                        <th >Date</th> 
                                        <th >Patient Name</th>  
                                        <th >Hospital No.</th>                                       
                                        <th >Services</th>
                                        <th >Charge</th>
                                        <th >Paid</th>
                                        <th >Balance</th>
                                        <th >Status</th>
                                        <th >Operations</th>
                                    </tr>
                                </thead>
                                <tbody >
                                <?php
								for($x = 0; $x < count($pbill) ; $x++){
									$bill = $pbill[$x][2];
									$tr = "<tr>";
									for($i = 0 ; $i < count($bill) ; $i++){
										$tr .= "<td>".$bill[$i]."</td>";
									}
									$tr .= "<td>";
										if($_SESSION['right'] == 2 && $pbill[$x][3] == false){
										$tr .= "<a title='Edit Reqquest' data-pid='".$pbill[$x][1]."' data-pbill='".$pbill[$x][0]."' class='btn 
										btn-primary editreq'><i class='fa  fa-edit'></i></a>";
										}
										if($_SESSION['right'] >= 3 && $pbill[$x][3] == false){
										$tr .= "<a title='Edit' href='edit_billing.php?pid=".$pbill[$x][1]."&bid=".$pbill[$x][0]."' class='btn 
										btn-primary' ><i class='fa  fa-edit'></i></a>";
										}
										if(strtolower($bill[7]) == 'debtor' && $pbill[$x][4] == 0 ){
										$tr .= " <a title='Pay Debt' href='debt_billing.php?pid=".$pbill[$x][1]."&bid=".$pbill[$x][0]."' class='btn 
										btn-primary debt' >	<i class='fa fa-reply'></i></a>";
										}
									$tr .= "
										  <a title='Print' href='receipt.php?pid=".$pbill[$x][1]."&bill=".$pbill[$x][0]."' class='btn btn-primary print'>
										  <i class='fa fa-print'></i></a>";
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
$(".editreq").on("click",function(e){
	$this = $(this);
	pid = $this.data("pid");
	pbill = $this.data("pbill")
	
	$.ajax({
		url:"script/ajax.php?state=editalert&pid="+pid+"&pbill="+pbill,
		dataType:"json",
		success: function(e){
			if(e == true){
				location = location.origin+location.pathname+"?pid="+pid+"&success=Edit Request Sent Successfully"
			}
		}
	})
})
</script>

<?php
require_once "template/footer.php";
?>