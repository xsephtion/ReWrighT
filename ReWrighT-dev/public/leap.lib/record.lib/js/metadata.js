(function() {
  
  player1.recorder.controller('Metadata', [
    '$scope', function($scope) {
      $scope.stripKeycodes = function(e) {
        return e.stopPropagation();
      };
      return player1.controller.on('playback.recordingSet', function(player) {
        return $scope.metadata = player.metadata;
      });
    }
  ]);
  
  player2.recorder.controller('Metadata', [
    '$scope', function($scope) {
      $scope.stripKeycodes = function(e) {
        return e.stopPropagation();
      };
      return player2.controller.on('playback.recordingSet', function(player) {
        return $scope.metadata = player.metadata;
      });
    }
  ]);
}).call(this);
