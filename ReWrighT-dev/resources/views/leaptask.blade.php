@extends('masterLeapLab')
@section('header-content')
    <script type="text/javascript">
      var siteUrl = "reWright.test";

      var project_id = <?php if(Auth::user()->users_type == 1){ echo Auth::user()->projects[1]->project_id; }else{ echo Auth::user()->projects[0]->project_id; } ?>;
      var tasks=[];
      var user_info = { type: parseInt({{ Auth::user()->user_types }}),
                        token: "{{csrf_token()}}"

      };
      var pageid = parseInt({{ $pageid }});
      var exer_id = parseInt({{$exer_id}});
      var p_exer_id = parseInt({{$p_exer_id}});
      var player1 = {player:null,recorder:null,controller:null};
      var player2 = {player:null,recorder:null,controller:null};
      var cmp1 = null;
      var cmp2 = null;
      var tempSimilarityResults = {player1frame:{id:null,eArray:null},player2frame:{id:null,eArray:null},similarityIndex:null}; //javascript obj blueprint;
      
      var similarityResult = [];
    </script>
    
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/task-v/similarityResult.js') }}"/></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/task-v/recorder.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/task-v/controls.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/task-v/data-collection.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/task-v/metadata.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/task-v/player.js') }}"></script>

    <script type = "text/javascript" src = "{{ URL::asset('js/thesisFunctions.js') }}"/></script>

    <link rel = "stylesheet" type = "text/css" href="{{ URL::asset('css/materialize-fonts.min.css') }}"/><!--local copy-->
    <link rel = "stylesheet" type = "text/css" href = "{{ URL::asset('css/materialize.min.css') }}" media="screen,projection"/>
    <script type = "text/javascript" src = "{{ URL::asset('js/materialize.min.js') }}"/></script>
    <script type = "text/javascript" src = "{{ URL::asset('js/api.js') }}"/></script>
    
    <script type = "text/javascript" src = "{{ URL::asset('js/taskDetails.js') }}"></script>
    @if (Auth::user()->user_types == 1)
    <script type = "text/javascript" src = "{{ URL::asset('js/notesAPI.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('js/notesDetail.js') }}"></script>
    @endif
