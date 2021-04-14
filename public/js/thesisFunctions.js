function euclideanDistance(obj1,obj2){
    if(!Array.isArray(obj1)){
        var x1 = (obj1.mcpPosition[0]*3);
        var y1 = (obj1.mcpPosition[1]*3)-200;;
        var z1 = (obj1.mcpPosition[2]*3)-400;
    }else{
        var x1 = (obj1[0]*3);
        var y1 = (obj1[1]*3)-200;;
        var z1 = (obj1[2]*3)-400;
    }
    if(!Array.isArray(obj2)){
        var x2 = (obj2.mcpPosition[0]*3);
        var y2 = (obj2.mcpPosition[1]*3)-200;;
        var z2 = (obj2.mcpPosition[2]*3)-400;
    }else{
        var x2 = (obj2[0]*3);
        var y2 = (obj2[1]*3)-200;;
        var z2 = (obj2[2]*3)-400;
    }   

    var x = Math.pow(x1-x2,2);
    var y = Math.pow(y1-y2,2);
    var z = Math.pow(z1-z2,2);
    return Math.sqrt(x+y+z);
}
function onProtocol(frame) {

    //if(player.plugins.playback.player.state==='playing'){
      var fingers = {};
      var spheres = {};
      var record = [];
      
      if (frame.hands === undefined ) {
        var handsLength = 0
      } else {
        var handsLength = frame.hands.length;
      }

      for (var handId = 0, handCount = handsLength; handId != handCount; handId++) {
        var hand = frame.hands[handId];
        var posX = (hand.palmPosition[0]*3);
        var posY = (hand.palmPosition[2]*3)-200;
        var posZ = (hand.palmPosition[1]*3)-400;
        var rotX = (hand._rotation[2]*90);
        var rotY = (hand._rotation[1]*90);
        var rotZ = (hand._rotation[0]*90);
        var sphere = spheres[hand.id];

        if(handId == 0){
          var d1 = euclideanDistance(hand.palmPosition,hand.fingers[0]);
          var d2 = euclideanDistance(hand.palmPosition,hand.fingers[1]);
          var d3 = euclideanDistance(hand.palmPosition,hand.fingers[2]);
          var d4 = euclideanDistance(hand.palmPosition,hand.fingers[3]);
          var d5 = euclideanDistance(hand.palmPosition,hand.fingers[4]);
          var d6 = euclideanDistance(hand.fingers[4],hand.fingers[3]);
          var d7 = euclideanDistance(hand.fingers[3],hand.fingers[2]);
          var d8 = euclideanDistance(hand.fingers[2],hand.fingers[1]);

          record = [d1,d2,d3,d4,d5,d6,d7];//,d8];
          if(handsLength == 1) break;
        }else{

          record.push(euclideanDistance(hand.palmPosition,hand.fingers[0]));
          record.push(euclideanDistance(hand.palmPosition,hand.fingers[1]));
          record.push(euclideanDistance(hand.palmPosition,hand.fingers[2]));
          record.push(euclideanDistance(hand.palmPosition,hand.fingers[3]));
          record.push(euclideanDistance(hand.palmPosition,hand.fingers[4]));
          record.push(euclideanDistance(hand.fingers[4],hand.fingers[3]));
          record.push(euclideanDistance(hand.fingers[3],hand.fingers[2]));
          record.push(euclideanDistance(hand.fingers[2],hand.fingers[1]));
        }
        //console.table(record);
      }
      //temp = record;
      fingers = undefined;
      spheres = undefined;
      //record = undefined;
      return record;
    //}
}
/*
  * gesture1 euclidean distances from the hands in player
  * gesture2 euclidean distances from the hands in recorder
  *
  * -1 == perfectly disimilar
  * 1 == perfectly similar
  * note: number of hands should be the same for player and recorder
*/
function cosineSimilarity(cd,cf){
  
  if(cd.length === cf.length){
    var eAB = 0;                          //sum(cf * cd)
    var ecf = 0, ecd = 0;                 //sum(cf^2) or sum(cd^2)
    for(var i=0; i<cd.length;i++){
      eAB += cf[i] * cd[i];
      ecf += Math.pow(cf[i],2)
      ecd += Math.pow(cd[i],2);
    }
    return(eAB/(Math.sqrt(ecf) * Math.sqrt(ecd)));

  }else{
    return -1; //
  }
}