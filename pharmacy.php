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

if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' && $_REQUEST['pid']){
	
	$ids = $_REQUEST['id'];
	$quantities = $_REQUEST['quantity'];
	$medicineIds = $_REQUEST['medicine'];
	for($i = 0 ; $i < count($medicineIds) ; $i++){
	    //$mequantity = $quantities[$i];
	    $id = $ids[$i];
	    $medquantity = $db->getOne("select quantity from medicine where id = ? ",array($medicineIds[$i]));
	    $quantity = $db->getOne("select sum(quantity) as quantity from treatment where medicine_id = ? and sign = 1",array($medicineIds[$i]));
	    $quantity = $quantity['quantity'] + $quantities[$i];
	    if($medquantity['quantity'] < $quantity){
	        continue;
        }
	    $db->execute("update treatment set sign = 1, quantity = ?, pharmacist = ? where id = ?",array($quantities[$i],$_SESSION['user_id'],$id));
    }
	header("location:pharmacy_list.php?success=Signed Successfully");
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
                    <label class="col-md-1 ">Quantity</label>
                    <label class="col-md-3 ">Route </label>
                </div>
                <?php
				$result = $db->getAll("select m.name, dosage,routine,t.quantity, t.id, medicine_id, t.route from treatment t inner join medicine m on t.medicine_id = m.id inner join patient p on t.patient_id = p.id where patient_id = ? and t.date = ? and sign = 0 and (payment = 1 or p.ward is not null) ",
										array($_REQUEST['pid'],$_REQUEST['date']));
				foreach($result as $diag){
					echo '<div class="form-group " id="vitals-group">
                  <div class="col-md-4" >
				  <input type="hidden" name="id[]" value="'.$diag['id'].'" />
				
				  <input type="hidden" name="medicine[]" value="'.$diag['medicine_id'].'" />
				  <input class="form-control" disabled="disabled"   value="'.$diag['name'].'"  />
				  </div>
				  <div class="col-md-1" >
				  <input class="form-control" disabled="disabled"   value="'.$diag['dosage'].'"  />
				  </div>
				  <div class="col-md-1" >
				  <input class="form-control" disabled="disabled"   value="'.$diag['routine'].'"  />
				  </div>
				  <div class="col-md-1" >';

				  if($diag['quantity'] != null) {

                      echo '<input type="hidden" name="quantity[]" value="'.$diag['quantity'].'" />
                      <input class="form-control" disabled = "disabled" value = "'.$diag['quantity'].'"  />';
				  }else{
                      echo '<input class="form-control" name="quantity[]"  />';
                  }
                    echo '
				  </div>
				  <div class="col-md-3" >
				  <input class="form-control" disabled="disabled"   value="'.$diag['route'].'"  />
				  </div>
				  </div>';
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
                
                
                <div class="form-group" style="margin-top:30px">
                 
                  
                  <div class="col-md-2 ">
                  <button type="submit" class="btn btn-block btn-primary " name="submit" value="save" ><b>Sign</b></button>
                  </div>
                  
                </div>
                <!-- /.row -->
                </form>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

<?php
require_once "template/footer.php";
?>
