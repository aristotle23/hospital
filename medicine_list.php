<?php
require_once "script/ini.php";
if(isset($_REQUEST['del'])){
    $db->execute("delete from medicine where  id = ?",array($_REQUEST['del']));
}
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Medicine List</h1>
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
                        <div class="panel-body ">

                            <table width="100%" class="table table-striped table-bordered table-hover dataTables-example" >
          
                                <thead>
                                    <tr>
                                    	<th >Name</th>
                                        <th >Generic Name</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th >Suppliers</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>Low Stock</th>
                                        <th>Expire</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $medicineArr = $db->getAll("select * from medicine");
                                foreach ($medicineArr as $medicine){
                                    $supplierArr = array();
                                    echo "<tr>";
                                    echo "<td>".$medicine['name']."</td>";
                                    echo "<td>".$medicine['gname']."</td>";
                                    echo "<td>".$medicine['category']."</td>";
                                    echo "<td>".$medicine['type']."</td>";
                                    $supplierIds = $db->getAll("select supplier_id from supply where item_id = ?",array($medicine['id']));
                                    foreach ($supplierIds as $supplierId){
                                        $supplier = $db->getOne("select name from supplier where id = ?",array($supplierId['supplier_id']));
                                        array_push($supplierArr, $supplier['name']);
                                    }
                                    echo "<td>".join(",",array_unique($supplierArr))."</td>";
                                    echo "<td>".$medicine['cost']."</td>";
                                    echo "<td>".$medicine['selling_price']."</td>";
                                    echo "<td>".$medicine['unit']."</td>";
                                    echo "<td>".$medicine['quantity']."</td>";
                                    echo "<td>".$medicine['low_level']."</td>";
                                    echo "<td>".$medicine['expdate']."</td>";
                                    echo "<td>";
                                        echo "<div class='row'>";
                                            /*echo "<div class='col-md-4' style='padding-right: 0px' >";
                                            echo "<a href='add_medicine.php?med=".$medicine['id']."' class='btn btn-primary btn-block'>Update</a>";
                                            echo "</div>";*/
                                            echo "<div class='col-md-4' style='padding-right: 0px' >";
                                            echo "<a href='topup_medicine.php?med=".$medicine['id']."' class='btn btn-primary btn-block'>Add</a>";
                                            echo "</div>";
                                            echo "<div class='col-md-4' style='padding-right: 0px' >";
                                            echo "<a href='?del=".$medicine['id']."' class='btn btn-primary btn-block'>Delete</a>";
                                            echo "</div><div class='col-md-4' style='padding-right: 0px' >";
                                            echo "<a href='' class='btn btn-primary btn-block btnSupplyHIst' data-medname='".$medicine['name']."' data-toggle=\"modal\" data-mid='".$medicine['id']."' data-target='#supply-hist'>History</a>";
                                            echo "</div>";
                                        echo "</div>";
                                    echo "</td>";
                                    echo "</tr>";
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

    <div class="modal fade" id="supply-hist" role="dialog">
        <div class="modal-dialog ">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" >ss<small>Supply History</small></h4>
                </div>
                <div class="modal-body">
                    <table width="100%" class="table table-striped table-bordered table-hover" >

                        <thead>
                        <tr>
                            <th >Date Time</th>
                            <th >Supplier</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody id="supply-hist-con">
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">close</button>
                </div>
            </div>
        </div>
    </div>
        <!-- /#page-wrapper -->
<script >
    $(document).on("click",".btnSupplyHIst",function (e) {
        e.preventDefault();
        var $this = $(this);
        var medId = $this.data("mid");
        var tr = null;
        var record = null;
        var con = $("#supply-hist-con");
        con.empty();
        $("#supply-hist .modal-title").text($this.data("medname")).append($("<small> Supply History</small>"));
        var param = {
            generate : "medhist",
            medid : medId
        }
        $.post("script/ajax.php",param,function (data) {
            for(var i = 0 ; i < data.length ; i++){
                tr = $("<tr>");
                record  = data[i];
                tr.append($("<td>").text(record['date']));
                tr.append($("<td>").text(record['supplier']));
                tr.append($("<td>").text(record['quantity']));
                con.append(tr);

            }
        },"json")
    })
</script>

<?php
require_once "template/footer.php";
?>