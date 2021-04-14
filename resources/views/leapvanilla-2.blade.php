@extends('masterLeapLab')
@section('header-content')
  <script type = "text/javascript">
    var player2 = {player:null,recorder:null,controller:null};
  </script>

  <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/vanilla-v/recorder.js') }}"></script>
  <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/vanilla-v/controls.js') }}"></script>
  <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/vanilla-v/data-collection.js') }}"></script>
  <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/vanilla-v/metadata.js') }}"></script>
  <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/vanilla-v/player.js') }}"></script>

  <script type = "text/javascript" src = "{{ URL::asset('js/thesisFunctions.js') }}"/></script>
@stop
@section('body-content')
  <div id="dvPlayer" >    
      <div id="dcPlayer" data-ng-controller="DataCollection" ng-show="mode != 'recording' && mode != 'off'">          
      </div>
      <div id="controlsPlayer" data-ng-controller="Controls">
        
      </div>
  </div>
  <div id="dvRecorder" >    

      <!-- For the time being, this controller manages recordings which get used -->
      <div id="dcRecorder" data-ng-controller="DataCollection" ng-show="mode != 'recording' && mode != 'off'">
        
      </div>
      
      </div>
      <div id="controls" data-ng-controller="Controls">
        <div id="range"
             pin-handle=""
             range-slider min="0" max="recordingLength()" model-min="leftHandlePosition" model-max="rightHandlePosition"
             ng-show="mode == 'crop' || mode == 'playback'"></div>

        <div class="btn-group btn-group-lg dropup">

          <button type="button" class="btn btn-default" ng-click="record()" ng-class="{active: mode=='record'}" id="record">

            <i class="glyphicon glyphicon-record" ng-class="{orange: recordPending(), red: recording()}"></i>

            <span class="hotkey">R</span>ecord

          </button>

          <button type="button" class="btn btn-default" ng-click="crop()" ng-class="{active: mode=='crop'}" ng-disabled="canPlayBack()" id="crop">

            <span style="margin-top: -2px; display: inline-block; vertical-align: top;">[ ]</span>

            <span class="hotkey">C</span>rop

          </button>

          <button style="text-align: left" type="button" class="btn btn-default" ng-click="playback()" ng-class="{active: mode=='playback'}" ng-disabled="canPlayBack()">

            <span ng-show="player().loading()" us-spinner="{radius:4, width:2, length: 4, left: '20px'}" style="width: 18px; display: inline-block;"></span>

            <span ng-show="!player().loading() && !pauseOnPlaybackButtonClick()">&#9654;</span>

            <span ng-show="!player().loading() && pauseOnPlaybackButtonClick()"><i class="glyphicon glyphicon-pause"></i></span>

            <span class="hotkey">P</span>layback

          </button>

        </div>

        <div class="btn-group btn-group-lg dropup" style="text-align: left;">
          <button type="button" class="btn btn-default" ng-click="save()">Download</button>
        </div>

        <div class="pull-right">
          <a href="#" onclick="$('#helpModal').modal('toggle')">
            <!-- todo: should change href to help and use angular router.. -->
            <i class="glyphicon glyphicon-info-sign help-modal-control"></i>
          </a>
        </div>

      </div>
  </div>
  <script type="text/javascript">
    var dvRecorder = document.getElementById("dvRecorder");
    angular.element(document).ready(function() {
      //angular.bootstrap(dvPlayer, ['Player']);
      angular.bootstrap(dvRecorder, ['Recorder']);
    });
  </script>
@stop
