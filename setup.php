<?php
date_default_timezone_set("Africa/Lagos");
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
	<link rel="icon" type="image/png" href="assets/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Get Shit Done Bootstrap Wizard by Creative Tim</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

	<!--     Fonts and icons     -->
	    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

	<!-- CSS Files -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/gsdk-bootstrap-wizard.css" rel="stylesheet" />

</head>

<body>
<div class="image-container set-full-height" style="background-image: url('assets/img/wizard.jpg')">

    <!--   Big container   -->
    <div class="container">
        <div class="row">
        <div class="col-sm-8 col-sm-offset-2">

            <!--      Wizard container        -->
            <div class="wizard-container">

                <div class="card wizard-card" data-color="orange" id="wizardProfile">
                    <form action="" method="">
                <!--        You can switch ' data-color="orange" '  with one of the next bright colors: "blue", "green", "orange", "red"          -->

                    	<div class="wizard-header">
                        	<h3>
                        	   <b>HOSPITAL</b> SETUP <br>
                        	   <small>This step by step setup will guide through successfull setup without any aid.</small>
                        	</h3>
                    	</div>

						<div class="wizard-navigation">
							<ul>
                            	<li><a href="#database" data-toggle="tab">Database</a></li>
	                            <li><a href="#about" data-toggle="tab">Hospital Information</a></li>
	                            <li><a href="#printer" data-toggle="tab">POS printer(s)</a></li>
	                            
	                        </ul>

						</div>

                        <div class="tab-content">
                        	<div class="tab-pane" id="database" >
                            <div class="row">
                            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                            	<h4 class="info-text ">
                                	The password field requires the password of the root user you use when installing MySql
                                </h4>
                            </div>
                            </div>
                              <div class="row">
                                  <div class="col-sm-4 col-sm-offset-1">
                                     <div class="picture-container">
                                          <div class="mysqllogo">
                                              <img src="assets/img/mysql.png" width="100%"/>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Username:</label><br />
                                        <label><b>root</b></label>
                                      </div>
                                      <div class="form-group">
                                        <label>Password <small>(required)</small></label>
                                        <input name="dbpassword" type="text" class="form-control" autocomplete="off">
                                      </div>
                                      <div class="form-group">
                                      <button class="btn btn-primary btn-sm checkdb">Check</button> <i class="fa fa-check fa-fw" id="dbok"></i>
                                       <i class="fa fa-spinner fa-spin fa-1x fa-fw " id="dbloading"></i><span class="sr-only">Loading...</span>
                                       <i class="fa fa-times fa-fw" id="dbbad"></i>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div class="tab-pane" id="about">
                              <div class="row">
                                  <h4 class="info-text"> All Hospital information inserted should be correct and accurate</h4>
                                  <div class="col-sm-4 col-sm-offset-1">
                                     <div class="picture-container">
                                          <div class="picture">
                                              <img src="assets/img/default-avatar.png" class="picture-src" id="wizardPicturePreview" title=""/>
                                              <input type="file" name="logo" id="wizard-picture">
                                          </div>
                                          <h6>Hospital Logo</h6>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="form-group">
                                        <label>Name <small>(required)</small></label>
                                        <input name="name" type="text" class="form-control" autocomplete="off">
                                      </div>
                                      <div class="form-group">
                                        <label>Phone <small>(required)</small></label>
                                        <input name="phone" type="text" class="form-control" autocomplete="off">
                                      </div>
                                  </div>
                                  <div class="col-sm-10 col-sm-offset-1">
                                      <div class="form-group">
                                          <label>Address <small>(required)</small></label>
                                          <input name="address" type="text" class="form-control" autocomplete="off">
                                      </div>
                                  </div>
                              </div>
                            </div>
                            
                            <div class="tab-pane" id="printer">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="info-text"> Are you living in a nice area? </h4>
                                    </div>
                                    <div class="col-sm-7 col-sm-offset-1">
                                         <div class="form-group">
                                            <label>Street Name</label>
                                            <input type="text" class="form-control" placeholder="5h Avenue">
                                          </div>
                                    </div>
                                    <div class="col-sm-3">
                                         <div class="form-group">
                                            <label>Street Number</label>
                                            <input type="text" class="form-control" placeholder="242">
                                          </div>
                                    </div>
                                    <div class="col-sm-5 col-sm-offset-1">
                                         <div class="form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" placeholder="New York...">
                                          </div>
                                    </div>
                                    <div class="col-sm-5">
                                         <div class="form-group">
                                            <label>Country</label><br>
                                             <select name="country" class="form-control">
                                                <option value="Afghanistan"> Afghanistan </option>
                                                <option value="Albania"> Albania </option>
                                                <option value="Algeria"> Algeria </option>
                                                <option value="American Samoa"> American Samoa </option>
                                                <option value="Andorra"> Andorra </option>
                                                <option value="Angola"> Angola </option>
                                                <option value="Anguilla"> Anguilla </option>
                                                <option value="Antarctica"> Antarctica </option>
                                                <option value="...">...</option>
                                            </select>
                                          </div>
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="wizard-footer height-wizard">
                            <div class="pull-right">
                                <input type='button' class='btn btn-next btn-fill btn-warning btn-wd btn-sm' name='next' value='Next' />
                                <input type='button' class='btn btn-finish btn-fill btn-warning btn-wd btn-sm' name='finish' value='Finish' />

                            </div>

                            <div class="pull-left">
                                <input type='button' class='btn btn-previous btn-fill btn-default btn-wd btn-sm' name='previous' value='Previous' />
                            </div>
                            <div class="clearfix"></div>
                        </div>

                    </form>
                </div>
            </div> <!-- wizard container -->
        </div>
        </div><!-- end row -->
    </div> <!--  big container -->

    <div class="footer">
        <div class="container">
            &copy; Copyright <?php echo date('Y',strtotime("today")) ?>, Develop by Intelogiic
        </div>
    </div>

</div>

</body>

	<!--   Core JS Files   -->
	<script src="assets/js/jquery-2.2.4.min.js" type="text/javascript"></script>
	<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="assets/js/jquery.bootstrap.wizard.js" type="text/javascript"></script>

	<!--  Plugin for the Wizard -->
	<script src="assets/js/gsdk-bootstrap-wizard.js"></script>

	<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
	<script src="assets/js/jquery.validate.min.js"></script>
    
</html>
