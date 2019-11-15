<style>
.herror{
	display: none;
}
</style>

      <!-- /#page-wrapper -->
</div>
<div class="modal fade" id="modal-servicetitle" role="dialog">
    <div class="modal-dialog  ">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD SERVICE TITLE</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" action="script/ajax.php">
           <input type="hidden" name="modal" value="addservicetitle"  />
            <div class="form-group">
              <label class="col-md-3 control-label" for="receiver">Service Title</label>
              <div class="col-md-9 ">
              	<input type="text" class="form-control"  autocomplete="off"  name="title" />
                <span class="help-block herror">Input Already Exists</span>
              </div>
          	</div>

        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary"  >Save</button>
        </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<div class="modal fade" id="modal-servicetype" role="dialog">
    <div class="modal-dialog  ">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD SERVICE TYPE</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" action="script/ajax.php">
           <input type="hidden" name="modal" value="addservicetype"  />
           
            <div class="form-group">
              <label class="col-md-3 control-label" for="receiver">Service Title</label>
              <div class="col-md-9 ">
              	<select name="title"  class="form-control">
                <option value="Null">Please Select ...</option>
                <?php 
					$titles = $db->getAll("select id, title from service_title");
					foreach ($titles as $title){
						print '<option value="'.$title['id'].'" >'.$title['title'].'</option>';
					}
				?>
                </select>
              </div>
          	</div>
            
            <div class="form-group">
              <label class="col-md-3 control-label" for="receiver">Service Type</label>
              <div class="col-md-9 ">
              	<input type="text" class="form-control"  autocomplete="off"  name="type" />
                <span class="help-block herror">Service Type Already Exists</span>
              </div>
          	</div>

        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary"  >Save</button>
        </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<div class="modal fade" id="modal-edituser" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT USER</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" action="script/ajax.php">
          <input type="hidden" name="modal" value="edituser"  />
           <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Old Password</label>
              <div class="col-md-8">
              	<input type="password" class="form-control"  required="required"  autocomplete="off"  name="oldpass" />
                <span class="help-block herror">Incorrect old password</span>
              </div>
          	</div> 
           <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">New Password</label>
              <div class="col-md-8">
              	<input type="password" class="form-control" required="required"  autocomplete="off"  name="newpass" />
              </div>
          	</div> 
          <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Re-type Password</label>
              <div class="col-md-8">
              	<input type="password" class="form-control" required="required"  autocomplete="off" name="repass"  />
                <span class="help-block herror">Re-type password does not match </span>
              </div>
          	</div>  
          
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="btnedituser">Save</button>
        </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<div class="modal fade" id="modal-newuser" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">NEW USER</h4>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form" action="script/ajax.php">
          <input type="hidden" name="modal" value="newuser"  />
          	<div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Full Name</label>
              <div class="col-md-8">
              	<input type="text" class="form-control" name="name" autocomplete="off" required="required" />
              </div>
			
          	</div>
            <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Username</label>
              <div class="col-md-8">
              	<input type="text" class="form-control" name="username" autocomplete="off" required="required" />
                <span class="help-block herror">This user already exist </span>
              </div>
			
          	</div>
            <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Access Right</label>
              <div class="col-md-8">
              	<select name="right" class="form-control">
                <option value="null">Please select...</option>
                <?php
					$result = $db->getAll("select * from access_right order by level desc");
					foreach ($result as $right){
                	 	print '<option value="'.$right['level'].'">'.$right['access'].'</option>';
					}
					?>
                </select>
              </div>
          	</div>
           <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Password</label>
              <div class="col-md-8">
              	<input type="password" class="form-control" autocomplete="off" required="required" name="password" />
				
              </div>
          	</div> 
          <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Re-type Password</label>
              <div class="col-md-8">
              	<input type="password" class="form-control" name="rpassword"  autocomplete="off" required="required" />
                <span class="help-block herror " style="color:red">Both Password and Re-type password does not match </span>
              </div>
          	</div>  
         <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Question</label>
              <div class="col-md-8">
              	<input type="text" class="form-control" name="question" autocomplete="off" required="required" />
              </div>
			
          	</div>
         <div class="form-group">
              <label class="col-md-4 control-label" for="receiver">Answer</label>
              <div class="col-md-8">
              	<input type="text" class="form-control" name="ans" autocomplete="off" required="required" />
              </div>
			
          	</div>
            
        </div>
        <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="btnnewuser">Save</button>
        </form>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
