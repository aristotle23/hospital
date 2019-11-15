<?php
require_once "script/ini.php";
if(isset($_REQUEST['return']) && $_REQUEST['return'] == "true"){
    $medicineId = $_REQUEST['medid'];
    $return = $_REQUEST['ret'];
    $tid = $_REQUEST['tid'];
    $db->execute("update medicine set quantity = quantity + ? where id = ?",array($return,$medicineId));
    $db->execute("update treatment set quantity = quantity - ? where id = ?",array($return,$tid));
    header("location:?success=Return successful");
}
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Prescription Report</h1>
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
                        
                            <table width="100%" class="table table-striped table-bordered table-hover prescription-list" >
          
                                <thead>
                                    <tr>
                                    	<th width="10%" >Date</th>
                                        <th>Hospital ID</th>
                                        <th>Name</th>      
                                        <th>Medicine</th>
                                        <th width="8%">Sex</th>
                                        <th>Doctor</th>
                                        <th>Pharmacist</th>
                                        <th width="14%">Tasks</th>
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
    <div class="modal fade " id="detailmodal" role="dialog">
        <div class="modal-dialog modal-lg ">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Prescription Detail</h4>
                </div>
                <div class="modal-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                        <tr>
                            <th >Prescription</th>
                            <th >Pre. Quantity</th>
                            <th >Used Quantity</th>
                            <th >Return</th>
                            <th >Task</th>
                        </tr>
                        </thead>
                        <tbody id="detailCon">
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
    <div class="modal fade " id="refillmodal" role="dialog">
        <div class="modal-dialog modal-lg ">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Refill History</h4>
                </div>
                <div class="modal-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" >
                        <thead>
                        <tr>
                            <th >Date Time</th>
                            <th >Quantity</th>
                            <th >Pharmacist</th>
                        </tr>
                        </thead>
                        <tbody id="refillCon">
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
        $(document).on("click",".btndetail",function (e) {
            e.preventDefault();
            let $this = $(this);
            let pid = $this.data("pid");
            let date = $this.data("date")
            let tr = null;
            let btn = null;
            let btnrefill = null;
            let record = null;
            let con = $("#detailCon");
            con.empty();
            var param = {
                generate : "prehist",
                pid : pid,
                date : date
            };
            $.post("script/ajax.php",param,function (data) {

                for(var i = 0 ; i < data.length ; i++){
                    tr = $("<tr>");
                    record  = data[i];
                    console.log(record['refill']);
                    if(record['return'] >= 1){
                        btn = $('<a href="?return=true&medid='+record['mid']+'&ret='+record['return']+'&tid='+record['tid']+'" class="btn btn-primary">');
                        btn.text("Return");
                    }else{
                        btn = $('<span class="help-block ">')
                        btn.text("Done");
                    }
                    if(record['refill'] === true){
                        btnrefill = $('<a data-toggle="modal" data-target="#refillmodal" data-tname="'+record['name']+'" data-tid="'+record['tid']+'" class="btn btn-link btnrefill">');
                        btnrefill.text("Refills");
                    }else{
                        btnrefill = $('<span class="text-muted">')
                        btnrefill.text("No refills");
                    }
                    btnrefill.css("margin-left","10px");
                    tr.append($("<td>").text(record['name']));
                    tr.append($("<td>").text(record['quantity']));
                    tr.append($("<td>").text(record['used']));
                    tr.append($("<td>").text(record['return']));
                    tr.append($("<td>").append(btn).append(btnrefill));
                    con.append(tr);

                }
            },"json")
        })
    </script>
    <script >
        $(document).on("click",".btnrefill",function (e) {
            e.preventDefault();
            let $this = $(this);
            let tid = $this.data("tid");
            let tr = null;
            let record = null;
            let con = $("#refillCon");
            con.empty();
            $("#refillmodal .modal-title").text($this.data("tname")).append($("<small> Refill History</small>"));
            var param = {
                generate : "refillhist",
                tid : tid
            };
            $.post("script/ajax.php",param,function (data) {
                for(var i = 0 ; i < data.length ; i++){
                    tr = $("<tr>");
                    record  = data[i];
                    tr.append($("<td>").text(record['date']));
                    tr.append($("<td>").text(record['quantity']));
                    tr.append($("<td>").text(record['name']));
                    con.append(tr);

                }
            },"json")
        })
    </script>
<?php
require_once "template/footer.php";
?>