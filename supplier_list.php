<?php
require_once "script/ini.php";
if(isset($_REQUEST['del'])){
    $db->execute("delete from supplier where  id = ?",array($_REQUEST['del']));
    header("location: supplier_list.php");
    exit;
}
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Supplier List</h1>
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
                                        <th >Phone</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $supplierArr = $db->getAll("select * from supplier");
                                foreach ($supplierArr as $supplier){
                                    echo "<tr>";
                                    echo "<td>".$supplier['name']."</td>";
                                    echo "<td>".$supplier['phone']."</td>";
                                    echo "<td>".$supplier['email']."</td>";
                                    echo "<td>".$supplier['address']."</td>";
                                    echo "<td><div class='row'><div class='col-md-6' style='padding-right: 0px'>
					<a href='add_supplier.php?sp=".$supplier['id']."' class='btn btn-primary btn-block'>edit</a></div>
					<div class='col-md-6' style='padding-left: 5px'>
					<a href='?del=".$supplier['id']."' class='btn btn-primary btn-block'>Delete</a></div></div></td>";
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