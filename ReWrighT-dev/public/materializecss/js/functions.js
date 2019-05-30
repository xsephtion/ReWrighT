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

function submitRegForm(){
	if(chk_pword()){
		document.forms['f_reg'].submit();
		return true;
	}
	return false;
}
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
								"<img class='materialboxed' width='650' src='http://icsprom.app/css/images/"+image+"' alt='notavailable'/>"+
							"</div>";
	if(text!=null){
		card+=				"<div class='card-content'>"+
								"<p>"+text+"</p>"+
							"</div>";
	}
		card+=			"</div>"+
					"</div>"+
				"</div>";
	return card;
}
function textCard(text){
	var card = "<div class='row'>"+
					"<div class='col s12 m8 offset-m2'>"+
						"<div class='card small'>"+
							"<div class='card-content'>"+
								"<p>"+text+"</p>"+
							"</div>"+
						"</div>"+
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
		if(temp[j].startsWith('text',3)){
			var tt = temp[j].split('::');
			text+= tt[1] + " ";
		}else if(!temp[j].endsWith('!]>')){
			text+= temp[j] + " ";
		}else{
			var tt = temp[j].split('!]>');
			text+= tt[0] + " ";
			break;
		}
	}
	ret.pos = j;
	ret.text = text;
	return ret;
}
function filterGenComments(comments){
	var li = "<ul class='collection'>";
	console.log(comments);
	for(var i=0;i < comments.length;i++){
		var upvote = (comments[i].upvote!=null) ? comments[i].upvote : 0 ;
		var downvote = (comments[i].downvote!=null) ? comments[i].downvote : 0 ;
		li += "<li class='collection-item avatar'>"+
				 "<img src='http://icsprom.app/css/images/"+comments[i].profile+"' alt='notavailable' class='profiles circle' />"+
				 "<span class='card-title'>"+comments[i].first_name+" "+comments[i].last_name+"<br/>"+comments[i].created_at+"</span>"+
				 "<div class='votes'><a id='upc"+comments[i].id+"' class='btn tooltipped waves-effect waves-light-blue-accent-4 waves-ripple  light-blue darken-4' data-position='top' data-delay='50' data-tooltip='UPVOTE' href='#!' onclick='vote("+comments[i].id+",1);'><span >"+upvote+"</span></a><a id='downc"+comments[i].id+"' class='btn tooltipped waves-effect waves-light-blue-accent-4  btn-flat' data-position='top' data-delay='50' data-tooltip='DOWNVOTE' href='#!' onclick='vote("+comments[i].id+",2);'>"+downvote+"</a></div>"+
			 	"<p>"+filterGenText(comments[i].text,comments[i].image)+"</p>"+
			 "</li>";
	}
		li+= "</ul>";
	return li;
}
function filterGenText(text,image){
	if(text != null){
		var temp = text.split(" ");
		var images = (image != null) ? image.split(","):false;

		var newText = "";
		var k = 0;
		for(var i = 0; i<temp.length;i++){
			if(temp[i].startsWith('<[!') ){
				if(images != false){
					if(temp[i].startsWith('img',3)){
						var t = "";
						if(temp[++i].startsWith('<[!') ){
							if(temp[i].startsWith('text',3)){
								var ret = interpText(temp,i);
								var text= ret.text;						
								i = ret.pos;
								t = imageCard(images[k],text);
							}
						}else{
							t= imageCard(images[k]);
						}
						k++;
						newText+=t;
					}
				}else{
					newText+="<div class='card'> Image Unavailable </div>";
				}
			}else if(temp[i].startsWith('<[!') ){

				if(temp[i].startsWith('text',3)){
					var ret = interpText(temp,i);
					var text= ret.text;						
					i = ret.pos;
					t+=textCard(text);
				}
			}else{
				newText+=temp[i]+ " ";
			}
		}
		return newText;
	}
	return null;
}
function filterQuickText(text){
	var temp = text.split(" ");
	var newText = "";
	for(var i = 0; i<temp.length;i++){
		if(temp[i].startsWith('<[!') ){
			if(temp[i].startsWith('img',3)){
				temp[i] = "--image--";
				newText+=temp[i]+ " ";
			}else if(temp[i].startsWith('text',3)){
				var t = "";
				var j;
				for(j=i;j<temp.length;j++){
					if(temp[j].startsWith('text',3)){
						var tt = temp[i].split('::');
						t+= tt[1] + " ";
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
			}
		}else{
			newText+=temp[i]+ " ";
		}
	}
	return newText;
}
function updateModal2(currBoard){
	var add1 = $('.modal-content ul','#modal2');
	var add2 = $('.modal-content ul li','#modal2');
	//add2.remove();
	if(currBoard === "db_disc_board"){
		for(var i=0;i < discussions.length;i++){
			var li = "<li class='collection-item avatar'>"+
					 "<img src='http://icsprom.app/css/images/"+discussions[i].profile+"' alt='notavailable' class='profiles circle' />"+
					 "<span class='title'>"+discussions[i].first_name+" "+discussions[i].last_name+"</span>"+
					 "<p>"+discussions[i].disc_title+"...<br/>";
			if(discussions[i].disc_text!=false){
				var text = filterQuickText(discussions[i].disc_text);
				if(text.length < 60)
					li+=text+"</p>";    					
				else
					li+=text+"...</p>";
			}else{
				li+="</p>";
			}
			li+="<a href='#top' class='secondary-content' type='button' onclick='launchGenContent("+ discussions[i].disc_id +");'><i class='material-icons'>launch</i></a></li>";
			//console.log($('#modal2 .modal-content ul').length)
			if(add2.length == 0){
				add1.append(li);
			}else{
				add2.first().before(li);
			}
		}
	}
}
function genContentComments(disc_id){
	var data = {
		_token:$('#'+board).data('token'),
        disc_id: disc_id,
    }

	$.ajax({
        url: 'comments',
        type:"POST",
        data: data,
        success:function(data){
        		var comments;
				for(var i=0;i< data.comments.length;i++){
					var comment = {comment_id:'',user_id:'',profile:'',first_name:'',last_name:'',text:'',image:'',upvote:'',downvote:'',created_at:'',updated_at:''};
						comment.user_id = data.comments[i].user_id;
						comment.profile = data.comments[i].profile;
			    		comment.first_name = data.comments[i].first_name;
			    		comment.last_name = data.comments[i].last_name;
			    		comment.comment_id = data.comments[i].comment_id;
			    		comment.text = data.comments[i].text;
			    		comment.image = data.comments[i].image;
			    		comment.upvote = data.comments[i].upvote;
			    		comment.downvote = data.comments[i].downvote;
			    		comment.created_at = data.comments[i].created_at;
			    		comment.updated_at = data.comments[i].updated_at;

			    	if(comments.length == 0){
			    		article.comments.push(comment);
			    	}else{
			    		article.comments.unshift(comment); //add items at the beginning of array
			    	}
				}
				/*$('.modal-content ul li','#modal2').remove();
				updateModal2(board);*/
				return true;
        },error:function(){ 
            alert("An Error!!!!");
            return false;
        }
    });
}
function launchGenContent(disc_id){
	if($('#div_details').is(':parent') ){
		$('#div_contents').empty();
		$('#div_comments').empty();
	}
	getLoading('div_details');

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
	    		artcle.comments 	= data.comments;
	    	updateGenContent(artcle);
			return true;
        },error:function(){ 
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
			dtls+= '<div class="tags"><div class="chip">'+ artcle.project_name.substr(0,25) +'...</div><div class="chip"><img src="http://icsprom.app/css/images/'+ artcle.profile +'" alt="Contact Person">'+ artcle.first_name +' '+ artcle.last_name +'</div></div>';
		}else{
			dtls+= '<div class="tags"><div class="chip">'+ artcle.project_name +'</div><div class="chip"><img src="http://icsprom.app/css/images/'+ artcle.profile +'" alt="Contact Person">'+ artcle.first_name +' '+ artcle.last_name +'</div></div>';
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
	
	//comments
	var comments = $('#div_comments','#genContent');
	var com = filterGenComments(artcle.comments);
	if(comments.is(':parent') ){
		comments.empty();
	}
	//console.log(com);
	comments.append(com);
	
	general.details = dtls;
	general.content = conts;
	general.comments = com;
	
	$(document).ready(function(){
	    $('.materialboxed').materialbox();
	    $('.tooltipped').tooltip({delay: 50});
	});
}
$(document).ready(function(){
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
    $('.modal-trigger').leanModal();
    $('select').material_select();
	if($('#modal2 div ul li').length == 0){
		$('#modal2 div ul li').ready(function(){
			$('html,body').animate({ scrollTop: 0 }, 'slow');
		    var data = {
	            _token:$('#'+board).data('token'),
	            project: project_id,
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

				    	if(discussions.length == 0){
				    		discussions.push(discussion);
				    	}else{
				    		discussions.unshift(discussion); //add items at the beginning of array
				    	}
					}
					$('.modal-content ul li','#modal2').remove();
					updateModal2(board);
					launchGenContent(discussions[0].disc_id);
					return true;
	            },error:function(){ 
	                alert("An Error!!!!");
	                return false;
	            }
	        });
		});
	}
});
$('#project','#nav-desktop li').on('change',function(){
	project_id = this.value;
});
$('.boards').on('click',function(){
	event.preventDefault();
	prevBoard = board;
	board = $(this).attr('id');

	if(board === 'db_disc_board'){
		var data = {
            _token:$(this).data('token'),
            project: project_id,
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

			    	if(discussions.length == 0){
			    		discussions.push(discussion);
			    	}else{
			    		discussions.unshift(discussion); //add items at the beginning of array
			    	}
				}
				$('.modal-content ul li','#modal2').remove();
				updateModal2(board);
				launchGenContent(discussions[0].disc_id);
				return true;
            },error:function(){ 
                alert("An Error!!!!");
                return false;
            }
        });
	}
});


$('a').on('click', function() {

   if(($(this).attr('href').substr(0,1)) != '#' || ($(this).attr('href')) ==='#top' ) {
	    $('html,body').animate({ scrollTop: 0 }, 'slow');
	    return false; 
	}
});
function vote(id,type){
	var data = {
		_token:$('#'+board).data('token'),
       id: id,
       type:type,
    }

	$.ajax({
        url: 'upvotes',
        type:"POST",
        data: data,
        success:function(data){
			var u = $('#upc'+id);
			u.empty();
			var t1 = createTextNode(data.upcount.vote_count);
			u.append(t1);
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