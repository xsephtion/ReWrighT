<html>
  <head>
    <title>DOM Visualizer - Leap</title>
    <script src="{{ URL::asset('js/leapLib/leap-0.6.4.js.js') }}"></script>
    <script>
      function moveFinger(Finger, posX, posY, posZ) {
        Finger.style.webkitTransform = "translate3d("+posX+"px, "+posY+"px, "+posZ+"px)";
      }

      function moveSphere(Sphere, posX, posY, posZ, rotX, rotY, rotZ) {
        Sphere.style.webkitTransform = Sphere.style.mozTransform =
        Sphere.style.transform = "translateX("+posX+"px) translateY("+posY+"px) translateZ("+posZ+"px) rotateX("+rotX+"deg) rotateY(0deg) rotateZ(0deg)";
      }

      var fingers = {};
      var spheres = {};
      Leap.loop(function(frame) {
        var seenFingers = {};
        var handIds = {};
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
          if (!sphere) {
            var sphereDiv = document.getElementById("sphere").cloneNode(true);
            sphereDiv.setAttribute('id',hand.id);
            sphereDiv.style.backgroundColor='#'+Math.floor(Math.random()*16777215).toString(16);
            document.getElementById('scene').appendChild(sphereDiv);
            spheres[hand.id] = hand.id;
          } else {
            var sphereDiv =  document.getElementById(hand.id);
            if (typeof(sphereDiv) != 'undefined' && sphereDiv != null) {
              moveSphere(sphereDiv, posX, posY, posZ, rotX, rotY, rotZ);
            }
          }
          handIds[hand.id] = true;
        }
        for (handId in spheres) {
          if (!handIds[handId]) {
            var sphereDiv =  document.getElementById(spheres[handId]);
            sphereDiv.parentNode.removeChild(sphereDiv);
            delete spheres[handId];
          }
        }

        for (var pointableId = 0, pointableCount = frame.pointables.length; pointableId != pointableCount; pointableId++) {
          var pointable = frame.pointables[pointableId];
          var newFinger = false;
          if (pointable.finger) {
            if (!fingers[pointable.id]) {
              fingers[pointable.id] = [];
              newFinger = true;
            }

            for (var partId = 0, length; partId != 4; partId++) {
              var posX = (pointable.positions[partId][0]*3);
              var posY = (pointable.positions[partId][2]*3)-200;
              var posZ = (pointable.positions[partId][1]*3)-400;

              var id = pointable.id+'_'+partId;

              var finger = fingers[id];
              if (newFinger) {
                var fingerDiv = document.getElementById("finger").cloneNode(true);
                fingerDiv.setAttribute('id', id);
                fingerDiv.style.backgroundColor='#'+Math.floor(pointable.type*500).toString(16);
                document.getElementById('scene').appendChild(fingerDiv);
                fingers[pointable.id].push(id);
              } else  {
                var fingerDiv =  document.getElementById(id);
                if (typeof(fingerDiv) != 'undefined' && fingerDiv != null) {
                  moveFinger(fingerDiv, posX, posY, posZ);
                }
              }
              seenFingers[pointable.id] = true;
            }

            //var dirX = -(pointable.direction[1]*90);
            //var dirY = -(pointable.direction[2]*90);
            //var dirZ = (pointable.direction[0]*90);
          }
        }
        for (var fingerId in fingers) {
          if (!seenFingers[fingerId]) {
            var ids = fingers[fingerId];
            for (var index in ids) {
              var fingerDiv =  document.getElementById(ids[index]);
              fingerDiv.parentNode.removeChild(fingerDiv);
            }
            delete fingers[fingerId];
          }
        }
        document.getElementById('showHands').addEventListener('mousedown', function() {
          document.getElementById('app').setAttribute('class','show-hands');
        }, false);
        document.getElementById('hideHands').addEventListener('mousedown', function() {
          document.getElementById('app').setAttribute('class','');
        }, false);
      });

    </script>
    <style>
      *,*:before,*:after {
        margin: 0;
        padding: 0;
        border: 0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
      }
      button {
        padding: .5em;
      }
      #app {
        position: absolute;
        width: 100%;
        height: 100%;
        font-size: 200%;
        overflow: hidden;
        background-color: #101010;
        -webkit-perspective: 1000;
      }
      #scene,
      #scene:before {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 40em;
        height: 40em;
        margin: -20em 0 0 -20em;
        border: 4px solid #A0A0A0;
        background-color: rgba(255,255,255,.1);
        background-image:
        -webkit-linear-gradient(rgba(255,255,255,.4) .1em, transparent .1em),
        -webkit-linear-gradient(0deg, rgba(255,255,255,.4) .1em, transparent .1em),
        -webkit-linear-gradient(rgba(255,255,255,.3) .05em, transparent .05em),
        -webkit-linear-gradient(0deg, rgba(255,255,255,.3) .05em, transparent .05em);
        background-size: 5em 5em, 5em 5em, 1em 1em, 1em 1em;
        background-position: -.1em -.1em, -.1em -.1em, -.05em -.05em, -.05em -.05em;
        transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d;
        transform: rotateX(75deg);
        -moz-transform: rotateX(75deg);
        -webkit-transform: rotateX(75deg);
      }
      #scene {
        transform: rotateX(75deg);
        -moz-transform: rotateX(75deg);
        -webkit-transform: rotateX(75deg);
      }
      #scene:before {
        content: '';
        transform: rotateX(90deg) translateZ(19.5em) translateY(20em);
        -moz-transform: rotateX(90deg) translateZ(19.5em) translateY(20em);
        -webkit-transform: rotateX(90deg) translateZ(19.5em) translateY(20em);
      }
      .cube {
        background-color: red;
        transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        -webkit-transform-style: preserve-3d;
        transform: translateX(19.5em) translateY(19.5em) translateZ(0em);
        -moz-transform: translateX(19.5em) translateY(19.5em) translateZ(0em);
        -webkit-transform: translateX(19.5em) translateY(19.5em) translateZ(0em);
      }
      .finger,
      .sphere {
        position: absolute;
        left: 50%;
        top: 50%;
        width: 1em;
        height: 1em;
        margin: -.5em 0 0 -.5em;
        -webkit-transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        transform-style: preserve-3d;
        -webkit-transform: translateX(14.5em) translateY(14.5em) translateZ(0);
        -moz-transform: translateX(14.5em) translateY(14.5em) translateZ(0);
        transform: translateX(14.5em) translateY(14.5em) translateZ(0);
      }

      .finger {
        opacity: .8;
      }

      .sphere {
        opacity: .3;
        display: none;
        font-size: 100px;
      }

      .show-hands .sphere {
        display: block;
      }

      .face {
        position: absolute;
        width: 1em;
        height: 1em;
        background-color: inherit;
        -webkit-transform-style: preserve-3d;
        -moz-transform-style: preserve-3d;
        transform-style: preserve-3d;
        -webkit-transform-origin: 0 0;
        -moz-transform-origin: 0 0;
        transform-origin: 0 0;
        -webkit-box-shadow: inset 0 0 0 1px rgba(255,255,255,.9);
        -moz-box-shadow: inset 0 0 0 1px rgba(255,255,255,.9);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.9);
      }
      .cube .face.tp {
        -webkit-transform: translateZ(1em);
        -moz-transform: translateZ(1em);
        transform: translateZ(1em);
      }
      .cube .face.ft {
        -webkit-transform: rotateX(90deg) translateZ(-1em);
        -moz-transform: rotateX(90deg) translateZ(-1em);
        transform: rotateX(90deg) translateZ(-1em);
      }
      .cube .face.bk {
        -webkit-transform: rotateX(90deg);
        -moz-transform: rotateX(90deg);
        transform: rotateX(90deg);
      }
      .cube .face.lt {
        -webkit-transform: rotateY(90deg) translateX(-1em);
        -moz-transform: rotateY(90deg) translateX(-1em);
        transform: rotateY(90deg) translateX(-1em);
      }
      .cube .face.rt {
        -webkit-transform: rotateY(90deg) translateX(-1em) translateZ(1em);
        -moz-transform: rotateY(90deg) translateX(-1em) translateZ(1em);
        transform: rotateY(90deg) translateX(-1em) translateZ(1em);
      }

      .finger .face.tp {
        -webkit-transform: translateZ(1em);
        -moz-transform: translateZ(1em);
        transform: translateZ(1em);
        height: 3em;
      }
      .finger .face.ft {
        -webkit-transform: rotateX(90deg) translateZ(-3em);
        -moz-transform: rotateX(90deg) translateZ(-3em);
        transform: rotateX(90deg) translateZ(-3em);
      }
      .finger .face.bk {
        -webkit-transform: rotateX(90deg);
        -moz-transform: rotateX(90deg);
        transform: rotateX(90deg);
      }
      .finger .face.lt {
        -webkit-transform: rotateY(90deg) translateX(-1em);
        -moz-transform: rotateY(90deg) translateX(-1em);
        transform: rotateY(90deg) translateX(-1em);
        height: 3em;
      }
      .finger .face.rt {
        -webkit-transform: rotateY(90deg) translateX(-1em) translateZ(1em);
        -moz-transform: rotateY(90deg) translateX(-1em) translateZ(1em);
        transform: rotateY(90deg) translateX(-1em) translateZ(1em);
        height: 3em;
      }

    </style>
  </head>
  <body>
    <div id="app" class="show-hands">
      <button id="showHands">Show Hands</button>
      <button id="hideHands">hide Hands</button>
      <div id="scene">
        <div id="cube" class="cube">
          <div class="face tp"></div>
          <div class="face lt"></div>
          <div class="face rt"></div>
          <div class="face ft"></div>
          <div class="face bk"></div>
        </div>
        <div id="finger" class="cube finger">
          <div class="face tp"></div>
          <div class="face lt"></div>
          <div class="face rt"></div>
          <div class="face ft"></div>
          <div class="face bk"></div>
        </div>
        <div id="sphere" class="cube sphere">
          <div class="face tp"></div>
          <div class="face lt"></div>
          <div class="face rt"></div>
          <div class="face ft"></div>
          <div class="face bk"></div>
        </div>
      </div>
    </div>
  </body>
</html>
