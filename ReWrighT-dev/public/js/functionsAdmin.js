$(document).ready(function(){
	$('select').material_select();
    $('.scrollspy').scrollSpy();
    $('.materialboxed').materialbox();
    //side navbar
	$('.button-collapse').sideNav({
		menuWidth: 300, // Default is 240
		edge: 'left', // Choose the horizontal origin
		closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
		}
	);
	// the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
    $('.modal-trigger').leanModal({
	    ready: function () {
	        $('ul.tabs').tabs();
	    }
	});
});
$('#userManage').click(function() {

	if(document.getElementById("userManagement").style.display == "none") {
		console.log("here");
		document.getElementById("userManagement").style.display = "block";
		document.getElementById("taskManagement").style.display = "none";
	}
});
$('#taskManage').click(function() {

	if(document.getElementById("taskManagement").style.display == "none") {
		document.getElementById("taskManagement").style.display = "block";
		document.getElementById("userManagement").style.display = "none";
	}
});

function submitRegForm(){
	var formId = '#f_reg';
	var dataform =  new FormData();

	dataform.append('_token',$(formId + ' [name=_token]')[0].value);
	dataform.append('email',$(formId+' [name=email]')[0].value);
	dataform.append('user_types',$(formId+' [name=user_types]')[0].value);
	
	var error;
	$.ajax({
        url: $(formId).attr('action'),
        processData: false,
		contentType: false,
		mimeType: 'multipart/form-data',
        type:"POST",
        data: dataform,
        
        success:function(data){
        	console.log(data) ;
        	var status = JSON.parse(data).status;
        	var msg = JSON.parse(data).message;
            var cur = document.getElementById('code');
        	if(status == "validatorFail"){
        		
            	for(var message in msg){
            		
            		var toastContent = "<span>" + msg[message] + "</span>";
					Materialize.toast(toastContent, 5000, 'red darken-4');
            	}
            }else if(status == "fail"){
                cur.innerHTML = " ";
            	var toastContent = "<span>" + msg + "</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
            }else if(status == "success"){
            	var toastContent = "<span>" + msg + "</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
				
                cur.innerHTML = " ";
				addNode(cur,'h1',undefined,undefined,undefined,undefined,undefined,msg);
			}
         },error:function(data){ 
            error = data.status;
        }
    });
    if(error == undefined){
    	return true;
    }
    return false;
}

function submitActivationForm(){
	var formId = '#f_activation';
	var dataform =  new FormData();

	dataform.append('_token',$(formId + ' [name=_token]')[0].value);
	dataform.append('email',$(formId+' [name=email]')[0].value);
	
	var error;
	$.ajax({
        url: $(formId).attr('action'),
        processData: false,
		contentType: false,
		mimeType: 'multipart/form-data',
        type:"POST",
        data: dataform,
        
        success:function(data){
        	var status = JSON.parse(data).status;
        	var msg = JSON.parse(data).message;
        	var cur = document.getElementById('codeActivation');
            if(status == "validatorFail"){
        		
            	for(var message in msg){            		
            		var toastContent = "<span>" + msg[message] + "</span>";
					Materialize.toast(toastContent, 5000, 'red darken-4');
            	}
            }else if(status == "fail"){
                cur.innerHTML = " ";
            	var toastContent = "<span>" + msg + "</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
            }else if(status == "success"){
            	var toastContent = "<span>" + msg + "</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
				cur.innerHTML = " ";
				addNode(cur,'h1',undefined,undefined,undefined,undefined,undefined,msg);
				
            }
            	
        	
        },error:function(data){ 
            error = data.status;
        }
    });
    if(error == undefined){
    	return true;
    }
    return false;
}
