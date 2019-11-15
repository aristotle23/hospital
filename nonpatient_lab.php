<?php
require_once "script/patient.php";
require_once "script/ini.php";


if(isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save' ){

    $test = $_REQUEST['test'];
    $result = $_REQUEST['result'];
    $name = $_REQUEST['name'];
    $sex = $_REQUEST['sex'];
    $phone = $_REQUEST['phone'];
    $nonpatient = $db->getOne("select id from nonpatient where phone = ?",array($phone));
    if($nonpatient){
        $nonpatientId = $nonpatient['id'];
    }else{
        $nonpatientId = $db->executeGetId("insert into nonpatient (name, sex, phone) VALUES (?,?,?)",array($name,$sex,$phone));
    }
    if($nonpatientId){
        for($i = 0 ; $i < count($test) ; $i++){
            $vlab_test = $test[$i];
            $vlab_result = $result[$i];

            $diag_id = $db->executeGetId("INSERT INTO nonpatient_lab (nonpatient_id, test, result, user_id) VALUES (?,?,?,?)",
                array($nonpatientId,$vlab_test,$vlab_result,$_SESSION['user_id']));
        }
    }
    header("location: lab-list.php?success=saved successfully");
    //print_r($_REQUEST);
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Non Patient Laboratory <small></small></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>

    </div>

    <form class="form-horizontal" role="form" id="vlab_form" method="post">
        <div class="form-group">
            <label class="col-md-1 control-label">Name</label>
            <div class="col-md-4">
                <input class="form-control "  required="required" name="name"/>
            </div>
            <label class="col-md-1 control-label">Sex</label>
            <div class="col-md-2">
                <select class="form-control" name="sex">
                    <option value="unknown">[---- Select ----]</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <label class="col-md-1 control-label">Phone</label>
            <div class="col-md-3">
                <input class="form-control "  required="required" name="phone" onkeypress="return isNumber(event)" />
            </div>
        </div>
        <div class="form-group" id="vlab_group">
            <div class="col-md-1">
                <button class="btn btn-block btn-primary " id="vlab_plus" ><i class="fa fa-plus"></i></button>
            </div>
            <div class="col-md-4">
                <select class="form-control"  id="vlab_test"  >
                    <option  disabled="disabled" selected="selected" value="">Select Test...</option>
                    <?php
                    $labTest = $db->getAll("select * from lab_test");
                    foreach($labTest as $test){
                        echo "<option value='".$test['test']."' >".$test['test']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <input class="form-control" id="vlab_result" placeholder="Result"  />
            </div>


        </div>

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


        <div class="form-group">


            <div class="col-md-offset-1 col-md-2">
                <button type="submit" class="btn btn-block btn-primary " name="submit" value="save" ><b>Save</b></button>
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
