@extends('masterLeapLab')
@section('header-content')
    <script type="text/javascript">
      var pageid = parseInt({{ $pageid }}); 
      var player2 = {player:null,recorder:null,controller:null};

    </script>
    
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/preview-v/recorder.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/preview-v/controls.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/preview-v/data-collection.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/preview-v/metadata.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/preview-v/player.js') }}"></script>

    <script type = "text/javascript" src = "{{ URL::asset('js/thesisFunctions.js') }}"/></script>
    <link rel = "stylesheet" type = "text/css" href="{{ URL::asset('css/materialize-fonts.min.css') }}"/><!--local copy-->
    <link rel = "stylesheet" type = "text/css" href = "{{ URL::asset('css/materialize.min.css') }}" media="screen,projection"/>
    <script type = "text/javascript" src = "{{ URL::asset('js/materialize.min.js') }}"/></script>
@stop
@section('body-content')
    <div id="dvRecorder" >    

        <!-- For the time being, this controller manages recordings which get used -->
        <div id="dcRecorder" data-ng-controller="DataCollection" ng-show="mode != 'recording' && mode != 'off'">
          
        </div>
        <div id="similarityChecker" data-ng-controller="similarityIndex">
        </div>
        <div id="controls" data-ng-controller="Controls">
          <div id="range"
               pin-handle=""
               range-slider min="0" max="recordingLength()" model-min="leftHandlePosition" model-max="rightHandlePosition"
               ng-show="mode == 'crop' || mode == 'playback'"></div>

          <div class="btn-group btn-group-lg dropup">

            <button style="text-align: left" type="button" class="btn btn-default" ng-click="playback()" ng-class="{active: mode=='playback'}" ng-disabled="canPlayBack()">

              <span ng-show="player().loading()" us-spinner="{radius:4, width:2, length: 4, left: '20px'}" style="width: 18px; display: inline-block;"></span>

              <span ng-show="!player().loading() && !pauseOnPlaybackButtonClick()">&#9654;</span>

              <span ng-show="!player().loading() && pauseOnPlaybackButtonClick()"><i class="glyphicon glyphicon-pause"></i></span>

              <span class="hotkey">P</span>layback

            </button>

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
        angular.bootstrap(dvRecorder, ['Recorder']);
      });
    </script>
@stop
