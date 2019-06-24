<!DOCTYPE html>
<html>
    <head>
        <title>ReWrighT</title>
        <!--Font/Icon-->
        <!--link rel = "stylesheet" type = "text/css" href="http://fonts.googleapis.com/icon?family=Material+Icons"/-->
        
        <link rel = "stylesheet" type = "text/css" href="{{ URL::asset('css/materialize-fonts.min.css') }}"/><!--local copy-->
        <!--Local-->
        <link rel = "stylesheet" type = "text/css" href = "{{ URL::asset('css/materialize.min.css') }}" media="screen,projection"/>
        <link rel = "stylesheet" type = "text/css" href = "{{ URL::asset('css/functions.css') }}"/>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        
    </head>
    <body>
        <!--Local-->
        <script type = "text/javascript" src = "{{ URL::asset('js/jquery-2.1.1.min.js') }}"/></script><!--local copy-->
        <script type = "text/javascript" src = "{{ URL::asset('js/materialize.js') }}"/></script>
        <!--Online-->
        <!--    
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script type = "text/javascript" src = "{{ URL::asset('js/materialize.min.js') }}"/></script>    
        -->
        @yield('content')
        
        <script type = "text/javascript" src = "{{ URL::asset('js/api.js') }}"/></script>
        <script type = "text/javascript" src = "{{ URL::asset('js/functionsAdmin.js') }}"/></script>
        
        
    </body>
</html>

