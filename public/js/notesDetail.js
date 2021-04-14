(function(){
var data = {
  _token:user_info.token,
  id:pageid
}

$.ajax({
      url: '/note/list/task',
      type:"POST",
      data: data,
      success:function(data){
        if(data.status == 'success'){
          var ul = document.getElementById("sidePanel");
          for(info of data.notes){
            var artcle = {id:'',title:'',text:'',images:'',updated_at:''};
                artcle.id    = info.id;
                artcle.title   = info.title;
                artcle.text    = info.text;
                artcle.images   = info.images;
                artcle.updated_at   = info.updated_at;
            

            var li = addNode(ul,"li",undefined,undefined,undefined,undefined,undefined,undefined);
                                    //(parent,type,id,name,classTxt,value,inpType,textNode)
            var div_header = addNode(li,"div",undefined,undefined,"collapsible-header");
            var header_ul = addNode(div_header,"ul",undefined,undefined,"collection");
            var header_li = addNode(header_ul,"li",undefined,undefined,"collection-avatar",undefined,undefined,"Note ID: "+artcle.id);


            var div_body = addNode(li,"div",undefined,undefined,"collapsible-body");
            var row = addNode(div_body,"div",undefined,undefined,"row");
            var div2 = addNode(row,"div",undefined,undefined,"col s12 m12 l12 grey lighten-2");
            var details = addNode(div2,"div","details"+artcle.id);
            var contents = addNode(div2,"div","contents"+artcle.id);
            updateNoteInfoDetail(artcle,details,contents);
          }
        }
        
         
        return true;
      },error:function(){ 
          alert("An Error!!!!");
          return false;
      }
  });
}).call(this);