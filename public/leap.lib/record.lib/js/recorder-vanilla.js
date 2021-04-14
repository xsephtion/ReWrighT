(function() {
  var rootApp = angular.module('rootApp', ['Player','Recorder']);
  var rootApp = angular.module('rootApp', ['Recorder']);


	player1.recorder = angular.module('Player', ['angulartics']);
  player1.player = function() {
    return player1.controller.plugins['playback'].player;
  };

  player2.recorder = angular.module('Recorder', ['ui-rangeSlider', 'angularSpinner', 'xeditable', 'angulartics']);
  player2.recorder.run(function(editableOptions) {
    return editableOptions.theme = 'bs3';
  });
  
  player2.recorder.controller('similarityIndex',['$scope','$rootScope', function($scope,$rootScope){
      $rootScope.$on('cosSimilarity',function(e,args){
        
        
        
        //if(framecounter%chkInterval == 0){
          console.log(args);
          args[0] *= 100;
          if(args[0] >= criteria){
            //$('#similarityChecker').css("border","5px ridge green");
            nodes.push(previousFrame);
            //previousFrame = cmp2; //current frame
          }
          //framecounter = 0;
        //}
        
      });
    }]);
  

  player2.player = function() {
    return player2.controller.plugins['playback'].player;
  };

}).call(this);