<div class="modal fade" id="allalert" role="dialog">
    <div class="modal-dialog ">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?php echo isset($_REQUEST['success']) ? "SUCCESS" : (isset($_REQUEST['failed']) ? 'ERROR': NULL) ?></h4>
        </div>
        <div class="modal-body">
            <?php echo isset($_REQUEST['success']) ? $_REQUEST['success'] : (isset($_REQUEST['failed']) ? $_REQUEST['failed']: NULL) ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="hmo-serivce" role="dialog">
    <div class="modal-dialog ">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Upload Service</h4>
        </div>
        <div class="modal-body"> 
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="hmo_id" value="<?php echo $_REQUEST['hmo'] ?>"  />
            <input type="hidden" name="upload_type" value="service"  />
            <div class="form-group">
            <label class="col-md-3 control-label">Cateogry</label>
            <div class="col-md-8">
            <select class="form-control" name="category">
            <?php
				$cats = $db->getAll("SELECT name, id FROM hmo_service_category where hmo_id = 0 or hmo_id = ?",array($_REQUEST['hmo']));
				foreach($cats as $cat){
					echo '<option value="'.$cat['id'].'">'.$cat['name'].'</option>';
				}
			?>
            </select>
            </div>
            </div>
            <div class="form-group">
            <label class="col-md-3 control-label">Excel File (.xlsx)</label>
            <div class="col-md-8">
            <input type="file" class="form-control" name="file"  />
            </div>
            </div>
            <div class="form-group">
            <div class="col-md-8 col-md-offset-3">
            <button name="upload" value="patient" class="btn btn-primary">Upload</button>
            </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="hmo-payment" role="dialog">
    <div class="modal-dialog ">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">HMO Payment </h4>
        </div>
        <div class="modal-body"> 
            <form class="form-horizontal" role="form" action="" method="post" >
            <input type="hidden" name="hmo" />
            <input type="hidden" name="date"  />
            <div class="form-group">
            <label class="col-md-3 control-label">Date</label>
            <div class="col-md-8">
            <label class="control-label" id="hmo-payment-date"></label>
            </div>
            </div>
            <div class="form-group">
            <label class="col-md-3 control-label">Amount</label>
            <div class="col-md-8">
            <input type="text" class="form-control" name="amount" onkeypress="return isNumber(event)" />
            </div>
            </div>
            <div class="form-group">
            <div class="col-md-8 col-md-offset-3">
            <button name="payment" value="hmo" class="btn btn-primary">Make Payment</button>
            </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  
<div class="modal fade" id="hmo-patient" role="dialog">
    <div class="modal-dialog ">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Patient Upload</h4>
        </div>
        <div class="modal-body"> 
            <form class="form-horizontal" role="form" action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="hmo_id" value="<?php echo $_REQUEST['hmo'] ?>"  />
            <input type="hidden" name="upload_type" value="patient"  />
            <div class="form-group">
            <label class="col-md-3 control-label">Excel File (.xlsx)</label>
            <div class="col-md-8">
            <input type="file" class="form-control" name="file"  />
            </div>
            </div>
            <div class="form-group">
            <div class="col-md-8 col-md-offset-3">
            <button name="upload" value="patient" class="btn btn-primary">Upload</button>
            </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" id="hmo-category" role="dialog">
    <div class="modal-dialog ">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Service Category</h4>
        </div>
        <div class="modal-body"> 
            <form class="form-horizontal" role="form" action="" method="post" >
            <input type="hidden" name="hmo_id" value="<?php echo $_REQUEST['hmo'] ?>"  />
  
            <div class="form-group">
                <label class="col-md-2 control-label">Name</label>
                <div class="col-md-8">
                <input type="text" class="form-control" list="name" name="name" autocomplete="off" />
                <datalist id="name">
                <?php
					$cats = $db->getAll("select name from hmo_service_category where hmo_id = ? or hmo_id = 0 ",array($_REQUEST['hmo']));
					foreach ($cats as $cat){
						echo '<option value="'.$cat['name'].'">';
					}
					
				?>
                </datalist>
                </div>
                </div>
            <div class="form-group">
            <div class="col-md-8 col-md-offset-2">
            <button name="submit" value="service_category" class="btn btn-primary">Save</button>
            </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  
