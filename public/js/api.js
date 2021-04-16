/*
	Author: Gabriel Luis G. Borjal
	Projects included in: onetouch, icsprom
*/
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    type: "POST"

});
function addNode(parent,type,id,name,classTxt,value,inpType,textNode){
	//var parent = document.getElementById(parentId);
	var tmp = document.createElement(type);

	if(id != undefined){tmp.id = id;}
	if(name != undefined){tmp.name = name;}
	if(classTxt != undefined){tmp.className = classTxt;}
	if(value != undefined){tmp.value = value;}
	if(inpType != undefined){tmp.type = inpType;}
	if(textNode != undefined){
		var t = document.createTextNode(textNode);
		tmp.appendChild(t);
	}
	parent.appendChild(tmp);
	return tmp;
}
function ajaxSubmitPostings(formId,dataform){
	var error;
		$.ajax({
            url: $(formId).attr('action'),
            processData: false,
			contentType: false,
			mimeType: 'multipart/form-data',
            type:"POST",
            data: dataform,
            
            success:function(data){
            	var success = (JSON.parse(data).success) ? JSON.parse(data).success: undefined;
            	var status = (JSON.parse(data).status) ? JSON.parse(data).success: undefined;
            	var msg = JSON.parse(data).message;

            	if(success!=undefined && success == false){
                	var toastContent = "<span>"+msg+"</span>";
                    Materialize.toast(toastContent, 5000, 'red darken-4');
	            }else if(status!=undefined && status == false){
	            	for(errors of msg){
	                    var toastContent = "<span>" + errors + "</span>";
	                    Materialize.toast(toastContent, 1000, 'red darken-4');
	                }
	            }else{

	            	var toastContent = "<span>Success</span>";
                    Materialize.toast(toastContent, 5000, 'red darken-4');
	            }
				return true;
            },error:function(data){ 
                for(errors of JSON.parse(data).message){
	                var toastContent = "<span>" + errors + "</span>";
	                Materialize.toast(toastContent, 1000, 'red darken-4');
	            }
                return false;
            }
        });
    if(error == undefined){
    	return true;
    }
    return false;
}
function resetPostings(div,type){
	var div = $('#'+div);
	if(div.is(':parent') ){
		div.empty();
	}
	switch(type){
		case 1: discTextCntr = 1;
				discImageCntr = 0;
				discHighlightCntr = 0;
				$('#postDiscussion input')[3].value = '';
				addDesc('addl_post_disc','text','What do you want to say?',discTextCntr);
				break;
		case 3: notesTextCntr = 1;
				notesImageCntr = 0;
				notesHighlightCntr = 0;
				addDesc('addl_post_note','text','What do you want to say?',notesTextCntr);
				$('#postNote input')[3].value = '';
				break;
		case 4: commTextCntr = 1;
				commImageCntr = 0;
				commHighlightCntr = 0;
				addDesc('addl_post_comment','text','What do you want to say?',commTextCntr);
				break;
	}
}
// For todays date;
Date.prototype.today = function () { 
    return ((this.getDate() < 10)?"0":"") + this.getDate() +"/"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"/"+ this.getFullYear();
}

// For the time now
Date.prototype.timeNow = function () {
     return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
}
//source: http://stackoverflow.com/questions/10211145/getting-current-date-and-time-in-javascript
function getLoading(div){
	
	var div = $('#'+div);
	var loading = "<div class='col s1 m1 l1 offset-s5 offset-m5 offset-l5 center'>"+
					"<div class='preloader-wrapper active'>"+
						"<div class='spinner-layer spinner-red-only'>"+
							"<div class='circle-clipper left'>"+
								"<div class='circle'></div>"+
							"</div>"+
							"<div class='gap-patch'>"+
								"<div class='circle'></div>"+
							"</div>"+
							"<div class='circle-clipper right'>"+
								"<div class='circle'></div>"
							"</div>"+
						"</div>"+
					"</div>"+
				"</div>";
	if(div.is(':parent') ){
		div.empty();
	}
	div.append(loading);
}
function imageCard(image,text){
	var card = "<div class='row'>"+
					"<div class='col s12 m8 offset-m2'>"+
						"<div class='card small'>"+
							"<div class='card-image'>"+
								"<img class='materialboxed' width='650' src='http://" + siteUrl + "/discussion/image/"+image+"' alt='notavailable'/>"+
							"</div>";
	if(text!=undefined){
		card+=				"<div class='card-content'>"+
								"<p class='flow-text'>"+text+"</p>"+
							"</div>";
	}
		card+=			"</div>"+
					"</div>"+
				"</div>";
	return card;
}
function textCard(text){
	var card = "<div class='row'>"+
					"<div class='col s12 m10 offset-m2'>"+
						"<blockquote class='flow-text'>"+text+"</blockquote>"+
					"</div>"+
				"</div>";
	return card;
}

