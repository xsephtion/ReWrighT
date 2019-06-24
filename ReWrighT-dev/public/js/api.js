/*
	Author: Gabriel Luis G. Borjal
	Projects included in: onetouch, icsprom
*/
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
				return true;
            },error:function(data){ 
                error = data.status;
                return false;
            }
        });
    if(error == undefined){
    	return true;
    }
    return false;
}