<!-- Bootstrap Core JavaScript -->
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="vendor/metisMenu/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

<script type="text/javascript" src="js/script.js"></script>

<script src="vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="vendor/datatables-responsive/dataTables.responsive.js"></script>
<script src="js/bootstrap-datepicker.min.js"></script>
<script src="js/jquery.mask.js"></script>
 <script type="text/javascript" src="js/howler.min.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
};
    $(document).ready(function() {
        table = $('#dataTables-example, .dataTables-example').DataTable({
            responsive: true
        });
    });
    </script>
    <script>
$('.date').datepicker({
	"autoclose":true,
	"todayHighlight":true,
	"disableTouchKeyboard":true,
	"format": "yyyy-mm-dd"
});
	</script>
<?php if ($_SESSION['right'] >= 3) {?>
<script>
function updatenotice($this){
	id = $this.data('id')
	$.ajax({
		url:"script/ajax.php?state=updatenotify&id="+id,
		dataType:"json",
		success: function(e){
			notice = $(e)
		}
	})
}
$(".bnotify").on("click",function(e){ 
	updatenotice($(this)) 
})
/*var sound = new Howl({
      src: ['audio/sound1.ogg','audio/sound1.mp3']
    });*/
/*setInterval(function(){
notify = $("#edit-notification")
aid = notify.find("li:first-child");
aid = aid.data('id');
if(notify.children("li:not(.divider)").length <= 10){
	$.ajax({
		url:"script/ajax.php?state=enotification&aid="+aid,
		dataType:"json",
		success: function(e){
			notice = $(e)
			if(notice.length > 0){
				sound.pause();
				sound.play();
			}
			notify.prepend(notice)
			$(".bnotify").on("click",function(e){
				updatenotice($(this))
			 })
			
		}
	})
}
},1000);*/
</script>
<?php } ?>
<script>
$("#psubmit").on("click",function(e){
	e.preventDefault();
	psearch = $("#psearch")
	if(psearch.val() == ''){
		return false;
	}
	location = "patients.php?psearch="+psearch.val()
})
</script>
 <?php if(isset($_REQUEST['psearch'])) { ?>
 <script>
    table.search( <?php echo $_REQUEST['psearch'] ?> ).draw();
 </script>
 <?php } ?>  
 <?php if (isset($_REQUEST['success']) || isset($_REQUEST['failed'])) { ?>
 <script>
 $("#allalert").modal("show")
 </script>
 <?php } ?>
 <script>
 $(document).on("click",".print",function(e){
	 e.preventDefault()
	 $this = $(this);
	 url = $this.attr("href");
	 window.open(url,'receipt','resizable=0,width=233,height=500');
 })
 </script>
 <script>
$(document).on("click",'.seen',function(e){
	 e.preventDefault()
	 $this = $(this);
	 id = $this.data('id');
	 tr = $this.parent().parent();
	 $.ajax({
		 url:"script/ajax.php?state=seen&id="+id,
		 dataType:"json",
		 success: function(data){
			 location = location.origin+location.pathname+"?success=The appointment patient has been confirmed"
		 }
	 })
})
 </script>
 <script>
/* var sound = new Howl({
      src: ['audio/sound1.ogg','audio/sound1.mp3']
    });*/
 ul = $("#app-notice");
 $.ajax({
	 url:"script/ajax.php?state=appnotice",
	 dataType:"json",
	 success: function(data){
		 li =  $(data)
		 if(li.length > 0){
			 sound.pause();
			sound.play();
		 }
		 ul.prepend(li)
		 
	 }
 })
 $(document).on("click",".appnotice",function(e){
	 $this = $(this);
	 id = $this.data('id')
	  $.ajax({
		  url:"script/ajax.php?state=appupdate&id="+id,
		  dataType:"json",
		  success: function(e){
			  notice = $(e)
		  }
	  })
	 
 })
 </script>
