document.getElementById("database").addEventListener("scroll",function(){
	var translate = "translate(0,"+this.scrollTop+"px)";
	this.querySelector("thead").style.transform = translate;
});

$(document).ready(function() {
	$('tr').click(function(event) {
		if (event.target.type !== 'checkbox') {
			$(':checkbox', this).trigger('click');
		}
	});
	$("input[type='checkbox']").change(function (e) {
		if ($(this).is(":checked")) {
			$(this).closest('tr').addClass("highlight_row");
		} else {
			$(this).closest('tr').removeClass("highlight_row");
		}
	});
});

function validateForm(){
	var user = document.forms["reg"]["username"].value;
	var passwd = document.forms["reg"]["password"].value;
	var passwdcon = document.forms["reg"]["passwordcon"].value;
	
	if ((user == null || user == "") && (passwd == null || passwd =="") && (con == null || con == "")){
		alert("Please fill out all fields.");
		return false;
	}
	
	if (user == null || user == ""){
		alert("Please fill out username (only letters).");
		return false;
	}
	
	if (passwd == null || passwd == ""){
		alert("Please fill out password.");
		return false;
	}
	
	if (passwdcon == null || passwdcon == ""){
		alert("Please confirm password.");
		return false;
	}
}