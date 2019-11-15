
//service title submit
$(".modal#modal-servicetitle form").on("submit",function(e){
	e.preventDefault()
	$this = $(this);
	err = $this.find("input[name=title]").siblings(".herror");
	formgroup = err.parent().parent();
	formdata = $this.serializeArray();
	url = $this.attr("action")
	$.post(url,formdata,function(e){
		if(e == 'exist'){
			err.css("display","inline-block");
			formgroup.addClass("has-error");
		}else if(e == true){
			location = location.origin+location.pathname+"?success=Service Title Added Successfully"
		}else{
			console.log(false);
		}
	},'json')
})
//service type form insert and check
$(".modal#modal-servicetype form").on("submit",function(e){
	e.preventDefault()
	$this = $(this);
	title = $this.find("select[name=title]");
	if(title.val() == "Null"){
		formgroup = title.parent().parent()
		formgroup.addClass("has-error")
		return false
	}
	err = $this.find("input[name=type]").siblings(".herror");
	formgroup = err.parent().parent();
	formdata = $this.serializeArray();
	url = $this.attr("action")
	$.post(url,formdata,function(e){
		if(e == 'exist'){
			err.css("display","inline-block");
			formgroup.addClass("has-error");
		}else if(e == true){
			location = location.origin+location.pathname+"?success=Service Type Added Successfully"
		}else{
			console.log(false);
		}
	},'json')
})

//new user form insert and check
$(".modal#modal-newuser form").on("submit",function(e){
	e.preventDefault()
	$this = $(this);
	right = $this.find("select[name=right]");
	if(right.val() == "null"){
		formgroup = right.parent().parent()
		formgroup.addClass("has-error")
		return false
	}
	pass = $(".modal#modal-newuser input[name=password]")
	rpass = $(".modal#modal-newuser input[name=rpassword]")

	if(pass.val() != '' && rpass.val() != ''){
		if(pass.val() != rpass.val()){
			rpass.siblings(".herror").css("display","inline-block");
			return false
		}
	}
	err = $this.find("input[name=username]").siblings(".herror");
	formgroup = err.parent().parent();
	formdata = $this.serializeArray();
	url = $this.attr("action")
	$.post(url,formdata,function(e){
		if(e == 'exist'){
			err.css("display","inline-block");
			formgroup.addClass("has-error");
		}else if(e == true){
			location = location.origin+location.pathname+"?success=New User Added Successfully"
		}else{
			console.log(e);
		}
	},'json')
})
$("#modal-edituser form").on("submit",function(e){
	e.preventDefault()
	$this = $(this);
	oldpass = $this.find("input[name=oldpass]");
	newpass = $this.find("input[name=newpass]");
	repass = $this.find("input[name=repass]");
	if(newpass.val() != repass.val()){
		err = repass.siblings(".herror")
		err.css("display","inline-block");
		err.parent().parent().addClass("has-error");
		return false;
	}
	url = $this.attr('action');
	formdata = $this.serializeArray();
	$.post(url,formdata,function(e){
		if(e == 'exist'){
			err = oldpass.siblings(".herror");
			err.css("display","inline-block");
			err.parent().parent().addClass("has-error");
		}else if(e == true){
			location = "login.php?logout=true"
		}else{
			console.log(e);
		}
	},'json')
})



$(document).on("click",".billminus",function(e){
	e.preventDefault()
	$this = $(this);
	formgroup = $this.parent().parent();
	amount = formgroup.find("input[type=text]");
	amount = amount.val();

	billtotal = $("#bill-total")
	billtotaltext = billtotal.text();
	sum = parseFloat(billtotaltext) - parseFloat(amount)
	billtotal.text(sum)
	
	formgroup.remove();
	
})
//add to one more bill service
$(".billplus").on("click",function(e){
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
$("#billing-list").on("submit",function(e){
	
	ok = confirm("Are you sure you want to continue with payment");
	if(ok == false){
		return false
	}
})
$(".btn.debt").on("click",function(e){
	ok = confirm("After debt payment you cannot edit this bill. Do you want to continue?");
	if(ok == false){
		return false
	}
})
$(document).on("click",".delete",function(e){
	$this = $(this);
	$tbl = $this.data("table");
	$tblid = $this.data("id");
	sibling = $this.siblings()
	$.ajax({
		url:"script/ajax.php?state=delete&tbl="+$tbl+"&tblid="+$tblid,
		dataType:"json",
		success: function(data){
			if(data == true){
				$this.css("display","none");
				sibling.css("display","inline-block");
			}
		},
		error: function(jqXHR, status, error){
			location = location.origin+location.pathname+"?failed=Service was NOT deleted successfully"
		}
	})
})
$(document).on("click",".delundo",function(e){
	$this = $(this);
	$tbl = $this.data("table");
	$tblid = $this.data("id");
	sibling = $this.siblings()
	$.ajax({
		url:"script/ajax.php?state=delundo&tbl="+$tbl+"&tblid="+$tblid,
		dataType:"json",
		success: function(data){
			if(data == true){
				$this.css("display","none");
				sibling.css("display","inline-block");
			}
		},
		error: function(jqXHR, status, error){
			location = location.origin+location.pathname+"?failed=Service was NOT deleted successfully"
		}
	})
})

