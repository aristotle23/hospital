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
                    <h1 class="page-header">Medicine Report</h1>
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
                                        <th>Type</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Supply Quantity</th>
                                        <th>Sold Quantity</th>
                                        <th>In Stock</th>
                                        <th>Total Sold</th>
                                        <th>Total Purchase</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $medicineArr = $db->getAll("select * from medicine");
                                foreach ($medicineArr as $medicine){
                                    $sold = $db->getOne("select sum(quantity) as quantity from treatment where medicine_id = ? and sign = 1",array($medicine['id']));
                                    $instock = $medicine['quantity'] - $sold['quantity'] ;
                                    $ttlsold = $medicine['selling_price'] * $sold['quantity'];
                                    $ttlpurchase = $medicine['quantity'] * $medicine['cost'];
                                    echo "<tr>";
                                    echo "<td>".$medicine['name']."</td>";
                                    echo "<td>".$medicine['type']."</td>";
                                    echo "<td>".$medicine['cost']."</td>";
                                    echo "<td>".$medicine['selling_price']."</td>";
                                    echo "<td>".$medicine['quantity']."</td>";

                                    echo "<td>".$sold['quantity']."</td>";


                                    echo "<td>".$instock."</td>";

                                    echo "<td>".$ttlsold."</td>";
                                    echo "<td>".$ttlpurchase."</td>";
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