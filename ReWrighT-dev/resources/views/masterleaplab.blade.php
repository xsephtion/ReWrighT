<html> 
  <head>
    <title>DOM Visualizer - Leap</title>
    <!--Local-->
    <!--script type = "text/javascript" src = "{{ URL::asset('js/jquery-2.1.1.min.js') }}"/></script-->
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/jquery-2.1.0.min.js') }}"/></script>

    <!--link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet"-->
    <link rel="stylesheet" href="{{ URL::asset('leap.lib/css/lib/angular.rangeSlider.css')}}">

    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/jquery-2.1.0.min.js') }}"></script>
    <!--script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script-->
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/FileSaver.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/spin.min.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/angular.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/angular.rangeSlider.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/angular-spinner.min.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/angulartics.min.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/angulartics-ga.min.js') }}"></script>

    <link href="{{ URL::asset('leap.lib/bower_components/angular-xeditable/dist/css/xeditable.css') }}" rel="stylesheet">
    <script type = "text/javascript"  src = "{{ URL::asset('leap.lib/bower_components/angular-xeditable/dist/js/xeditable.js') }}"></script>

    <link href="{{ URL::asset('leap.lib/bower_components/highlightjs/styles/default.css') }}" rel="stylesheet">
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/bower_components/highlightjs/highlight.pack.js') }}"></script>

    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/three.min.js') }}"></script>
    <script type = "text/javascript"  src = "{{ URL::asset('leap.lib/record.lib/js/lib/TrackballControls.js') }}"></script>

    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/leap-0.6.4.min.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/leap-plugins-0.1.6.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/js/lib/leap.rigged-hand-0.1.7.js') }}"></script>
    <script type = "text/javascript" src = "{{ URL::asset('leap.lib/record.lib/build/leap.playback-0.2.1.js') }}"></script>
    
    @yield('header-content')

    <link rel="stylesheet" href="{{ URL::asset('leap.lib/css/main.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('leap.lib/css/controls.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('leap.lib/css/data-collection.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('leap.lib/css/similarityChecker.css') }}">
  </head>
  <body>
        <!--Local-->
       
        <script type = "text/javascript" src = "{{ URL::asset('js/materialize.js') }}"/></script>
    @yield('body-content')
  </body>
</html>
