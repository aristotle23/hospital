// JavaScript Document
function getservtype($this,target){
	titleval = $this.val();
	if(titleval == 'null'){
		err = $this.parent().parent()
		err.addClass("has-error");
		target.empty()
		tag = $("<option></option>")
		tag.text('Please select...');
		tag.val('null')
		target.append(tag)
		return
	}else{
		err = $this.parent().parent()
		err.removeClass("has-error");
	}
	$.ajax({
		url:"script/ajax.php?state=getservtype&title="+titleval,
		dataType:"json",
		success: function(data){
			target.empty()
			tag = $("<option></option>")
			tag.text('Please select...');
			tag.val('null')
			target.append(tag)
			for(var i = 0; i < data.length ; i++){
				info = data[i]
				tag = $("<option></option>")
				tag.text(info['type']);
				tag.val(info['id'])
				target.append(tag)
				
			}
		}
	})
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
	if(charCode <= 57 && (charCode == 8 || charCode == 46 || charCode >= 48) ){
		return true
	}
	return false
	console.log(evt)
}