@stop
@section('body-content')
  <body>
    <div id="dvInfoPanel" >    
        <div id="dvSidePanel" class="side-nav" style="z-index:1000000;">

          <ul id="sidePanel" class = "collapsible popout">
            <li>
              <div class="collapsible-header active">
                <ul class="collection">
                  <li class="collection-avatar">
                    <i class="material-icons">info</i> Task Details
                  </li>
                </ul>
              </div>
              <div id="genContent" class="collapsible-body">
                <div class="row">
                  <div class="col s12 m12 l12">
                    <div id="div_details">
                      
                    </div>

                    <div id="div_contents">
                      
                    </div>
                  </div>
                </div>
              </div>  
            </li>
            @if(Auth::user()->user_types == 1)
            <li>
              <div class="collapsible-header active">
                <ul class="collection">
                  <li class="collection-avatar">
                    <i class="material-icons">assessment</i> Results
                  </li>
                </ul>
              </div>
              <div id="genContent" class="collapsible-body">
                <table class="centered">
                  <thead>
                    <tr>
                      <th>Generated</th>
                      <th>Adjusted</th>
                    </tr>
                  </thead>
                  <tbody>
                    <td> 
                      @if(!is_null($resScore))
                        {{ $resScore * 100 }}%
                      @else
                        N/A
                      @endif
                    </td>
                    <td> 
                        @if(is_null($adjustedResScore))
                        <input type="text" id="adjustedScore" name="adjustedScore" value = "{{ $adjustedResScore }}">
                        <label for="adjustedScore">Adjust Score</label>
                        <a class="waves-effect waves-red red-text text-darken-3" onclick="submitAdjustedScore();">Post</a>
                        @else
                        {{ $adjustedResScore}}% 
                        @endif
                    </td>
                  </tbody>
                </table>
              </div>
            </li>
            <li>
              <div class="collapsible-header">
                <ul class="collection">
                  <li class="collection-avatar">
                    <i class="material-icons">add</i>Notes
                  </li>
                </ul>
              </div>
              <div class="collapsible-body">
                <div class="row">
                  <div class="col s12 m12 l12">
                    {!! Form::open(['route'=>'postNote','id'=>'postNote','files'=>'true']) !!}
                      {!! Form::hidden('task_exer_data_id',$pageid) !!}
                        
                      <input type="text" id="title" name="title">
                      <label for="title">Subject</label>
                      
                      <div id="addl_post_note">
                        <script type="text/javascript">
                          var notesTextCntr = 1;
                          var notesImageCntr = 0;
                          var notesHighlightCntr = 0;
                        </script>
                        <textarea class="materialize-textarea" id="text[0]" name="text[]" placeholder="What do you want to say?"></textarea>
                      
                      </div>
                    {!! Form::close() !!}
                      <div class="col s12">
                      <br/>
                        <a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-3" data-position="top" data-delay="50" data-tooltip="Add Image"  onclick="upImage();"><i class="material-icons">picture_in_picture</i></a>
                        <a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-2" data-position="top" data-delay="50" data-tooltip="Highlight text" onclick="upHighlight();"><i class="material-icons" href="#upHighlightDisc">"</i></a>
                        <a class="tooltipped waves-effect waves-red btn-flat btn-large white col s4 red-text text-darken-1" data-position="top" data-delay="50" data-tooltip="Add more description" onclick="upDesc();"><i class="material-icons">description</i></a>
                      </div>
                      <a class="modal-action modal-close waves-effect waves-green btn-flat center" onclick="submitNote();">Post</a>
                  </div>
                </div>
              </div>
            </li>
            @endif
          </ul>
        </div>
        <div class="fixed-action-btn" style="top: 50%; right: 0px;">
          <a class="btn-floating btn-large button-collapse" href="#" data-activates="dvSidePanel">
            <i class="large material-icons">chevron_left</i>
          </a>
        </div>
        

  <!-- Modal Structure -->
        
    </div>

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
        <div id="similarityChecker" data-ng-controller="similarityIndex">
        </div>
        <div id="controls" data-ng-controller="Controls">
          <div id="range"
               pin-handle=""
               range-slider min="0" max="recordingLength()" model-min="leftHandlePosition" model-max="rightHandlePosition"
               ng-show="mode == 'crop' || mode == 'playback'"></div>

          <div class="btn-group btn-group-lg dropup">
            @if(Auth::user()->user_types == 2)
            <button type="button" class="btn btn-default" ng-click="record()" ng-class="{active: mode=='record'}" id="record">

              <i class="glyphicon glyphicon-record" ng-class="{orange: recordPending(), red: recording()}"></i>

              <span class="hotkey">R</span>ecord

            </button>

            <button type="button" class="btn btn-default" ng-click="crop()" ng-class="{active: mode=='crop'}" ng-disabled="canPlayBack()" id="crop">

              <span style="margin-top: -2px; display: inline-block; vertical-align: top;">[ ]</span>

              <span class="hotkey">C</span>rop

            </button>
            @endif
            <button style="text-align: left" type="button" class="btn btn-default" ng-click="playback()" ng-class="{active: mode=='playback'}" ng-disabled="canPlayBack()">

              <span ng-show="player().loading()" us-spinner="{radius:4, width:2, length: 4, left: '20px'}" style="width: 18px; display: inline-block;"></span>

              <span ng-show="!player().loading() && !pauseOnPlaybackButtonClick()">&#9654;</span>

              <span ng-show="!player().loading() && pauseOnPlaybackButtonClick()"><i class="glyphicon glyphicon-pause"></i></span>

              <span class="hotkey">P</span>layback

            </button>

          </div>
          @if(Auth::user()->user_types == 2)
          <div class="btn-group btn-group-lg dropup" style="text-align: left;">
            <button type="button" class="btn btn-default" ng-click="submit()">Submit Exercise</button>
            
          </div>
          @endif
          <div class="pull-right">
            <a href="#" onclick="$('#helpModal').modal('toggle')">
              <!-- todo: should change href to help and use angular router.. -->
              <i class="glyphicon glyphicon-info-sign help-modal-control"></i>
            </a>
          </div>

        </div>
    </div>
    
    
    <script type="text/javascript">
      
      var dvPlayer = document.getElementById("dvPlayer");
      var dvRecorder = document.getElementById("dvRecorder");
      angular.element(document).ready(function() {
        angular.bootstrap(dvPlayer, ['Player']);
        angular.bootstrap(dvRecorder, ['Recorder']);
      });
      $(document).ready(function(){
        $(".button-collapse").sideNav({
          menuWidth:window.innerWidth - (window.innerWidth/3),
          edge: 'right'
        });

      });
      /*var resultTotal = (function similarityResultTotal(result){
            var cnt = 0;
            var cmp = player1.player().recording.frameData.length - 1;
            
            for(var i=0; i<result.length;i++){
              if((result[i].player1frame.id !== null && result[i].player2frame.id !== null) && (result[i].similarityIndex !== -1 && result[i].similarityIndex >= 0.97)){//0.9
                cnt++;
              }
            }
            return (cnt/cmp).toFixed(2);
          });*/
    </script>
@stop