<script>
$(document).on("click",".ubal",function(e){
	e.preventDefault();
	$this = $(this);
	rid = $this.data('rid');
	parent = $this.parent();
	undo = $this.siblings();
	$.ajax({
		  url:"script/ajax.php?state=baluser&rid="+rid,
		  dataType:"json",
		  success: function(e){
			  if(e == true){
				  $this.css("display","none");
				  undo.css("display","inline-block");
			  }
		  }
	 })
})
/*$(document).on("click",".balundo",function(e){
	e.preventDefault();
	$this = $(this);
	rid = $this.data('rid');
	parent = $this.parent();
	bal = $this.siblings();
	$.ajax({
		  url:"script/ajax.php?state=balundouser&rid="+rid,
		  dataType:"json",
		  success: function(e){
			  if(e == true){
				  $this.css("display","none");
				  bal.css("display","inline-block");
			  }
		  }
	 })
})*/
</script>
<script>
/*var start = 100 //where the rows start
var count = 100 //total number of rows

var ptbl = $(".patientTbl").DataTable()
interval = setInterval(function(){
	param = {
		start : start,
		count : count,
		generate : "patient"
	}
	$.post("script/ajax.php",param,function(data){
		if(data.length != 0){
			for(i=0; i < data.length ; i++){
				col = data[i];
				id = parseInt(col.id)
				ptbl.row.add([
					col.hospital_no,
					col.name,
					col.telephone,
					col.dob,
					col.sex,
					col.marital_status,
					col.date,
					"<div class='row'><div class='col-md-6' style='padding-right: 0px'><a href='patient_view.php?pid="+id+"' class='btn btn-primary btn-block'>View</a></div><div class='col-md-6' style='padding-left: 5px'><a href='billing.php?pid="+id+"' class='btn btn-primary btn-block'>Bill</a></div>"
				]).draw(false)
				
			}
		}
		
		start = start + 100
	},'json')
	
},1000)
*/

$(".patientTbl").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable.php"
} );
$("#bill_view").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable_bv.php"
} );

$("#diag-plus").on("click",function(e){
	e.preventDefault()
	$this = $(this);
	form = $("#diag-form");
	original = $("#diag-group");
	clone = $(original.clone());
	idx = parseInt($this.data('idx'));

	if( original.find("#diag-test").val() === "" || original.find("#diag-test").val() == null ){
		return false;
	}
    clone.find("#diag-test").val(original.find("#diag-test").val());
	
	clone.find("#diag-test").attr("name","test[]");
	clone.find("#diag-lab").attr("name","lab"+idx);
	clone.find("#diag-payment").attr("name","payment"+idx);
	clone.addClass("diag-list");
	
	original.find("#diag-test").val("");
	original.find("#diag-lab").prop("checked","checked");
	original.find("#diag-payment").prop("checked","checked");
	
	plus_i = clone.find("#diag-plus i")
	plus_i.removeClass("fa-plus");
	plus_i.addClass("fa-minus");
	plus_i.parent().addClass("billminus");
	plus_i.parent().attr("id","diag-minus")
	
	$this.data("idx", idx + 1);
	btngroup = form.find("button[type=submit]")
	btngroup = btngroup.parent().parent();
	clone.insertBefore(btngroup);
	
})


$("#diag-form").on("submit",function(e,data){
	
	form = $("#diag-form");
	falert = form.find("input[name=alert]");
	check = $(".diag-list input[type=checkbox]#diag-lab:checked");
	
	/*if($(".diag-list").length < 1){
		return false
	}*/
	if(check.length > 0){
		if(confirm("Are you sure you want alert when test result(s) is out?")){
			falert.val(1);
		}else{
			falert.val(0);
		}
	}else{
		falert.val(0);
	}
	
})
$("#diag-lab").on("change",function (e) {
    var diagTestCon = $("#diag-test-con");
    var diagTestSelect = diagTestCon.children("select")
    var diagTestInput = diagTestCon.children("input")
    var $this = $(this);
    var status = $this.is(":checked");
    if(!status){
        console.log(diagTestInput);
        diagTestSelect.css("display","none");
        diagTestSelect.removeAttr("id");
        diagTestInput.removeAttr("style");
        diagTestInput.attr("id","diag-test");
    }else{
        console.log(diagTestSelect);
        diagTestInput.css("display","none");
        diagTestInput.removeAttr("id");
        diagTestSelect.removeAttr("style");
        diagTestSelect.attr("id","diag-test");
    }
})

