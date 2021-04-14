(function() {
  var rootApp = angular.module('rootApp', ['Recorder']);

  player2.recorder = angular.module('Recorder', ['ui-rangeSlider', 'angularSpinner', 'xeditable', 'angulartics']);
  player2.recorder.run(function(editableOptions) {
    return editableOptions.theme = 'bs3';
  });
  

  player2.player = function() {
    return player2.controller.plugins['playback'].player;
  };

}).call(this);
