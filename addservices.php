<?php
require_once "script/ini.php";

require_once "template/header.php";
require_once "template/sidebar.php";


?>
<style>
#choice{
	display: none;
}
</style>
        <div id="page-wrapper" >
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Add Services</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
              <div class="col-lg-12">
              	<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Standalone services</b> are services that are not under any service title or type e.g. BED 
                 </div>
                 <!--<div class="alert alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <b>Categorize services</b> are services that are under any service title or type e.g. LAB->TEST->Urinary Test , LAB->TEST->Blood Test 
                 </div>-->
              </div>
            </div>
            <form role="form" class="form-horizontal"  id="page-addservice" action="script/ajax.php" method="post">
            <input type="hidden" name="page" value="addservices"  />
                <div class="row ">
                    <div class="col-lg-12 ">
                    	<div class="form-group">
                            <label class="col-md-2 control-label" >SERVICE GROUP</label>
                            <div class="col-md-6">
                                <div class="check-box ">
                                    <label  class="control-label" >
                                    <input type="radio" name="group" class="service-group" value="standalone" required="required" checked="checked" /> 
                                    	Standalone Service
                                    </label>
                                    &nbsp;&nbsp;
                                    <label >
                                    <!--<input type="radio"  name="group" class="service-group" value="categorized" required="required" /> 
                                    	Categorized Service
                                    </label>-->
                                </div>
                            </div>
                        </div>
                        <div id="choice">
                            <div class="form-group">
                            <label class="col-md-2 control-label">Service Title</label>
                            <div class="col-md-6">
                                <select class="form-control" name="title">
                                  <option value="null">Please Select ...</option>
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
                            <label class="col-md-2 control-label">Service Type</label>
                            <div class="col-md-6">
                                <select class="form-control" name="type">
                                <option value="null">Please Select ...</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        <div class="form-group">
                        <label class="col-md-2 control-label">Service</label>
                        <div class="col-md-6">
                        	<input type="text" class="form-control" name="service" required="required" />
                            <span class="help-block herror">Input Already Exists</span>
                        </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Access Right</label>
                            <div class="col-md-6">
                                <select class="form-control" name="access">
                                  <option value="null">Please Select ...</option>
                                  <?php
                                   $titles = $db->getAll("select id, access from access_right");
                                    foreach ($titles as $title){
                                        print '<option value="'.$title['id'].'" >'.$title['access'].'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                            </div>
                        <div class="form-group">
                        <div class="col-md-offset-2 col-md-2">
                        <button type="submit" class=" btn btn-primary btn-block primary-save" value="expenditure" name="page" ><b>Save</b></button>
                        </div>
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

            </form>
            <!-- /.row -->
        </div>

<script type="text/javascript">
$("input.service-group").on("click",function(e){
	$this = $(this);
	value = $this.val()
	if(value == 'standalone'){
		$("#choice").css("display","none");
	}else{
		$("#choice").css("display","block");
	}
})

$("form#page-addservice select[name=title]").on("change",function(e){
	type = $("form#page-addservice select[name=type]");
	$this = $(this);
	getservtype($this,type)
})
$("form#page-addservice").on("submit",function(e){
	e.preventDefault()
	$this = $(this);
	access = $this.find("select[name=access]")
	title = $this.find("select[name=title]")
	type = $this.find("select[name=type]")
	service = $this.find("input[name=service]")
	radio = $("input.service-group").filter(function(index) {
        return $(this).prop("checked") == true
    });
	if(radio.val() != 'standalone'){
		if(title.val() == 'null'){ 
			title.parent().parent().addClass('has-error')
			return false;
		}
		if( type.val() == 'null'){
			type.parent().parent().addClass('has-error')
			return false;
		}else{
			type.parent().parent().removeClass('has-error')
		}
	}
	if(access.val() == 'null'){
		access.parent().parent().addClass('has-error')
		return false;
	}else{
		access.parent().parent().removeClass('has-error')
	}
	err = $this.find("input[name=service]").siblings(".herror");
	formdata = $this.serializeArray();
	url = $this.attr("action")
	$.ajax({
		url: "script/ajax.php?form=addservices&type="+type.val()+"&title="+title.val()+"&service="+service.val()+"&access="+access.val(),
		dataType:"json",
		success: function(e){
			if(e == 'exist'){
				err.css("display","inline-block");
				err.parent().parent().addClass("has-error");
			}else if(e == true){
				
				location = location.origin+location.pathname+"?success=Service Title Added Successfully"
			}else{
				console.log(e);
			}
		},
		error: function(xhr, status, error){
			console.log('error');
		}
	})
		
	/*$.post(url,formdata,function(e){
		if(e == 'exist'){
			err.css("display","inline-block");
			err.parent().parent().addClass("has-error");
		}else if(e == true){
			
			location = location.origin+location.pathname+"?success=Service Title Added Successfully"
		}else{
			console.log(e);
		}
	},'json')*/
})
</script>
    
<?php
require_once "template/footer.php";
?>