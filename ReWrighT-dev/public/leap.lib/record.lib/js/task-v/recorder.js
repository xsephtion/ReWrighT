(function() {
  var rootApp = angular.module('rootApp', ['Player','Recorder']); 


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
        tempSimilarityResults.similarityIndex = args[0];
        similarityResult.push(tempSimilarityResults);
        tempSimilarityResults = {player1frame:{id:null,eArray:null},player2frame:{id:null,eArray:null},similarityIndex:null}; //reset the temp var
        similarityResult.push(tempSimilarityResults);
        //console.log(similarityRes ult);
        //console.log(args[1]);
        //console.log(args[0]);

        args[0] *= 100;
        if(args[0] > 97){
             $('#similarityChecker').css("border","5px ridge green");
        
        }else{
          $('#similarityChecker').css("border","5px ridge red");
        }


      });
      $rootScope.$on('cosSimilarityPlayback',function(e,args){
        //console.table(args);

        args[0] *= 100;
        if(args[0] > 97){
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