$("#vitals-plus").on("click",function(e){
	e.preventDefault()
	$this = $(this);
	form = $("#vitals-form");
	original = $("#vitals-group");
	clone = $(original.clone())
	
	
	if(clone.find("#vitals-vital").val() == "" || clone.find("#vitals-sign").val() == "" ){
		return false;
	};
	
	clone.find("#vitals-vital").attr("name","vital[]");
	clone.find("#vitals-sign").attr("name","sign[]");

	clone.addClass("vitals-list")
	
	plus_i = clone.find("#vitals-plus i")
	plus_i.removeClass("fa-plus");
	plus_i.addClass("fa-minus");
	plus_i.parent().addClass("billminus");
	plus_i.parent().attr("id","vitals-minus")
	
	original.find("#vitals-vital").val("");
	original.find("#vitals-sign").val("");

	btngroup = form.find("button[type=submit]")
	btngroup = btngroup.parent().parent();
	clone.insertBefore(btngroup);
	
	
})
$("#vitals-form").on("submit",function(e,data){
	
	form = $("#vitals-form");
	falert = form.find("input[name=alert]");
	
	if($(".vitals-list").length < 1){
		return false
	}
})
$approvalamt = $("#approval-form").find(".approval-amount");
$approvalamt.on("focusout",function(e){
	$this = $(this);
	billtotal = $("#bill-total");
	//initotal = parseFloat(billtotal.text());
	figure = 0.0
	allamt = $("#approval-form").find(".approval-amount");
	allamt.each(function(index, element) {
		val = 0;
		if( $(this).val() == ""){
			val = 0
		}else{
			val = parseFloat($(this).val())
		}
        figure = val + figure
    });
	billtotal.text(figure);
})
$("#assign-staff").on("change",function(e){
	$this = $(this)
	$val = $this.val();
	pid = $this.data('pid');
	param = {
		state : 'assignstaff',
		uid : $val,
		pid : pid
	}
	$.post("script/ajax.php",param,function(data){
		console.log(data)
	},'json')
})
$(document).on("change","#admit",function(e){
	e.preventDefault()
	$this = $(this);
	param = {
		state : 'admit',
		pid : $this.data('pid'),
		ward : $this.val()
	}
	console.log(param)
	$.post("script/ajax.php",param,function(data){
		//$this.find("option:selected").text("Admited to 
	}, 'json')
})
$("#bed").on("focusin",function(e){
	idx =  this.selectedIndex;
	$(this).data('idx',idx);
	
})
$(document).on("change","#bed",function(e){
	e.preventDefault()
	$this = $(this);
	if(confirm("Are you sure you want to admit(assign bed)  this patient?")){
	}else{	
		$(this).prop('selectedIndex', idx);
		return false
	}
	param = {
		state : 'bed',
		pid : $this.data('pid'),
		bed : $this.val()
	}
	$.post("script/ajax.php",param,function(data){ 
	}, 'json')
})
$(".doc-list").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable_doc.php"
} );
$(".outdoc-list").DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": "script/datatable_outdoc.php"
} );
$(".bill-list").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable_bill.php"
} );
$(".lab-list").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable_lab.php"
} );
$(".lab-hist").DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": "script/datatable_labhist.php"
} );
$(".lab-nonhist").DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": "script/datatable_labnonhist.php"
} );
$(".pharmacy-list").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable_pharmacy.php"
} );
$(".prescription-list").DataTable( {
    "processing": true,
    "serverSide": true,
    "ajax": "script/datatable_prescription_list.php"
} );