function imageCardV2(div,image,text){
	
	var row = 	addNode(div,'div',undefined,undefined,'row');
	var col = 	addNode(row,'div',undefined,undefined,'col s12 m8 offset-m2');
	var card = 	addNode(col,'div',undefined,undefined,'card small');
	var cImg = 	addNode(card,'div',undefined,undefined,'card-image');
	var img = 	addNode(cImg,'img',undefined,undefined,'materialboxed');
		img.width = 650;
		img.src = "http://" + siteUrl + "/discussion/image/"+image;
		img.alt = 'notavailable';
	if(text!=undefined){
		var cText = addNode(card,'div',undefined,undefined,'card-content');
		addNode(cText,'p',undefined,undefined,'flow-text',undefined,undefined,text);
	}
}
function textCardV2(div,text){
	var row = 	addNode(div,'div',undefined,undefined,'row');
	var col = 	addNode(row,'div',undefined,undefined,'col s12 m8 offset-m2');
	addNode(col,'blockquote',undefined,undefined,'flow-text',undefined,undefined,text);
}
// input: array, int
function interpText(temp,pos){ 
	var ret = {pos:'',text:''}
	var j;
	var text="";
	for(j=pos;j<temp.length;j++){
		if(temp[j].startsWith('<[!text',0)||temp[j].startsWith('<[!caption',0)){
			var tt = temp[j].split('::');
			if(!tt[1].endsWith('!]>')){
				text+= tt[1] + " ";
			}else{
				var tt = tt[1].split("!]>");
				text+= tt[0] + " ";
				break;
			}
		}else if(!temp[j].endsWith('!]>')){
			text+= temp[j] + " ";
		}else{
			var tt = temp[j].split("!]>");
			text+= tt[0] + " ";
			break;
		}
	}
	ret.pos = j;
	ret.text = text;
	return ret;
}
function filterQuickText(text){
	var temp = text.split(" ");
	var newText = "";
	for(var i = 0; i<temp.length;i++){
		if(temp[i]=== "<[!img!]>" ){
			temp[i] = "--image--";
			newText+=temp[i]+ " ";
		}else if(temp[i].startsWith('<[!text',0) || temp[i].startsWith('<[!caption',0)){
			var t = "";
			var j;
			for(j=i;j<temp.length;j++){
				if(temp[j].startsWith('text',3) || temp[j].startsWith('caption',3)){
					var tt = temp[i].split('::');
					
					if(!tt[1].endsWith('!]>')){
						t+= tt[1] + " ";
					}else{
						var tt = tt[1].split("!]>");
						t+= tt[0] + " ";
					}
				}else if(!temp[j].endsWith('!]>')){
					t+= temp[j] + " ";
				}else{
					var tt = temp[j].split('!]>');
					t+= tt[0] + " ";
					break;
				}
			}
			i=j;
			newText+=t;
		
		}else{
			newText+=temp[i]+ " ";
		}
	}
	return newText;
}
function filterGenText(text,image){

	if(text != null){
		var temp = text.split(/\s+/);
		var images = (image != null) ? image.split(","):false;
		var newText = "";
		var k = 0;
		for(var i = 0; i<temp.length;i++){
			if(temp[i]=== "<[!img!]>" ){
				if(images != false){
					var t = "";
					if(temp[++i].startsWith('<[!caption',0) ){
						var ret = interpText(temp,i);
						var text= ret.text;						
						i = ret.pos;
						t = imageCard(images[k],text);
					}else{
						t= imageCard(images[k]);
					}
					k++;
					newText+=t;
				}else{
					newText+="<div class='card'> Image Unavailable </div>";
				}
			}else if(temp[i].startsWith('<[!text',0)){
				var ret = interpText(temp,i);
				var text= ret.text;						
				i = ret.pos;
				newText+=textCard(text);
				
			}else{
				newText+=temp[i]+ " ";
			}
		}
		return newText;
	}
	return null;
}
//filterGenTextV2(artcle.text,artcle.images);
function filterGenTextV2(text,image,div){//v2 of filterGenText

	if(text != null){
		var temp = text.split(/\s+/);
		var images = (image != null) ? image.split(","):false;
		
		var k = 0;
		var normTxt = "";
		for(var i = 0; i<temp.length;i++){

			if(temp[i]=== "<[!img!]>" ){
				addNode(div,'p',undefined,undefined,undefined,undefined,undefined,normTxt);
				normTxt = "";
				if(images != false){
					//var t = "";
					if(temp[++i].startsWith('<[!caption',0) ){
						var ret = interpText(temp,i);
						var text = ret.text;

						i = ret.pos;
						imageCardV2(div,images[k],text);
					}else{
						imageCardV2(div,images[k]);
					}
					k++;
					//newText+=t;
				}else{
					addNode(div,undefined,undefined,'card');
					//newText+="<div class='card'> Image Unavailable </div>";
				}
			}else if(temp[i].startsWith('<[!text',0)){
				addNode(div,'p',undefined,undefined,undefined,undefined,undefined,normTxt);
				normTxt = "";

				var ret = interpText(temp,i);
				var text= ret.text;						
				i = ret.pos;
				textCardV2(div,text);
				
			}else{
				normTxt+= temp[i] + " ";
				//addNode(div,undefined,undefined,undefined,undefined,undefined,undefined,temp[i]+" ");
				
			}
		}
		if(normTxt!="")
			addNode(div,'p',undefined,undefined,undefined,undefined,undefined,normTxt);
		//return newText;
	}
	//return null;
}
function updateGenContent(artcle){
	//details
	var details = $('#div_details','#genContent');
	if(artcle.updated_at){
		var year = Number(artcle.updated_at.date.substr(0,4));
		var day  = Number(artcle.updated_at.date.substr(9,1));
		var month= Number(artcle.updated_at.date.substr(6,1)) - 1;
		var hour = Number(artcle.updated_at.date.substr(11,2));
		var min  = Number(artcle.updated_at.date.substr(14,2));

		var date = new Date(year,month,day,hour,min);
	}else{
		var date = new Date();
	}
	var dtls = '<h4>'+ artcle.disc_title +'</h4><h6 class="grey-text text-darken-1">'+' '+ date.toUTCString() +'</h6>';
		dtls+= '<h6 class="grey-text text-darken-1">Priority:'+ artcle.disc_priority +'</h6>';
		if(artcle.project_name.length >25){
			dtls+= '<div class="tags"><div class="chip">'+ artcle.project_name.substr(0,25) +'...</div><div class="chip"><img src="http://' + siteUrl + '/profile/image/get/t/'+ artcle.profile +'" alt="Contact Person">'+ artcle.first_name +' '+ artcle.last_name +'</div></div>';
		}else{
			dtls+= '<div class="tags"><div class="chip">'+ artcle.project_name +'</div><div class="chip"><img src="http://' + siteUrl + '/profile/image/get/t/'+ artcle.profile +'" alt="Contact Person">'+ artcle.first_name +' '+ artcle.last_name +'</div></div>';
		}
	if(details.is(':parent') ){
		details.empty();
	}
	details.append(dtls);

	//contents
	var contents = $('#div_contents','#genContent');
	var conts = filterGenText(artcle.disc_text,artcle.disc_image);
	if(contents.is(':parent') ){
		contents.empty();
	}
	contents.append(conts);
	
	general.details = dtls;
	general.content = conts;
	
	
    $('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});
	
}

function colateText(arr){	
	var formData = new FormData();
	
	var arr = $('#'+ arr +' :input');
	var text = "";
	for(var i=0;i<arr.length;i++){
		if((arr[i].value != "")){
			if(arr[i].tagName === "TEXTAREA"){
				//text+=arr[i].value;
				var tmp = arr[i].value.split('\n');
				
				for(var j=0;j<tmp.length;j++){
					text+= tmp[j] + "<br/>";
				}
			}else if(arr[i].tagName === "INPUT"){
				if(arr[i].type == "file"){ //expect image files
					text+= " <[!img!]> ";
					formData.append('image[]',arr[i].files[0]);
				}else if(arr[i].type == "text"){
					if(arr[i].name == "img_desc_text[]" && (arr[i-1].value != "")){
						if(arr[i].value != ""){
							text+= " <[!caption::"+arr[i].value+"!]> ";
						}
					}else if(arr[i].name == "hl_text[]"){
						text+= " <[!text::"+arr[i].value+"!]> ";
					}
				}
			}
		}
	}
	formData.append('text',text);
	
	return formData;
	
}
function addImage(div_Id,input_Id,desc_Id,cntr){
	var cur = document.getElementById(div_Id);

	var div_file = addNode(cur,'div',undefined,undefined,'file-field input-field');
	var div_btn =  addNode(div_file,'div',undefined,undefined,'btn');
	addNode(div_btn,'span',undefined,undefined,undefined,undefined,undefined,'Image');
	var inp_file = addNode(div_btn,'input',input_Id+'['+cntr+']',input_Id+'[]',undefined,undefined,'file');

	var wrapper = addNode(div_file,'div',undefined,undefined,'file-path-wrapper');
	var inp_file = addNode(wrapper,'input',input_Id+'_path['+cntr+']',input_Id+'_path[0]','file-path validate',undefined,'text');

	var desc_text = addNode(cur,'input',desc_Id+'['+cntr+']',desc_Id+'[]',undefined,undefined,'text');
	desc_text.placeholder = 'Add image description.';
	
	discImageCntr = ++cntr;
}
function addHighlight(div_Id,input_id,cntr){
	var cur = document.getElementById(div_Id);
	addNode(cur,'input',input_id+'['+cntr+']',input_id+'[]',undefined,undefined,'text','Highlight text');
	var label = addNode(cur,'label',undefined,undefined,undefined,undefined,undefined,'Highlight text');
	label.htmlFor = input_id+'['+cntr+']';
}
function addDesc(div_Id,input_id,placeholder,cntr){
	var cur = document.getElementById(div_Id);
	var inp_text = addNode(cur,'textarea',input_id+'['+cntr+']',input_id+'[]','materialize-textarea');
	inp_text.placeholder = placeholder;
	var label = addNode(cur,'label',undefined,undefined,undefined,undefined,undefined,'What do you want to say?');
	label.htmlFor = input_id+'['+cntr+']';
}