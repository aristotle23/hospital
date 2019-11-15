<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid']) && isset($_REQUEST['date'])){
	$patient =  new patient($_REQUEST['pid']);
	$patient = $patient->info();
	$patient = $patient['name'];
	
}else{
	header("location:index.php");
}

if(isset($_REQUEST['re'])){
	
	$db->execute("delete from treatment where  id = ?",array($_REQUEST['re']));
	header("location:prescription_report.php?success=Prescription Removed Successfully");
}
if(isset($_REQUEST['refill'])){
    $quantity = $_REQUEST['quantity'];
    $tid = $_REQUEST['refill'];
    $medid = $_REQUEST['medid'];

    $medquantity = $db->getOne("select quantity from medicine where id = ? ",array($medid));
    $tQuantity = $db->getOne("select sum(quantity) as quantity from treatment where medicine_id = ? and sign = 1",array($medid));
    $tQuantity = $tQuantity['quantity'] + $quantity;
    if($medquantity['quantity'] < $tQuantity){
        header("location:?fail=Stock quantity is less than refill&pid=".$_REQUEST['pid']."&date=".$_REQUEST['date']);
    }

    $db->execute("update treatment set quantity = quantity + ? where id = ?",array($quantity,$tid));
    $db->execute("insert into treatment_refill (treatment_id, quantity,user_id) VALUES (?,?,?)",array($tid,$quantity,$_SESSION['user_id']));
    header("location:?success=Refill Successful&pid=".$_REQUEST['pid']."&date=".$_REQUEST['date']);
}
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header" style="margin-bottom:50px">Pharmacy <small><?php echo $patient ?></small></h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                </div>
                
                <form class="form-horizontal" role="form"  method="post">
                <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                <div class="form-group">
                <label class="col-md-4 ">Medicine</label>
                <label class="col-md-1 ">Dosage</label>
                <label class="col-md-1 ">Rutine</label>
                    <label class="col-md-2 ">Quantity</label>
                </div>
                <?php
				$result = $db->getAll("select name, dosage,routine,t.quantity, t.id, medicine_id,payment from treatment t inner join medicine m on t.medicine_id = m.id where patient_id = ? and date = ? and sign = 1 ",
										array($_REQUEST['pid'],$_REQUEST['date']));
			
				foreach($result as $diag){

					echo '<div class="form-group " id="vitals-group">
                  <div class="col-md-4" >
				  <input type="hidden" name="id[]" value="'.$diag['id'].'" />
				  <input type="hidden" name="quantity[]" value="'.$diag['quantity'].'" />
				  <input type="hidden" name="medicine[]" value="'.$diag['medicine_id'].'" />
				  <input class="form-control" disabled="disabled"   value="'.$diag['name'].'"  />
				  </div>
				  <div class="col-md-1" >
				  <input class="form-control" disabled="disabled"   value="'.$diag['dosage'].'"  />
				  </div>
				  <div class="col-md-1" >
				  <input class="form-control" disabled="disabled"   value="'.$diag['routine'].'"  />
				  </div>
				  <div class="col-md-2" >
				  <input class="form-control" disabled="disabled"   value="'.$diag['quantity'].'"  />
				  </div>
				  <div class="col-md-1" >
				  <a href="?re='.$diag['id'].'&pid='.$_REQUEST['pid'].'&date='.$_REQUEST['date'].'" title="Remove Prescription" class="btn btn-primary btn-block btn-danger"><i class="fa fa-times"></i> </a>
				  </div>';
					if($diag['payment'] == 0) {
                        echo '<div class="col-md-1" >
				            <button data-toggle="modal" type="button" data-mid="' . $diag['medicine_id'] . '" data-target="#refillmodal" data-tid="' . $diag['id'] . '" title="Refill Prescription" class="btn btn-primary btn-block btnrefill"><i class="fa fa-plus"></i> </button>
				            </div>';
                    }
					echo '</div>';
				}
				?>
                
                  	
                 
                
                <!--<div class="form-group">
                  <div class="col-md-1">
                      <button class="btn btn-block btn-primary billminus" ><i class="fa fa-minus"></i></button>
                  </div>
                  <div class="col-md-4">
                    <select class="form-control" name="service[]">
                    <option>Please Select...</option>
                    </select>
                  </div>
                  <div class="col-md-2">
                  	<input class="form-control" placeholder="0.00" dir="rtl" required="required" name="amount[]"/>
                  </div>
                </div>-->
                
                

                <!-- /.row -->
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
<div class="modal fade " id="refillmodal" role="dialog">
    <div class="modal-dialog modal-sm ">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Refill Prescription</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="post">
                    <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
                    <input type="hidden" name="date" value="<?php echo $_REQUEST['date'] ?>"  />
                    <input type="hidden" name="medid"  />
                    <div class="form-group">
                        <label class="control-label col-md-4">Quantity</label>
                        <div class="col-md-8">
                            <input class="form-control " name="quantity" required />
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnrefillform" name="refill" >Refill</button>
                </form>
            </div>
        </div>

    </div>
</div>
<script >
    $(document).on("click",".btnrefill",function (e) {
        e.preventDefault();
        let $this = $(this);
        let tid = $this.data("tid");
        let mid = $this.data("mid");
        let refillButton = $("#btnrefillform");
        let inputMedid = $("input[name=medid]");
        refillButton.attr("value",tid);
        inputMedid.val(mid)

    })
</script>
<?php
require_once "template/footer.php";
?>