$("#treatment-plus").on("click",function(e){
	e.preventDefault()
	$this = $(this);
	form = $("#treatment-form");
	original = $("#treatment-group");
	clone = $(original.clone());
	
	
	if(clone.find("#treatment-drug").val() == "" || clone.find("#treatment-dosage").val() == "" || clone.find("#treatment-routine").val() == "" || clone.find("#treatment-route").val() == "" ){
		return false;
	};

	clone.find("#treatment-drug").val(original.find("#treatment-drug").val())

	clone.find("#treatment-drug").attr("name","drug[]");
	clone.find("#treatment-dosage").attr("name","dosage[]");
	clone.find("#treatment-routine").attr("name","routine[]");
	clone.find("#treatment-quantity").attr("name","quantity[]");
    clone.find("#treatment-remark").attr("name","tremark[]");
    clone.find("#treatment-route").attr("name","route[]");

	clone.addClass("treatment-list")
	
	plus_i = clone.find("#treatment-plus i")
	plus_i.removeClass("fa-plus");
	plus_i.addClass("fa-minus");
	plus_i.parent().addClass("billminus");
	plus_i.parent().attr("id","treatment-minus")
	
	original.find("#treatment-drug").val("");
	original.find("#treatment-dosage").val("");
	original.find("#treatment-routine").val("");
    original.find("#treatment-quantity").val("");
    original.find("#treatment-remark").val("");
    original.find("#treatment-route").val("");

	btngroup = form.find("button[type=submit]");
	btngroup = btngroup.parent().parent();
	clone.insertBefore(btngroup);
	
	
})
$("#treatment-form").on("submit",function(e,data){
	
	form = $("#treatment-form");
	falert = form.find("input[name=alert]");
	
	if($(".treatment-list").length < 1){
		return false
	}
})
$(".hmo-list").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": "script/datatable_hmo.php"
} );

</script>
<script>
$(".hmo-billrecord").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": {
		"url" : "script/datatable_hmobillrecord.php",
		"data" : {
			"hmo" : getUrlParameter('hmo')
		}
	}
});
</script>
<script>
$(".hmo-billing").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": {
		"url" : "script/datatable_hmobill.php",
		"data" : {
			"hmo" : getUrlParameter('hmo')
		}
	}
} );
</script>
<script>
$(".patients-hmo").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": {
		"url" : "script/datatable_phmo.php",
		"data" : {
			"hmo" : getUrlParameter('hmo')
		}
	}
});
</script>
<script>
$("#hmo-plus").on("click",function(e){
	e.preventDefault()
	$this = $(this);
	form = $("#hmo-form");
	original = $("#hmo-group");
	clone = $(original.clone())
	idx = parseInt($this.data('idx'));
	amount = 0;
	dis_amount = parseInt($("#hmo-total").text())
	service_val = original.find("#hmo-service ").val()
	service_text = original.find("#hmo-service option:selected").text()
	service_quantity = parseInt(original.find("#hmo-quantity").val())
	
	//console.log(clone.find("#diag-result-con").css("display"))
	if(original.find("#hmo-service option:selected ").is(":disabled") || clone.find("#hmo-amount").val() == "" 
		|| original.find("#hmo-quantity").val() == "" ){
		return false;
	};
	
	
	clone.find("#hmo-service").html("<option value='"+service_val+"'>"+service_text+"</option>") //setting the service value and text
	clone.find("#hmo-service").attr("name","service[]"); //setting the service name attribute
	
	clone.find("#hmo-amount").attr("name","cost[]");
	clone.find("#hmo-quantity").attr("name","quantity[]");

	amount = parseInt(clone.find("#hmo-amount").val()) * service_quantity
	
	clone.addClass("hmo-list")
	
	disabled = original.find("#hmo-service option:disabled")
	disabled.removeAttr("disabled");
	disabled.prop("selected","selected")
	disabled.attr("disabled","disabled");
	
	original.find("#hmo-amount").val("");
	original.find("#hmo-quantity").val("");
	
	plus_i = clone.find("#hmo-plus i")
	plus_i.removeClass("fa-plus");
	plus_i.addClass("fa-minus");
	plus_i.parent().addClass("billminus");
	plus_i.parent().attr("id","diag-minus")
	
	$this.data("idx", idx + 1);
	btngroup = form.find("button[type=submit]")
	btngroup = btngroup.parent().parent();
	$("#hmo-total").text(dis_amount + amount);
	clone.insertBefore(btngroup);
	
})
$("#hmo-form").on("submit",function(e){
	if($(".hmo-list").length < 1){
		return false
	}
	ok = confirm("Are you sure you want to continue with payment");
	if(ok == false){
		return false
	}
})
$(document).on("click",".hmo-pay",function(e){
	date = $(this).data('date')
	hmo = $(this).data('hmo')
	payment = $("#hmo-payment")
	payment.find("#hmo-payment-date").text(date)
	payment.find("input[name=hmo]").val(hmo)
	payment.find("input[name=date]").val(date)	
})
</script>
<script>

