
function updateNoteInfoDetail(artcle,details,genContent){
//details
  //var details = details;
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
            //(parent,type,id,name,classTxt,value,inpType,textNode)
  addNode(details,"h4",undefined,undefined,undefined,undefined,undefined,artcle.title); 
  addNode(details,"h6",undefined,undefined,"grey-text text-darken-1",undefined,undefined,date.toUTCString()); 
            //'<h4>'+ artcle.title +'</h4><h6 class="grey-text text-darken-1">'+' '+ date.toUTCString() +'</h6>';

  //details.append(dtls);

  //contents
  var conts = filterGenTextV2(artcle.text,artcle.images,genContent);

  //contents.append(conts);
  
  
	$('.materialboxed').materialbox();
	$('.tooltipped').tooltip({delay: 50});
  
}

function submitNote(){
	var formId = '#postNote';
	var dataform =  new FormData();

	dataform = colateText('addl_post_note');
	dataform.append('_token',$(formId+' [name=_token]')[0].value);
	dataform.append('task_exer_data_id',$(formId+' [name=task_exer_data_id]')[0].value);
	dataform.append('title',$(formId+' [name=title]')[0].value);

	if(ajaxSubmitPostings(formId,dataform)){
		resetPostings('addl_post_note',3);
		//reset postings
	}
}
$('a').on('click', function() {
	if($(this).attr('href') != undefined){
		if($(this).attr('href') ==='#top' ) {
	   		$('html,body').animate({ scrollTop: 0 }, 'slow');
		
		}
	}
});
function upImage(){
	addImage('addl_post_note','file_img','img_desc_text',notesImageCntr);
	$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
}
function upHighlight(){
	addHighlight('addl_post_note','hl_text',notesHighlightCntr);
	$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+100}, 'slow');
}
function upDesc(){
	addDesc('addl_post_note','text','Continue...',notesTextCntr);
	$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
}