          <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" id="psearch" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" id="psubmit" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <?php 
							if($_SESSION['right'] == 9){
						?>
                        <li>
                        	<a href="#" ><i class="fa fa-bed fa-fw"></i> HMO<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<?php
									if($_SESSION['right'] == 4){
								?>
                            	<li>
                                	<a href="register_hmo.php">New HMO</a>
                                </li>
                                <?php
									}
								?>
                                <li>
                                	<a href="hmo.php">HMO List</a>
                                </li>
                            </ul>
                        </li>
                        <?php
							}
							if($_SESSION['right'] == 4 || $_SESSION['right'] == 8){
						?>
                        <li>
                        	<a href="#" ><i class="fa fa-bed fa-fw"></i> Prescription<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="pharmacy_list.php">Prescription</a>
                                </li>
                            	<li>
                                	<a href="prescription_report.php">Prescription Report</a>
                                </li>
                            </ul>
                        </li>
                                <li>
                                    <a href="#" ><i class="fa fa-bed fa-fw"></i> Medicines<span class="fa arrow"></span></a>
                                    <ul class="nav nav-second-level">

                                            <li>
                                                <a href="add_supplier.php">Add supplier</a>
                                            </li>
                                        <li>
                                            <a href="supplier_list.php">Supplier List</a>
                                        </li>

                                        <li>
                                            <a href="add_medicine.php">Add medicine</a>
                                        </li>
                                        <li>
                                            <a href="medicine_list.php">Medicine List</a>
                                        </li>
                                        <li>
                                            <a href="medicine_report.php">Medicine Report</a>
                                        </li>
                                    </ul>
                                </li>
                        <?php
							}
							if($_SESSION['right'] == 4 || $_SESSION['right'] == 5){
						?>
                        
                        <li>
                            <a href="patients.php"><i class="fa fa-bed fa-fw"></i> Patients</a>
                        </li>
                        <li>
                        	<a href="#" ><i class="fa fa-bed fa-fw"></i> Ward<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	<?php
									if($_SESSION['right'] == 4){
								?>
                            	<li>
                                	<a href="add_ward.php">New</a>
                                </li>
                                <?php
									}
								?>
                                <li>
                                	<a href="ward_patient.php">Patients</a>
                                </li>
                            </ul>
                        </li>
                        
                        <?php
							}
							if($_SESSION['right'] == 4 || $_SESSION['right'] == 7){
						?>
                        <li>
                            <a href="#"><i class="fa fa-bed fa-fw"></i> Laboratory<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">

                                    <li>
                                        <a href="lab-list.php">New</a>
                                    </li>

                                <li>
                                    <a href="lab-history.php">Patient History</a>
                                </li>
                                <li>
                                    <a href="lab-nonhistory.php">Non Patient History</a>
                                </li>
                            </ul>
                        </li>
                                <li>
                                    <a href="lab_test.php"><i class="fa fa-bed fa-fw"></i> Add Lab Test</a>
                                </li>

                        <?php
							}
							if($_SESSION['right'] == 4 || $_SESSION['right'] == 6){
						?>
                        <li>
                        	<a href="#"><i class="fa fa-bed fa-fw"></i> Doctor<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="patient_list.php">In Patients</a>
                                </li>
                                <li>
                                    <a href="outpatient_list.php">Out Patients</a>
                                </li>
                                <li>
                                <?php
									$today = date('Y-m-d',strtotime('today'));
									$tomorrow = date('Y-m-d',strtotime('+1 day'));
								?>
                                    <a href="appointment_view.php?from=<?php echo $today ?>&to=<?php echo $tomorrow ?>">View Appointment</a>
                                </li>
                            </ul>
                            
                            <!-- /.nav-second-level -->
                        </li>
                        <?php
							}
							if($_SESSION['right'] == 4 || $_SESSION['right'] == 1 ){
						?>
                        <li>
                            <a href="#"><i class="fa fa-bed fa-fw"></i> Registrar<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="register.php">Registration</a>
                                </li>
                                <li>
                                    <a href="register_group.php">New Group</a>
                                </li>
                                <li>
                                    <a href="group.php?type=0">Group List</a>
                                </li>
                                <li>
                                    <a href="patients.php">View Patient</a>
                                </li>
                                <li>
                                <?php
									$today = date('Y-m-d',strtotime('today'));
									$tomorrow = date('Y-m-d',strtotime('+1 day'));
								?>
                                    <a href="appointment_view.php?from=<?php echo $today ?>&to=<?php echo $tomorrow ?>">View Appointment</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php
							}
							if($_SESSION['right'] >= 2 && $_SESSION['right'] <= 4){
						?>
                        
                         <li>
                            <a href="#"><i class="fa fa-exchange fa-fw"></i> Billing<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                            	
                            	<li>
                                    <a href="pbill.php">Bill Patient</a>
                                </li>
                                <li>
                                    <a href="billing_view.php">Manage Billing</a>
                                </li>
                                <li>
                                    <a href="bill_list.php">Treatment Approval</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php
							}
							if($_SESSION['right'] >= 2 && $_SESSION['right'] <= 4){
							    if($_SESSION['right'] > 2) {
                                    ?>

                                    <li>
                                        <a href="#"><i class="fa fa-area-chart fa-fw"></i> Reports<span
                                                    class="fa arrow"></span></a>
                                        <ul class="nav nav-second-level">
                                            <?php
                                            $datetime = new DateTime();
                                            $curyear = $datetime->format('Y');
                                            $from = $curyear . '-00-00';
                                            $to = $curyear . '-12-31';
                                            ?>
                                            <li>
                                                <a href="preport.php?from=<?php echo $from ?>&to=<?php echo $to ?>">Patient
                                                    Report</a>
                                            </li>
                                            <li>
                                                <a href="ureport.php?from=<?php echo $from ?>&to=<?php echo $to ?>">User
                                                    Report</a>
                                            </li>
                                            <li>
                                                <a href="income.php?from=<?php echo $from ?>&to=<?php echo $to ?>">Income
                                                    Report</a>
                                            </li>
                                            <li>
                                                <a href="sreport.php?from=<?php echo $from ?>&to=<?php echo $to ?>">Service
                                                    Report</a>
                                            </li>
                                            <li>
                                                <a href="rprofit.php?year=<?php echo $curyear ?>">Profit And Loss</a>
                                            </li>

                                        </ul>
                                        <!-- /.nav-second-level -->
                                    </li>


                                    <li>
                                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Service Management<span
                                                    class="fa arrow"></span></a>
                                        <ul class="nav nav-second-level">
                                            <li>
                                                <a href="addservices.php">Add Service</a>
                                            </li>
                                            <li>
                                                <a href="view_service.php">View Service</a>
                                            </li>

                                            <!--
                                             <li>
                                                 <a href="#">Service Configuration <span class="fa arrow"></span></a>
                                                 <ul class="nav nav-third-level">
                                                     <li>
                                                         <a href="#" data-toggle="modal" data-target="#modal-servicetitle" > Add Service Title</a>
                                                     </li>

                                                     <li>
                                                         <a href="#" data-toggle="modal" data-target="#modal-servicetype">Add Service Type</a>
                                                     </li>
                                                 </ul>

                                             </li>
                                             -->
                                        </ul>
                                        <!-- /.nav-second-level -->
                                    </li>
                                    <?php
                                }
                                    ?>
                                    <li>
                                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Income Management<span
                                                    class="fa arrow"></span></a>
                                        <ul class="nav nav-second-level">
                                            <li>
                                                <a href="add_income.php">Add Income</a>
                                            </li>
                                            <li>
                                                <a href="income_analyst.php">Income Report</a>
                                            </li>


                                        </ul>
                                        <!-- /.nav-second-level -->
                                    </li>
                                    <li>
                                        <a href="#"><i class="fa fa-wrench fa-fw"></i> Expenditure Management<span
                                                    class="fa arrow"></span></a>
                                        <ul class="nav nav-second-level">
                                            <li>
                                                <a href="expenditure.php">Add Expenditure</a>
                                            </li>
                                            <li>
                                                <a href="expense_analyst.php">Expenditure Report</a>
                                            </li>


                                        </ul>
                                        <!-- /.nav-second-level -->
                                    </li>

                                    <?php

							}
							
						?>  
                        
                        <?php
							if($_SESSION['right'] == 4 ){
						?>
                        <li>
                        <a href="log.php?from=<?php echo $today ?>&to=<?php echo $tomorrow ?>"><i class="fa fa-list fa-fw"></i> Log</a>
                        </li>
                        <?php	
							}
						?>
                        
                        
                       
                        <!--<li>
                            <a href="#"><i class="fa fa-cog fa-fw"></i> Settings<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="printers.php" >Set Printer</a>
                                </li>
                               
                            </ul>
                            
                        </li>-->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>