$(".group-list").DataTable( {
	"processing": true,
	"serverSide": true,
	"ajax": {
		"url" : "script/datatable_group.php",
		"data" : {
			"type" : getUrlParameter('type')
		}
	}
});

$(".preport-table").DataTable( {
	"processing": true,
	"bLengthChange": false,
	"bFilter": false,
	"ordering": false,
	"paging":   false,
	"info":     false
	
});
$(".ureport-table").DataTable( {
	"processing": true,
	"bLengthChange": false,
	"bFilter": false,
	"ordering": false,
	"paging":   false,
	"info":     false
});
$(".incomereport-table").DataTable( {
	"processing": true,
	"bLengthChange": false,
	"bFilter": false,
	"ordering": false,
	"paging":   false,
	"info":     false
});
$(".sreport-table").DataTable( {
	"processing": true,
	"bLengthChange": false,
	"bFilter": false,
	"ordering": false,
	"paging":   false,
	"info":     false
});
$("#vlab_plus").on("click",function(e){
    e.preventDefault();
    let $this = $(this);
    let form = $("#vlab_form");
    let original = $("#vlab_group");
    let clone = $(original.clone());
    let test_val = original.find("#vlab_test").val();
    let test_text = original.find("#vlab_test option:selected").text();

    if( test_val === null || original.find("#vlab_result").val() === ""  ){
        return false;
    };


    clone.find("#vlab_test").html("<option value='"+test_val+"'>"+test_text+"</option>") //setting the service value and text
    clone.find("#vlab_test").attr("name","test[]"); //setting the service name attribute
    clone.find("#vlab_result").attr("name","result[]");
    clone.addClass("vlab-list")

    disabled = original.find("#vlab_test option:disabled")
    disabled.removeAttr("disabled");
    disabled.prop("selected","selected");
    disabled.attr("disabled","disabled");

    original.find("#vlab_result").val("");

    plus_i = clone.find("#vlab_plus i");
    console.log(plus_i);
    plus_i.removeClass("fa-plus");
    plus_i.addClass("fa-minus");
    plus_i.parent().addClass("billminus");
    plus_i.parent().attr("id","vlab_minus");


    btngroup = form.find("button[type=submit]")
    btngroup = btngroup.parent().parent();
    clone.insertBefore(btngroup);

})
$(".addbillplus").on("click",function(e){
	e.preventDefault()
	billamount = $("#bill-amount")
	billservice = $("#bill-service")
	
	if(billamount.val() == ''){
		return false
	}
	if(billservice.val() == 'null'){
		return false
	}
	formgroup = $("<div class='form-group'></div>")
	col1 = $("<div class='col-md-1'>")
	col1btn = $("<button class='btn btn-block btn-primary billminus'><i class='fa fa-minus'></i></button>")
	/*col1btn.on("click",function(e){
		e.preventDefault()
		remove($(this))
	})*/
	col1 = col1.append(col1btn)
	formgroup.append(col1)
	
	amount = billamount.val();
	stext = $("#bill-service option:selected").text();
	sval = billservice.val();
	
	billtotal = $("#bill-total")
	billtotaltext = billtotal.text();

	if(billservice.val() == "1" || billservice.val() == "6"){
		sum = parseFloat(billtotaltext) - parseFloat(amount)
		amount =  '-'+billamount.val();
	}else{
		sum = parseFloat(billtotaltext) + parseFloat(amount)
	}
	billtotal.text(sum)

    other = '<div class="col-md-4"><select class="form-control" name="service[]"><option value="'+sval+'" >'+stext+'</option></select></div>';
	other += '<div class="col-md-2"><input class="form-control" type="text" placeholder="0.00" value="'+amount+'" dir="rtl" ';
	other += 'required="required" name="amount[]" onkeypress="return false"/></div></div>';
	formgroup.append($(other));
	
	form = $("#billing-list");
	btngroup = form.find("button[type=submit]")
	btngroup = btngroup.parent().parent();
	formgroup.insertBefore(btngroup);
	
	billamount.val(null)
	$('#bill-service option:eq(0)').prop("selected",true)
	
});
$(".add_falseedit").on("keydown",function(e){
	return false;
})

</script>

</body>

</html>  
