<?php
require_once "script/ini.php";
require_once "template/header.php";
require_once "template/sidebar.php";
?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Profit and Loss</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                 <div class="row">
                    <div class="col-lg-2">
                        <select class="form-control " name="year" id="year" >
					   <?php
                        $datetime = new DateTime();
                        $curyear = $datetime->format('Y');
                        $year = $curyear + 10;
                        for($i = 1980; $i < $year ; $i++){
                            if ($i == $_REQUEST['year']){
                                print '<option selected="selected">'.$i.'</option>';
                            }else{
                                print '<option>'.$i.'</option>';
                            }
                        }
                       ?>
                        </select>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <canvas id="myChart"></canvas>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->
        <script type="text/javascript">
		year = location.search;
		year = year.split('=');
		year = year[1];
		$.ajax({
			url:"script/ajax.php?form=rprofit&year="+year,
			dataType:"json",
			success: function(value){
				var ctx = document.getElementById('myChart');
				var chart = new Chart(ctx, {
					// The type of chart we want to create
					type: 'line',
					// The data for our dataset
					data: {
						labels: ["January", "February", "March", "April", "May", "June", "July","August","September","October","November","December"],
						datasets: [{
							label: "Profit (+) , Loss (-)",
							backgroundColor: 'rgb(255, 99, 132)',
							borderColor: 'rgb(255, 99, 132)',
							data: value,
						}]
					},
				
					// Configuration options go here
					options: {}
				});
			}
		})
		</script>
        <script>
		$("#year").on("change",function(e){
			year = $(this).val();
			location = location.origin+location.pathname+"?year="+year
		})
		</script>
<?php
require_once "template/footer.php";
?>