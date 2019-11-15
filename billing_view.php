<?php

require_once "script/ini.php";
require_once "script/all_bill.php";
if((isset($_REQUEST['bid']) && isset($_REQUEST['del'])) && $_REQUEST['del'] == "1" ){
    $db->execute("delete from billing where id = ?;",array($_REQUEST['bid']));
    $db->execute("delete from billing_hist where bill_id = ?;",array($_REQUEST['bid']));
    header("location: ?success=bill deleted successfully");
}
/*$billings = new billing();
$pbill = $billings->getBilling();
*/
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
                        
                            <table width="100%" class="table table-striped table-bordered table-hover" id="bill_view">
          
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