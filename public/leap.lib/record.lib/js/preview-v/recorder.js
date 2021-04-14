(function() {

  player2.recorder = angular.module('Recorder', ['ui-rangeSlider', 'angularSpinner', 'xeditable', 'angulartics']);
  player2.recorder.run(function(editableOptions) {
    return editableOptions.theme = 'bs3';
  });
  
  player2.recorder.controller('similarityIndex',['$scope','$rootScope', function($scope,$rootScope){
      $rootScope.$on('cosSimilarity',function(e,args){

        console.log(args[1]);
        console.log(args[0]);
        args[0] *= 100;
        if(args[0] > 98){
             $('#similarityChecker').css("border","5px ridge green");
        
        }else{
          $('#similarityChecker').css("border","5px ridge red");
        }


      });
    }]);
  

  player2.player = function() {
    return player2.controller.plugins['playback'].player;
  };

}).call(this);
