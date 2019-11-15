<?php
require_once "script/patient.php";
require_once "script/ini.php";

if(isset($_REQUEST['pid'])){
    $patient =  new patient($_REQUEST['pid']);
    $patientIno = $patient->info();
    //$patient = $patient['name'];

}else{
    header("location:index.php");
    exit;
}

if(isset($_REQUEST['sign']) ){

    $treatmentId = $_REQUEST['sign'];
    $note = $_REQUEST[$treatmentId];

    $db->execute("insert into nursing (note, treatment_id, patient_id, user_id) VALUES (?,?,?,?)",
        array($note,$treatmentId,$_REQUEST['pid'],$_SESSION['user_id']));

    header("location:?pid=".$_REQUEST['pid']."&success=Treatment Signed");
    exit;
}

require_once "template/header.php";
require_once "template/sidebar.php";
?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header" style="margin-bottom:50px">Treatment <small><?php echo $patientIno['name'] ?></small></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>



    </div>



    <form class="form-horizontal" role="form"  method="post">
        <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'] ?>"  />
        <div class="form-group">
            <div class="col-md-12">
            <label class="col-md-3 ">Prescription</label>
            <label class="col-md-2 ">Dosage</label>
            <label class="col-md-2 ">Routine</label>
                <label class="col-md-2 ">Route</label>
                <label class="col-md-3 ">Doctor Note</label>
            </div>
        </div>
        <?php
        $result = $db->getAll("select name, dosage,routine,t.id, medicine_id, t.route, t.note,t.quantity from treatment t inner join medicine m on t.medicine_id = m.id where patient_id = ? ",
            array($_REQUEST['pid']));
        foreach ($result as $treatment) {
            $lastSign = $db->getOne("select date, (select count(id) from nursing where treatment_id = ?) as ttl from nursing where treatment_id = ? order by date desc limit 1",array($treatment['id'],$treatment['id']));
            $return = $treatment['quantity'] - ($treatment['dosage'] * $lastSign['ttl']);
            $timeAgo = ($lastSign) ? timeAgo($lastSign['date']) : "never";
            ?>
            <div class="form-group " id="treatment-group">
                <div class="col-md-12">
                    <div class="col-md-3">

                        <input type="text" class="form-control" disabled value="<?php echo $treatment['name'] ?>"  />
                    </div>
                    <div class="col-md-2" >
                        <input class="form-control" id="treatment-dosage" placeholder="Dosage"
                               disabled value="<?php echo $treatment['dosage'] ?>" />
                    </div>
                    <div class="col-md-2" >
                        <input class="form-control" id="treatment-routine" placeholder="Frequency"
                               disabled value="<?php echo $treatment['routine'] ?>" />
                    </div>
                    <div class="col-md-2" >
                        <input class="form-control" id="treatment-routine" placeholder="Route Of Administration"
                               disabled value="<?php echo $treatment['route'] ?>" />
                    </div>
                    <div class="col-md-3" >
                        <textarea class="form-control" id="treatment-routine" disabled ><?php echo $treatment['note'] ?></textarea>
                    </div>
                    <div class="col-md-3" >
                        <textarea class="form-control" id="treatment-routine" placeholder="Nurse Note" name="<?php echo $treatment['id'] ?>"></textarea>
                    </div>
                    <div class="col-md-2" >
                        <?php
                        if($return >= 1){
                            ?>
                            <button type="submit" name="sign" value="<?php echo $treatment['id'] ?>"
                                    class="btn btn-block btn-primary "><b>Sign</b></button>
                            <?php
                        }else{
                            ?>
                            <span class="help-block">Refill</span>
                            <?php
                            }
                            ?>
                    </div>
                    <div class="col-md-6" >
                        <span  class="help-block btnhist" style="cursor: pointer" data-tid="<?php echo $treatment['id'] ?>" data-tname="<?php echo $treatment['name'] ?>" data-toggle="modal" data-target="#histmodal">Administered <?php
                            echo $timeAgo ;
                            if($timeAgo != "never") {
                                ?>
                                <i class="fa fa-arrow-circle-o-right text-primary"></i>
                                <?php
                            }
                            ?>
                        </span>
                    </div>
                </div>

            </div>
            <?php
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
<div class="modal fade " id="histmodal" role="dialog">
    <div class="modal-dialog modal-lg ">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Treament History</h4>
            </div>
            <div class="modal-body">
                <table width="100%" class="table table-striped table-bordered table-hover" >
                    <thead>
                    <tr>
                        <th >Date Time</th>
                        <th >Note</th>
                        <th >Nurse</th>
                    </tr>
                    </thead>
                    <tbody id="histCon">
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
<script >
    $(document).on("click",".btnhist",function (e) {
        e.preventDefault();
        let $this = $(this);
        let tid = $this.data("tid");
        let tr = null;
        let record = null;
        let con = $("#histCon");
        con.empty();
        $("#histmodal .modal-title").text($this.data("tname")).append($("<small> Treatment History</small>"));
        var param = {
            generate : "treatmenthist",
            tid : tid
        };
        $.post("script/ajax.php",param,function (data) {
            for(var i = 0 ; i < data.length ; i++){
                tr = $("<tr>");
                record  = data[i];
                tr.append($("<td>").text(record['date']));
                tr.append($("<td>").text(record['note']));
                tr.append($("<td>").text(record['name']));
                con.append(tr);

            }
        },"json")
    })
</script>
<?php
require_once "template/footer.php";
?>
