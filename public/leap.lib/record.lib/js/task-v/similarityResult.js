(function(){
  dataform = new FormData();
  dataform.append('_token',user_info.token);
  dataform.append('id',pageid);
  
  $.ajax({
      url: '/recordings/exer/result/',
      processData: false,
      contentType: false,
      type:"POST",
      data: dataform,
      
      success:function(data){
        var status = data.status;
        var msg = data.message;
        if(msg === null || msg === undefined)
          similarityResult = [];
        else{
          similarityResult = JSON.parse(msg);
        }
        return true;
        },error:function(data){ 
            
          
            return false;
        }
      });

}).call(this);