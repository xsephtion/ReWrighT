function submitRegForm(){
	if(chk_pword()){
		document.forms['f_reg'].submit();
		return true;
	}
	return false;
}
function changeRegCard(type){
	var f = $('#f'); 
	if(type == 0){
		f.removeClass('large');
		f.addClass('medium');
	}else{
		f.removeClass('medium');
		f.addClass('large');
	}

}
function chk_pword()
{
	var p1 = document.getElementById('r_password1');
	var p2 = document.getElementById('r_password2');

	if(!(p1.value === p2.value))
	{
		document.getElementById('label_pword').innerHTML = '<h6><b>Passwords MUST match!</b></h6>';
		document.getElementById('label_pword').style.color = 'green';
		document.getElementById('sub').disabled=true;
		return false;
	}else
	{
		document.getElementById('label_pword').innerHTML = 'Passwords match!';
		document.getElementById('label_pword').style.color = '#B71C1C';
		document.getElementById('password').value = document.getElementById('r_password2').value;
		document.getElementById('sub').disabled=false;
		return true;
	}
}
$(document).ready(function(){
	$('select').material_select();

});