(function(){
getLoading('div_details');
if($('#div_details').is(':parent') ){
  $('#div_contents').empty();
  //$('#div_comments').empty();
}

var data = {
  _token:user_info.token,
  id:pageid,
  project: project_id,
  all: true
}

$.ajax({
      url: '/taskBoard/info',
      type:"POST",
      data: data,
      success:function(data){
        if(data.status == 'success'){
          
            var task_info = data.task;
        }
        var artcle = {id:'',title:'',text:'',images:'',updated_at:''};
            artcle.id    = task_info.task_id;
            artcle.title   = task_info.task_title;
            artcle.text    = task_info.task_text;
            artcle.images   = task_info.task_images;
            artcle.updated_at   = task_info.updated_at;
            
          updateTaskInfoDetail(artcle,$('#div_details','#genContent'),$('#div_contents','#genContent'));
         
        return true;
      },error:function(){ 
          alert("An Error!!!!");
          return false;
      }
  });


function updateTaskInfoDetail(artcle,details,genContent){
//details
  var details = details;
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

  var dtls = '<h4>'+ artcle.title +'</h4><h6 class="grey-text text-darken-1">'+' '+ date.toUTCString() +'</h6>';

  details.append(dtls);

  //contents
  var contents = genContent;
  var conts = filterGenText(artcle.text,artcle.images);

  contents.append(conts);
  
  
    $('.materialboxed').materialbox();
    $('.tooltipped').tooltip({delay: 50});
  
}

}).call(this);
function submitAdjustedScore(){
  var data = {
    _token:user_info.token,
    id:pageid,
    adjustedScore:document.getElementById('adjustedScore').value,
    all: true
  }
  $.ajax({
        url: '/post/exerdata/adjustedScore',
        type:"POST",
        data: data,
        success:function(data){
          if(data.status == 'success'){ 
              var toastContent = "<span>" + data.message + "</span>";
              Materialize.toast(toastContent, 1000, 'red darken-4');
          }           
          return true;
        },error:function(){ 
            alert("An Error!!!!");
            return false;
        }
    });
}