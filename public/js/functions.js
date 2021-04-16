var siteUrl = window.location.hostname;
// For todays date;
Date.prototype.today = function () { 
    return ((this.getDate() < 10)?"0":"") + this.getDate() +"/"+(((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) +"/"+ this.getFullYear();
}

// For the time now
Date.prototype.timeNow = function () {
     return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
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
function updateGenContentTasks(arr,div){

	var add1 = $('.modal-content ul','#'+div);
	var add2 = $('.modal-content ul li','#'+div);
	
		for(var i=0;i < arr.length;i++){
			if(arr.length > 1 && (arr.length == add2.length)) break;
			var li ="";
			var font = "";
			
			li = "<li><div class='collapsible-header'><ul class='collection'><li class='collection-item avatar'>";
			if(user_type!=1){
				li 	+=	 "<img src='http://" + siteUrl + "/profile/image/get/t/"+arr[i].creator_info.profile+"' alt='notavailable' class='profiles circle' />"+
						 "<div class='title"+font+"'>"+arr[i].creator_info.first_name+" "+arr[i].creator_info.last_name;
			}else{
				li 	+=	 "<img src='http://" + siteUrl + "/profile/image/get/t/"+arr[i].patient_info.profile+"' alt='notavailable' class='profiles circle' />"+
					 "<div class='title"+font+"'>"+arr[i].patient_info.first_name+" "+arr[i].patient_info.last_name;
			}

			li  +=	 "<p class='"+font+"'>"+arr[i].task_info.task_title+"</p>";
			li+="</ul></div>";
			li+="<div class='collapsible-body'>";
			if(arr[i].task_text!=false){
				li+="<p>"+arr[i].task_info.task_text+"</p>";				
			}

			
			//task_exer_datas
			if(arr[i].exers_info != undefined){
				//for(var j=0;j<tasks[i].exers_info[j].length;j++){
					var prev_task_id = -1;
					var freq = 1;
					li+="	<ul class='collection'>";
					for(var k=0;k<arr[i].exers_info.task_datas.length;k++){
						if(prev_task_id != arr[i].exers_info.task_datas[k].task_assignment_id)
							li+="<li class='collection-item'><span><h5>Day #"+ (freq++) + "</h5></span></li>";
						li+="<li class='collection-item'><span>Exercise #"+(arr[i].exers_info.task_datas[k].freq_order);
						if(user_type==1){
							if(arr[i].exers_info.task_datas[k].resultScore){
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip blue-text tooltipped' data-position='top' data-delay='50' data-tooltip='Generated Score'>"+ arr[i].exers_info.task_datas[k].resultScore*100 + "%<i class='material-icons'>assessment</i></div>";
							}
							else{
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip red-text tooltipped' data-position='top' data-delay='50' data-tooltip='Generated Score'>Pending<i class='material-icons'>assessment</i></div>";
							}
							if(arr[i].exers_info.task_datas[k].adjustedResultScore){
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip blue-text tooltipped' data-position='top' data-delay='50' data-tooltip='Adjusted Score'>"+ arr[i].exers_info.task_datas[k].adjustedResultScore + "<i class='material-icons'>assessment</i></div>";
							}
							else{
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip red-text tooltipped' data-position='top' data-delay='50' data-tooltip='Adjusted Score''>Pending<i class='material-icons'>assessment</i></div>";
							}
						}else if(user_type==2){
							if(arr[i].exers_info.task_datas[k].resultScore){
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip blue-text tooltipped' data-position='top' data-delay='50' data-tooltip='Status'>Performed<i class='material-icons'>play_arrow</i></div>";
							}else{
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip red-text tooltipped' data-position='top' data-delay='50' data-tooltip='Status'>Pending<i class='material-icons'>play_arrow</i></div>";
							}
							if(arr[i].exers_info.task_datas[k].adjustedResultScore){
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip blue-text tooltipped' data-position='top' data-delay='50' data-tooltip='Assessment'>Assessed<i class='material-icons'>assessment</i></div>";
							}
							else{
								li+= "&nbsp&nbsp&nbsp&nbsp<div class='chip red-text tooltipped' data-position='top' data-delay='50' data-tooltip='Assessment'>Pending<i class='material-icons'>assessment</i></div>";
							}
						}
							
						li+="<div class='secondary-content "+font+"'><a class='btn btn-flat tooltipped' href='http://" + siteUrl + "/tasks/" + arr[i].exers_info.task_datas[k].id + "' target='_blank' data-position='left' data-delay='50' data-tooltip='Launch'><i class='material-icons'>launch</i></a></div>";
						
						li+="</li>";

						prev_task_id = arr[i].exers_info.task_datas[k].task_assignment_id;
						
					}

					li+="	</ul>";
						
						//li+="</div>";
				//}
				
			}
			li+="</ul></div></li></li>";
			if(add2.length == 0){
				add1.append(li);
			}else{
				add2.first().before(li);
			}
			$('.tooltipped').tooltip();
			$('.collapsible').collapsible();
		}
	
}
function updateModal2(currBoard,div){
	var add1 = $('.modal-content ul','#'+div);
	var add2 = $('.modal-content ul li','#'+div);
	//add2.remove();

	if(!Array.isArray(currBoard)){
		if(currBoard === "db_disc_board"){
			for(var i=0;i < discussions.length;i++){
				if(discussions.length== add2.length) break;
				var li ="";
				var font = "";
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

				li 	+=	 "<img src='http://" + siteUrl + "/profile/image/get/t/"+discussions[i].profile+"' alt='notavailable' class='profiles circle' />"+
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
	}else{
		for(var i=0;i < currBoard.length;i++){
			if(currBoard.length== add2.length) break;
			var li ="";
			var font = "";
			switch(currBoard[i].priority){
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

			li 	+=	 "<img src='http://" + siteUrl + "/profile/image/get/t/"+currBoard[i].profile+"' alt='notavailable' class='profiles circle' />"+
					 "<span class='title"+font+"'>"+currBoard[i].first_name+" "+currBoard[i].last_name+"</span>"+
					 "<p class='"+font+"'>"+currBoard[i].disc_title+"...<br/>";
			if(currBoard[i].disc_text!=false){
				var text = filterQuickText(currBoard[i].disc_text);
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
			li+="<a href='#top' class='secondary-content"+font+"' type='button' onclick='launchGenContent("+ currBoard[i].disc_id +");'><i class='material-icons'>launch</i></a></li>";
			if(add2.length == 0){
				add1.append(li);
			}else{
				add2.first().before(li);
			}
		}
	}
}
function launchGenContent(disc_id){
	if(user_type == 1){
		$('#genPatientBoard').hide();
		$('#search').prop( "disabled", true );
	}
	$('#genContentTask').hide();
	$('#genContent').show();
	
	displayed_id = disc_id;
	getLoading('div_details');
	if($('#div_details').is(':parent') ){
		$('#div_contents').empty();
		//$('#div_comments').empty();
	}
	
	var data = {
        disc_id: disc_id,
    }

	$.ajax({
        url: 'discussion',
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
        disc_id: disc_id,
    }

	$.ajax({
        url: '/discussion/comments',
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





function commentsRefresh(){
	if(board === "db_disc_board"){
		var data = {
            id: displayed_id
		};
		$.ajax({
			url: "/post/discussion/comment/cnt",
			data: data,
			success:function(data){
				if(data.cnt != $('#lsComments ul li').length){
					genContentComments(displayed_id);
				}
			},error:function(data){
			},complete: function() {
		      // Schedule the next request when the current one's complete
				setTimeout(commentsRefresh, 20000);
			}
		});
	}	
}
function notifsNextPage(pageNo){
	var url = "";
	var prev = discussions.length;
	if(board === "db_disc_board"){
		url = "discussionBoard";
	
		var data = {
	            project: project_id,
	            page: pageNo+1,
	            all: false
			};
		$.ajax({
			url: url,
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
				if(prev < discussions.length){
					updateModal2(board,'modal2');
				}
				
			},error:function(data){

			}
		});
	}
}
function notifsRefresh(){

	var url = "";
	if(board === "db_disc_board"){
		url = "post/discussion/notifs/cnt";
	}
	var data = {
            project: project_id,
            all: false
		};
	$.ajax({
		url: url,
		data: data,
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
function vote(id,type){
	var data = {
       id: id,
       type:type,
    }

	$.ajax({
        url: '/discussion/upvotes',
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
function getProjects(){

	var ul = $('#projects ul');
	
	var data = {
	};
	$.ajax({
		url: 'project/getProjects',
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


function submitActivationForm(formId){
	var div = formId + "_div";
	formId = '#' + formId;
	var cur = document.getElementById(div);
	var dataform =  new FormData();

	//dataform.append('_token',$(formId + ' [name=_token]')[0].value);
	dataform.append('email',$(formId+' [name=email]')[0].value);
	
	var error;
	$.ajax({
        url: $(formId).attr('action'),
        processData: false,
		contentType: false,
		mimeType: 'multipart/form-data',
       	data: dataform,
        
        success:function(data){
        	var status = JSON.parse(data).status;
        	var msg = JSON.parse(data).message;
        	if(status == "validatorFail"){
        		
            	for(var message in msg){            		
            		var toastContent = "<span>" + msg[message] + "</span>";
					Materialize.toast(toastContent, 5000, 'red darken-4');
            	}
            }else if(status == "fail"){
            	var toastContent = "<span>" + msg + "</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
            }else if(status == "success"){
            	var toastContent = "<span>" + msg + "</span>";
				Materialize.toast(toastContent, 5000, 'red darken-4');
				
                var row = addNode(cur,'li',undefined,undefined,'collection-item',undefined,undefined,undefined);
                addNode(row,undefined,undefined,undefined,undefined,undefined,undefined,$(formId+' [name=email]')[0].value + " ");
                a = addNode(row,'a',undefined,undefined,"btn waves-effect btn-flat",undefined,undefined,"Copy");
                a.onclick = function(){
                    var text = msg;
                    var listener = function(ev) {
                        ev.clipboardData.setData("text/plain", text);
                        ev.preventDefault();
                    };
                    document.addEventListener("copy", listener);
                    document.execCommand("copy");
                    document.removeEventListener("copy", listener);
                    toastContent = "<span>" + "Copied Code: " + msg + "</span>";
                    Materialize.toast(toastContent, 5000, 'red darken-4');
                };
                var a = addNode(row,'a',undefined,undefined,"secondary-content btn waves-effect",undefined,undefined,"Edit");
                var siteUrl = window.location.href.split('/')[2];
                a.href = "http://" + siteUrl + "/auth/profile/edit/" + msg;
                a.target = "_blank";
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



function getExerDataList(){
	formId = '#postExerciseDataList';
	
	var dataform =  new FormData();

	//dataform.append('_token',$('#postExerciseDataList [name=_token]')[0].value);
	$.ajax({
		url: '/post/exerdata/list',
        processData: false,
		contentType: false,
    	data: dataform,
    	success:function(data){
    		var status = data.success;
        	var msg = data.message;
        	var cur = document.getElementById('ul_view_exercises');
        	if(status == false){
            	for(errors of msg){
                    var toastContent = "<span>" + errors + "</span>";
                    Materialize.toast(toastContent, 1000, 'red darken-4');
                }
            }else if(status == true){
	    		for(result of msg){
                    if(result.length != 0){
                        if(exerDataList.length == 0){
                            exerDataList.push(result);
                        }else{
                            var flg = 0; //no similar result
                            for(res of exerDataList){
                                if(res.id == result.id){
                                    flg = 1;
                                    break;
                                }
                            }
                            if(flg==0){
                                exerDataList.push(result);
                            }
                        }
                    }
                }
            	cur.innerHTML = " ";
                for(result of exerDataList){
                	var row = addNode(cur,'li',undefined,undefined,'collection-item',undefined,undefined,undefined);
                    addNode(row,"span",undefined,undefined,undefined,undefined,undefined,result.desc);

                    var a = addNode(row,'a',undefined,undefined,"secondary-content",undefined,undefined,undefined);
                    addNode(a,'i',undefined,undefined,"material-icons",undefined,undefined,"launch");
                    var siteUrl = window.location.href.split('/')[2];
                    a.href = "http://" + siteUrl + "/preview/" + result.id;
                    a.target = "_blank";
                    
                }
			}
    	}
	});
}

function getPatientList(){
	//formId = '#postTask';
	
	var dataform =  new FormData();

	//dataform.append('_token',$('#postTask [name=_token]')[0].value);
	$.ajax({
		url: '/post/patient/list',
        processData: false,
		contentType: false,
    	data: dataform,
    	success:function(data){
    		var status = data.success;
        	var msg = data.message;
        	if(status == false){
            	for(errors of msg){
                    var toastContent = "<span>" + errors + "</span>";
                    Materialize.toast(toastContent, 1000, 'red darken-4');
                }
            }else if(status == true){
	    		for(result of msg){
                    if(result.length != 0){
                        if(patientDataList.length == 0){
                            patientDataList.push(result);
                        }else{
                            var flg = 0; //no similar result
                            for(res of patientDataList){
                                if(res.user_id == result.user_id){
                                    flg = 1;
                                    break;
                                }
                            }
                            if(flg==0){
                                patientDataList.push(result);
                            }
                        }
                    }
                }
                populateProjectList();
			}
    	}
	});
}
function populateExerData(){
	getExerDataList();
	var sel_inp = document.getElementById('leapData');
	sel_inp.innerHTML = " ";

	var opt1 = addNode(sel_inp,'option',undefined,undefined,undefined,undefined,undefined,"Choose exercise.");
	opt1.setAttribute('disabled', 'disabled');
	opt1.setAttribute('selected','selected');
	for(result of exerDataList){
    	var row = addNode(sel_inp,'option',undefined,undefined,undefined,result.id,undefined,result.desc);

        var a = addNode(row,'a',undefined,undefined,"secondary-content",undefined,undefined,undefined);
        addNode(a,'i',undefined,undefined,"material-icons",undefined,undefined,"launch");
        var siteUrl = window.location.href.split('/')[2];
        a.href = "http://" + siteUrl + "/preview/" + result.id;
        a.target = "_blank";
    }
    $('select').material_select();
}
function populateProjectList(){
	var sel_inp = document.getElementById('patientData');
	sel_inp.innerHTML = " ";

	var opt1 = addNode(sel_inp,'option',undefined,undefined,undefined,undefined,undefined,"Choose patient.");
	opt1.setAttribute('disabled', 'disabled');
	opt1.setAttribute('selected','selected');
	for(result of patientDataList){
    	var row = addNode(sel_inp,'option',undefined,undefined,undefined,result.user_id,undefined,result.first_name + " " + result.middle_name + " " + result.last_name + " " + result.suffix_name);
    	var img = addNode(row,'img',undefined,undefined,"secondary-content circle",undefined,undefined,undefined);
    	img.setAttribute('src',"/profile/image/t/"+result.user_id);
    	img.setAttribute('alt','notavailable');
        /*var a = addNode(row,'a',undefined,undefined,"secondary-content",undefined,undefined,undefined);
        addNode(a,'i',undefined,undefined,"material-icons",undefined,undefined,"launch");
        var siteUrl = window.location.href.split('/')[2];
        a.href = "http://" + siteUrl + "/preview/" + result.id + ".json.lz";
        a.target = "_blank";*/
    }
    $('select').material_select();
}
function submitExer(id){

    var dataform =  new FormData();

	//dataform.append('_token',$('#db_task_board').data('token'));
	dataform.append('id',id);
	if($('#leapData'+id).val() == undefined){
		var toastContent = "<span> Leap File Required. </span>";
        Materialize.toast(toastContent, 5000, 'red darken-4');
	}else{
		dataform.append('leapData',$('#leapData'+id).prop('files')[0]);
	}
	$.ajax({
			url: '/post/task/patient',
            processData: false,
			contentType: false,
			mimeType: 'multipart/form-data',
            data: dataform,            
            success:function(data){
            	var status = JSON.parse(data).status;
            	var msg = JSON.parse(data).message;
            	
                var toastContent = "<span>" + errors + "</span>";
                Materialize.toast(toastContent, 1000, 'red darken-4');
	                
				return true;
            },error:function(data){ 
                for(errors of JSON.parse(data).message){
	                var toastContent = "<span>" + errors + "</span>";
	                Materialize.toast(toastContent, 1000, 'red darken-4');
	            }
                return false;
            }
        });
        
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
		if(user_type == 1){
			$('#genPatientBoard').hide();
			$('#search').prop( "disabled", true );
		}
		$('#genContentTask').hide();
		$('#genContent').show();
		var data = {
            project: project_id,
            page: notifPage,
            all: false
        }

		$.ajax({
            url: 'discussionBoard',
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
				updateModal2(board,'modal2');
				launchGenContent(discussions[0].disc_id);
				displayed_id = discussions[0].disc_id;
				return true;
            },error:function(){ 
                alert("An Error!!!!");
                return false;
            }
        });
	}else if(board === "db_task_board"){
		if(user_type == 1){
			$('#genPatientBoard').hide();
			$('#search').prop( "disabled", true );
		}
		$('#genContent').hide();
		$('#genContentTask').show();
		
		if($('#genContentTask').is(':parent') ){
			$('#genContentTask').empty();
		}
		getLoading('genContentTask');

		var data = {
            project: project_id,
            all: true
        }

		$.ajax({
            url: 'taskBoard',
            data: data,
            success:function(data){

            	if(data.status == "fail"){
            		var toastContent = "<span>" + data.message + "</span>";
                	Materialize.toast(toastContent, 5000, 'red darken-4');
            	}else{

            		var tasks = [];

            		if(data.tasks.length > 0){
						for(var i=0;i< data.tasks.length;i++){
							var task = {creator_info:undefined,task_info:undefined,patient_info:undefined,exers_info:undefined};
							task.creator_info = data.tasks[i].creator_info;
							task.task_info = data.tasks[i].task_info;
							task.patient_info = data.tasks[i].patient_info;
							task.exers_info = data.tasks[i].exers_info;
							
					    	if(tasks.length == 0){
					    		tasks.push(task);
					    	}else{
				    			
						    	tasks.unshift(task); //add items at the beginning of array
							    	
					    	}
						}
						if($('#genContentTask').is(':parent') ){
							$('#genContentTask').empty();
						}
						var par = document.getElementById('genContentTask');
						var div = addNode(par,"div",undefined,undefined,"modal-content container");
						var ul = addNode(div,"ul",undefined,undefined,"collapsible popout");
						addNode(ul,"li");
						updateGenContentTasks(tasks,'genContentTask');
					}else{
						var toastContent = "<span> No Tasks available. </span>";
                		Materialize.toast(toastContent, 5000, 'red darken-4');
					}
					if(data.error.length >0){
						for(var i=0;i<data.error.length;i++){
							var toastContent = "<span> "+ data.error[i].status + "Patient ID: " + data.error[i].info + " </span>";
                		Materialize.toast(toastContent, 5000, 'red darken-4');
						}
					}
				}
				
				return true;
            },error:function(){ 
                alert("An Error!!!!");
                return false;
            }
        });
	}else if(board === "db_patients"){
		$('#genContent').hide();
		$('#genContentTask').hide();
		$('#genPatientBoard').show();
		$('#search').prop( "disabled", false );
		
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
				dataform.append('priority',$(formId+' [name=pd_priority]')[0].value);
				dataform.append('project_id',$(formId+' [name=pd_project]')[0].value);
				dataform.append('title',$(formId+' [name=title]')[0].value);

				if(ajaxSubmitPostings(formId,dataform)){
					resetPostings('addl_post_disc',1);
					//reset postings
				}

				
				return false;
			}else if(active == '#post_task'){
				var formId = '#postTask';
				var dataform =  new FormData();

				dataform = colateText('addl_post_task');
				dataform.append('project_id',$(formId+' [name=pd_project]')[0].value);
				dataform.append('title',$(formId+' [name=title]')[0].value);
				dataform.append('frequency',$('#freq').val());
				dataform.append('leapData',$('#leapData').val());
				dataform.append('patientData',$('#patientData').val());
								
				if(ajaxSubmitPostings(formId,dataform)){
					resetPostings('addl_post_task',1);
					//reset postings
				}
				return false;
			}else if(active == '#post_exercise'){
				var formId = '#postExerciseData';
				var dataform =  new FormData();

				dataform.append('title',$(formId+' [name=title]')[0].value);
				dataform.append('leapData',$(formId+' [name=leapData]')[0].files[0]);
								
				if(ajaxSubmitPostings(formId,dataform)){
					//materialize toast : success
					//resetPostings('addl_post_task',1);
					//reset postings
				}
			return false;
			}
		}else if($(this).attr('href')==='#comment'){

			if(board === 'db_disc_board'){	//comment on current discussion

				var formId = '#postComment';
				var dataform =  new FormData();
				
				dataform = colateText('addl_post_comment');
				dataform.append('discussion_id',displayed_id);
				
				if(ajaxSubmitPostings(formId,dataform)){
					
					resetPostings('addl_post_comment',4);
					commentsRefresh();
					
				}
				
				return false;
			}
		}else if($(this).attr('href')==='#post_task'){
			populateExerData();
		}else if($(this).attr('href')==='#upImageDisc'){
			addImage('addl_post_disc','file_img','img_desc_text',discImageCntr);
	   		$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
		}else if($(this).attr('href')==='#upHighlightDisc'){
			addHighlight('addl_post_disc','hl_text',discHighlightCntr);
			$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+100}, 'slow');
		}else if($(this).attr('href')==='#upDescDisc'){
			addDesc('addl_post_disc','text','Continue...',discTextCntr);
			$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
		}else if($(this).attr('href')==='#upImageTask'){
			addImage('addl_post_task','file_img','img_desc_text',taskImageCntr);
	   		$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+120}, 'slow');
		}else if($(this).attr('href')==='#upHighlightTask'){
			addHighlight('addl_post_task','hl_text',taskHighlightCntr);
			$('#m_postings .modal-content').animate({scrollTop:$('#m_postings .modal-content').scrollTop()+100}, 'slow');
		}else if($(this).attr('href')==='#upDescTask'){
			addDesc('addl_post_task','text','Continue...',taskTextCntr);
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
$('#a_p_add').click(function(){
    this.parentNode.className = "active";
    document.getElementById('li_find').className = "";
    document.getElementById('post_patient_add').style.display = 'block';
    document.getElementById('post_patient_find').style.display = 'none';
});
$('#a_find').click(function(){
    this.parentNode.className = "active";
    document.getElementById('li_p_add').className = "";
    document.getElementById('post_patient_add').style.display = 'none';
    document.getElementById('post_patient_find').style.display = 'block';
});
$('#a_e_add').click(function(){
    this.parentNode.className = "active";
    document.getElementById('li_e_view').className = "";
    document.getElementById('post_add_exercise').style.display = 'block';
    document.getElementById('post_view_exercise').style.display = 'none';
});
$('#e_view').click(function(){
    this.parentNode.className = "active";
    document.getElementById('li_e_add').className = "";
    document.getElementById('post_add_exercise').style.display = 'none';
    document.getElementById('post_view_exercise').style.display = 'block';
    getExerDataList();
});
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
	            project: project_id,
	            page: notifPage,
	            all: true
		        }
			$.ajax({
	            url: 'discussionBoard',
	            data: data,
	            success:function(data){
	            	if(data.discussions.length > 0){
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
						updateModal2(board,'modal2');
						launchGenContent(discussions[0].disc_id);
						displayed_id = discussions[0].disc_id;
					}
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
	if(user_type == 0 || user_type ==1){
		populateExerData();
		getPatientList();	
	}
	
});
jQuery(function($) {
    $('#modal2').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            notifsNextPage(notifPage);
        }
    })
});