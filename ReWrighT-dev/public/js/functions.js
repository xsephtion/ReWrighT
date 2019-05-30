var siteUrl = "reWright.test";
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
	var loading = "<div class='col s1 m1 l1 offset-s5 offset-m5 offset-l5'>"+
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
								"<img class='materialboxed' width='650' src='http://rewright.test/discussion/image/"+image+"' alt='notavailable'/>"+
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
function filterGenComments(comments){
	var li = "<ul class='collection'>";
	for(var i=0;i < comments.length;i++){
		var upvote = (comments[i].upvote!=null) ? comments[i].upvote : 0 ;
		var downvote = (comments[i].downvote!=null) ? comments[i].downvote : 0 ;

		var year = Number(comments[i].updated_at.date.substr(0,4));
		var day  = Number(comments[i].updated_at.date.substr(9,1));
		var month= Number(comments[i].updated_at.date.substr(6,1)) - 1;
		var hour = Number(comments[i].updated_at.date.substr(11,2));
		var min  = Number(comments[i].updated_at.date.substr(14,2));

		var date = new Date(year,month,day,hour,min); 

		li += "<li class='collection-item avatar'>"+
				 "<img src='http://"+ siteUrl +"/profile/image/t/"+comments[i].user_id+"' alt='notavailable' class='profiles circle' />"+
				 "<span class='card-title'><b>"+comments[i].first_name+" "+comments[i].last_name+"</b><br/><p class='grey-text text-darken-1' style='font-size:12px;'>"+date.toUTCString()+"</p></span>"+
				 "<div class='votes'><a id='upc"+comments[i].comment_id+"' class='btn tooltipped waves-effect waves-light-blue-accent-4 waves-ripple  light-blue darken-4' data-position='top' data-delay='50' data-tooltip='UPVOTE' href='#!' onclick='vote("+comments[i].comment_id+",1);'><span >"+upvote+"</span></a><a id='downc"+comments[i].comment_id+"' class='btn tooltipped waves-effect waves-light-blue-accent-4  btn-flat' data-position='top' data-delay='50' data-tooltip='DOWNVOTE' href='#!' onclick='vote("+comments[i].comment_id+",2);'>"+downvote+"</a></div>"+
			 	"<p class='flow-text'>"+filterGenText(comments[i].text,comments[i].image)+"</p>"+
			 "</li>";
	}
		li+= "</ul>";
	return li;
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

function updateModal2(currBoard){
	var add1 = $('.modal-content ul','#modal2');
	var add2 = $('.modal-content ul li','#modal2');
	//add2.remove();

	if(currBoard === "db_disc_board"){
		for(var i=0;i < discussions.length;i++){
			if(discussions.length== add2.length) break;
			var li ="";
			var font = "";
			console.log(discussions[i]);
			switch(discussions[i].priority){
				case 1: li = "<li class='collection-item avatar'>";
						break;
				case 4: li = "<li class='collection-item avatar red darken-1'>";
						font = " white-text ";
						break;
				case 5: li = "<li class='collection-item avatar red darken-3'>";
						font = " white-text ";
						break;
				default: li = "<li class='collection-item avatar'>";
						break;
			}

			li 	+=	 "<img src='http://" + siteUrl + "/profile/image/t/"+discussions[i].profile+"' alt='notavailable' class='profiles circle' />"+
					 "<span class='title"+font+"'>"+discussions[i].first_name+" "+discussions[i].last_name+"</span>"+
					 "<p class='"+font+"'>"+discussions[i].disc_title+"...<br/>";
			if(discussions[i].disc_text!=false){
				var text = filterQuickText(discussions[i].disc_text);
				if(text.length < 60)
					li+=text+"</p>";    					
				else
					li+=text+"...</p>";
			}else{
				li+="</p>";
			}
			if(font != ""){
				font = " teal-text text-lighten-3";
			}
			li+="<a href='#top' class='secondary-content"+font+"' type='button' onclick='launchGenContent("+ discussions[i].disc_id +");'><i class='material-icons'>launch</i></a></li>";
			if(add2.length == 0){
				add1.append(li);
			}else{
				add2.first().before(li);
			}
		}
	}
}
function launchGenContent(disc_id){
	displayed_id = disc_id;
	getLoading('div_details');
	if($('#div_details').is(':parent') ){
		$('#div_contents').empty();
		//$('#div_comments').empty();
	}
	
	var data = {
		_token:$('#'+board).data('token'),
        disc_id: disc_id,
    }

	$.ajax({
        url: 'discussion',
        type:"POST",
        data: data,
        success:function(data){
			var artcle = {profile:'',first_name:'',last_name:'',disc_id:'',disc_title:'',disc_text:'',disc_image:'',disc_priority:'',project_name:'',updated_at:'',read:'',seen:''};
				artcle.profile 		= data.article.profile;
	    		artcle.first_name 	= data.article.first_name;
	    		artcle.last_name 	= data.article.last_name;
	    		artcle.disc_id 		= data.article.disc_id;
	    		artcle.disc_title 	= data.article.disc_title;
	    		artcle.disc_text 	= data.article.disc_text;
	    		artcle.disc_image 	= data.article.disc_image;
	    		artcle.disc_priority= data.article.disc_priority;
	    		artcle.project_name = data.article.project,
	    		artcle.updated_at   = data.article.updated_at;
	    		artcle.disc_read 	= data.article.disc_read;
	    		artcle.disc_seen 	= data.article.disc_seen;
	    		//artcle.comments 	= data.comments;
	    	updateGenContent(artcle);
			genContentComments(disc_id);
			return true;
        },error:function(){ 
            alert("An Error!!!!");
            return false;
        }
    });
}
function genContentComments(disc_id){
	getLoading('lsComments');
	var data = {
		_token:$('#'+board).data('token'),
        disc_id: disc_id,
    }

	$.ajax({
        url: '/discussion/comments',
        type:"POST",
        data: data,
        success:function(data){
        		var comments = [];

				for(var i=0;i< data.comments.length;i++){
					var comment = {comment_id:'',user_id:'',profile:'',first_name:'',last_name:'',text:'',image:'',upvote:'',downvote:'',created_at:'',updated_at:''};
						comment.comment_id =  data.comments[i].id;
						comment.user_id = data.comments[i].user_id;
						comment.profile = data.comments[i].profile;
			    		comment.first_name = data.comments[i].first_name;
			    		comment.last_name = data.comments[i].last_name;
			    		comment.text = data.comments[i].text;
			    		comment.image = data.comments[i].image;
			    		comment.upvote = data.comments[i].upvote;
			    		comment.downvote = data.comments[i].downvote;
			    		comment.created_at = data.comments[i].created_at;
			    		comment.updated_at = data.comments[i].updated_at;

			    	if(comments.length == 0){
			    		comments.push(comment);
			    	}else{
			    		comments.unshift(comment); //add items at the beginning of array
			    	}
				}
				updateGenComments(comments);
				return true;
        },
        error:function(){ 
            alert("An Error!!!!");
            return false;
        }
    });
}
function updateGenContent(artcle){
	//details
	var details = $('#div_details','#genContent');

	var year = Number(artcle.updated_at.date.substr(0,4));
	var day  = Number(artcle.updated_at.date.substr(9,1));
	var month= Number(artcle.updated_at.date.substr(6,1)) - 1;
	var hour = Number(artcle.updated_at.date.substr(11,2));
	var min  = Number(artcle.updated_at.date.substr(14,2));

	var date = new Date(year,month,day,hour,min);
	var dtls = '<h4>'+ artcle.disc_title +'</h4><h6 class="grey-text text-darken-1">'+' '+ date.toUTCString() +'</h6>';
		dtls+= '<h6 class="grey-text text-darken-1">Priority:'+ artcle.disc_priority +'</h6>';
		if(artcle.project_name.length >25){
			dtls+= '<div class="tags"><div class="chip">'+ artcle.project_name.substr(0,25) +'...</div><div class="chip"><img src="http://' + siteUrl + '/profile/image/t/'+ artcle.profile +'" alt="Contact Person">'+ artcle.first_name +' '+ artcle.last_name +'</div></div>';
		}else{
			dtls+= '<div class="tags"><div class="chip">'+ artcle.project_name +'</div><div class="chip"><img src="http://' + siteUrl + '/profile/image/t/'+ artcle.profile +'" alt="Contact Person">'+ artcle.first_name +' '+ artcle.last_name +'</div></div>';
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
function updateGenComments(comments){
	var div_comments = $('#lsComments','#genContent');
	var com = filterGenComments(comments);
	if(div_comments.is(':parent') ){
		div_comments.empty();
	}
	div_comments.append(com);

	$('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});
}

$('#project','#nav-desktop li').on('change',function(){
	project_id = this.value;
});
$('#project_postings','#nav-desktop li').on('change',function(){
	project__id_postings = this.value;
});
$('.boards').on('click',function(){
	event.preventDefault();
	prevBoard = board;
	board = $(this).attr('id');

	if(board === 'db_disc_board'){
		var data = {
            _token:$(this).data('token'),
            project: project_id,
            page: notifPage,
            all: false
        }

		$.ajax({
            url: 'discussionBoard',
            type:"POST",
            data: data,
            success:function(data){
				for(var i=0;i< data.discussions.length;i++){
					var discussion = {profile:'',first_name:'',last_name:'',disc_id:'',disc_title:'',disc_text:''};
						discussion.profile = data.discussions[i].profile;
			    		discussion.first_name = data.discussions[i].first_name;
			    		discussion.last_name = data.discussions[i].last_name;
			    		discussion.disc_id = data.discussions[i].disc_id;
			    		discussion.disc_title = data.discussions[i].disc_title;
			    		discussion.disc_text = data.discussions[i].disc_text;
			    		discussion.priority = data.discussions[i].priority;

			    	if(discussions.length == 0){
			    		discussions.push(discussion);
			    	}else{
		    			if(function(){
			    			for(var j=0;j<data.length;j++){
			    				if(discussion.disc_id == discussions[i].disc_id){
			    					return false;
			    				}
			    			}
			    			return true;
			    		}){
				    		discussions.unshift(discussion); //add items at the beginning of array
					    }	
			    	}
				}
				$('.modal-content ul li','#modal2').remove();
				updateModal2(board);
				launchGenContent(discussions[0].disc_id);
				displayed_id = discussions[0].disc_id;
				return true;
            },error:function(){ 
                alert("An Error!!!!");
                return false;
            }
        });
	}
});


$('a').on('click', function() {

	if($(this).attr('href') != undefined){
		if($(this).attr('href') ==='#top' ) {
	   		$('html,body').animate({ scrollTop: 0 }, 'slow');
		}else if($(this).attr('href')==='#post'){
			var active = $('#post_tabs li a.active').attr('href');

			if(active == '#post_disc'){
				var formId = '#postDiscussion';
				var dataform =  new FormData();
				
				dataform = colateText('addl_post_disc');
				dataform.append('_token',$(formId+' [name=_token]')[0].value);
				dataform.append('priority',$(formId+' [name=pd_priority]')[0].value);
				dataform.append('project_id',$(formId+' [name=pd_project]')[0].value);
				dataform.append('title',$(formId+' [name=title]')[0].value);
				
				if(ajaxSubmitPostings(formId,dataform)){
					resetPostings('addl_post_disc',1);
					//reset postings
				}

				
				return false;
			}
		}else if($(this).attr('href')==='#comment'){

			if(board === 'db_disc_board'){	//comment on current discussion

				var formId = '#postComment';
				var dataform =  new FormData();
				
				dataform = colateText('addl_post_comment');
				dataform.append('_token',$(formId+' [name=_token]')[0].value);
				dataform.append('discussion_id',displayed_id);
				
				if(ajaxSubmitPostings(formId,dataform)){
					
					resetPostings('addl_post_comment',4);
					commentsRefresh();
					
				}
				
				return false;
			}
		}else if($(this).attr('href')==='#upImageDisc'){
			addImage('addl_post_disc','file_img','img_desc_text',discImageCntr);
	   		$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
		}else if($(this).attr('href')==='#upHighlightDisc'){
			addHighlight('addl_post_disc','hl_text',discHighlightCntr);
			$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+100}, 'slow');
		}else if($(this).attr('href')==='#upDescDisc'){
			addDesc('addl_post_disc','text','Continue...',discTextCntr);
			$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
		}else if($(this).attr('href')==='#upImageComment'){
			addImage('addl_post_comment','file_img','img_desc_text',commImageCntr);
		}else if($(this).attr('href')==='#upHighlightComment'){
			addHighlight('addl_post_comment','hl_text',commHighlightCntr);
		}else if($(this).attr('href')==='#upDescComment'){
			addDesc('addl_post_comment','text','Continue...',commTextCntr);
		}else{
			//console.log($(this).attr('href'));
		}
	}
});
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
		case 4: commTextCntr = 1;
				commImageCntr = 0;
				commHighlightCntr = 0;
				addDesc('addl_post_comment','text','What do you want to say?',commTextCntr);
				break;
	}
	

	
	
	

}
function commentsRefresh(){
	var url = "";
	if(board === "db_disc_board"){
		url = "/post/discussion/comment/cnt";
	}
	var data = {
			 _token:$('#'+board).data('token'),
            id: displayed_id
		};
	$.ajax({
		url: url,
		data: data,
		type: "POST",
		success:function(data){
			if(data.cnt != $('#lsComments ul li').length){
				genContentComments(displayed_id);
			}
		},error:function(data){
			console.log(data);
		},complete: function() {
	      // Schedule the next request when the current one's complete
			setTimeout(commentsRefresh, 20000);
		}
	});
}
function notifsNextPage(pageNo){
	var url = "";
	var prev = discussions.length;
	if(board === "db_disc_board"){
		url = "discussionBoard";
	}
	var data = {
			 _token:$('#'+board).data('token'),
            project: project_id,
            page: pageNo+1,
            all: false
		};
	$.ajax({
		url: url,
		data: data,
		type: "POST",
		success:function(data){
			for(var i=0;i< data.discussions.length;i++){
				var discussion = {profile:'',first_name:'',last_name:'',disc_id:'',disc_title:'',disc_text:''};
					discussion.profile = data.discussions[i].profile;
		    		discussion.first_name = data.discussions[i].first_name;
		    		discussion.last_name = data.discussions[i].last_name;
		    		discussion.disc_id = data.discussions[i].discussion_id;
		    		discussion.disc_title = data.discussions[i].disc_title;
		    		discussion.disc_text = data.discussions[i].disc_text;

		    	if(discussions.length == 0){
		    		discussions.push(discussion);
		    	}else{
		    		if(function(){
		    			for(var j=0;j<data.length;j++){
		    				if(discussion.disc_id == discussions[i].disc_id){
		    					console.log(false);
		    					return false;
		    				}
		    			}
		    			return true;
		    		}){
			    		discussions.unshift(discussion); //add items at the beginning of array
			    	}
		    	}
			}
			if(prev < discussions.length){
				updateModal2(board);
			}
			
		},error:function(data){

		}
	});
}
function notifsRefresh(){
	var url = "";
	if(board === "db_disc_board"){
		url = "post/discussion/notifs/cnt";
	}
	var data = {
			 _token:$('#'+board).data('token'),
            project: project_id,
            all: false

		};
	$.ajax({
		url: url,
		data: data,
		type: "GET",
		success:function(data){
			if(data.cnt > 0 && data.cnt != $('#modal2 div ul li').length){
				notifsNextPage(-1);
			}
		},error:function(data){

		},complete: function() {
	      // Schedule the next request when the current one's complete
			setTimeout(notifsRefresh, 30000);
		}
	});
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
                error = data.statusText;
                return false;
            }
        });
    if(error == undefined){
    	return true;
    }
    console.log(error);
    return false;
}
function colateText(arr){	
	var formData = new FormData();
	
	var arr = $('#'+ arr +' :input');
	var text = "";
	for(var i=0;i<arr.length;i++){
		if((arr[i].value != "")){
			if(arr[i].tagName === "TEXTAREA"){
				text+=arr[i].value;
			}else if(arr[i].tagName === "INPUT"){
				if(arr[i].type == "file"){
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
function vote(id,type){
	var data = {
		_token:$('#'+board).data('token'),
       id: id,
       type:type,
    }

	$.ajax({
        url: '/discussion/upvotes',
        type:"POST",
        data: data,
        success:function(data){
			var u = $('#upc'+id);
			u.empty();
			u.append(document.createTextNode(data.upcount.vote_count));
			var d = $('#downc'+id);
			d.empty();
			var t2 = data.downcount.vote_count;
			d.append(t2);
			return true;
        },error:function(){ 
            alert("An Error!!!!");
            return false;
        }
    });
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
$(document).ready(function(){
	$('select').material_select();
    $('.scrollspy').scrollSpy();
    $('.tabs-wrapper .row').pushpin({ top: $('.tabs-wrapper').offset().top });
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
	if($('#modal2 div ul li').length == 0){
		$('#modal2 div ul li').ready(function(){
			$('html,body').animate({ scrollTop: 0 }, 'slow');
		    var data = {
	            _token:$('#'+board).data('token'),
	            project: project_id,
	            page: notifPage,
	            all: true
		        }
			$.ajax({
	            url: 'discussionBoard',
	            type:"POST",
	            data: data,
	            success:function(data){
	            	for(var i=0;i< data.discussions.length;i++){
						var discussion = {profile:'',first_name:'',last_name:'',disc_id:'',disc_title:'',disc_text:''};
							discussion.profile = data.discussions[i].profile;
				    		discussion.first_name = data.discussions[i].first_name;
				    		discussion.last_name = data.discussions[i].last_name;
				    		discussion.disc_id = data.discussions[i].discussion_id;
				    		discussion.disc_title = data.discussions[i].disc_title;
				    		discussion.disc_text = data.discussions[i].disc_text;
				    		discussion.priority = data.discussions[i].priority;

				    	if(discussions.length == 0){
				    		discussions.push(discussion);
				    	}else{
				    		if(function(){
				    			for(var j=0;j<data.length;j++){
				    				if(discussion.disc_id == discussions[i].disc_id){
				    					return false;
				    				}
				    			}
				    			return true;
				    		}){
					    		discussions.unshift(discussion); //add items at the beginning of array
					    	}
				    	}
					}
					$('.modal-content ul li','#modal2').remove();
					updateModal2(board);
					launchGenContent(discussions[0].disc_id);
					displayed_id = discussions[0].disc_id;
					return true;
	            },error:function(){ 
	                alert("An Error!!!!");
	                return false;
	            }
	        });
		});
	}
	notifsRefresh();
	commentsRefresh();
});
jQuery(function($) {
    $('#modal2').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            notifsNextPage(notifPage);
        }
    })
});
function getProjects(){

	var ul = $('#projects ul');
	
	var data = {
		_token:$('#'+board).data('token'),
	};
	$.ajax({
		url: 'project/getProjects',
		type:"POST",
    	data: data,
    	success:function(data){
    		var ul = $('#projects ul');
    		for(var i=0;i<data.projects.length;i++){
    			var li = "<li class='collection-item'>" + data.projects[i].text;
    				li += "<a class='joinProj right' href='/project/joinProject/" + data.projects[i].id +"'";
    				li += "' data-token=" + $('#'+board).data('token') + "> JOIN </a>";
    			if(ul.length == 0){
					ul.append(li);
				}else{
					ul.first().before(li);
				}
    		}
    		
    	}
	});
}
$('.joinProj').on('click',function(){
	event.preventDefault();
	var data = {
		token: $(this).data('token')
	}
	$.ajax({
		url:$(this).attr('href'),
		type:"POST",
		data: data,
		success:function(data){
			console.log(data);
		},
		error:function(data){

		}
	});
});