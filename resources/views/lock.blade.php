<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <title>XALFA</title>

    <link href="{{asset('bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('animate.css/animate.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css">
    <!-- Custom Theme Style -->
    <link href="{{asset('css/custom.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('node_modules/bootstrap3-dialog/dist/css/bootstrap-dialog.css')}}">
    <script src="{{asset('jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('node_modules/bootstrap3-dialog/dist/js/bootstrap-dialog.js')}}"></script>
    <script src="{{asset('js/lock.js')}}"></script>
    <style>
        @charset "utf-8";


        @import url//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css);



        div.main{
            background: #0264d6; /* Old browsers */
            background: -moz-radial-gradient(center, ellipse cover,  #0264d6 1%, #1c2b5a 100%); /* FF3.6+ */
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(1%,#0264d6), color-stop(100%,#1c2b5a)); /* Chrome,Safari4+ */
            background: -webkit-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* Chrome10+,Safari5.1+ */
            background: -o-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* Opera 12+ */
            background: -ms-radial-gradient(center, ellipse cover,  #0264d6 1%,#1c2b5a 100%); /* IE10+ */
            background: radial-gradient(ellipse at center,  #0264d6 1%,#1c2b5a 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0264d6', endColorstr='#1c2b5a',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
            height:calc(100vh);
            width:100%;
        }

        [class*="fontawesome-"]:before {
            font-family: 'FontAwesome', sans-serif;
        }

        /* ---------- GENERAL ---------- */

        * {
            box-sizing: border-box;
            margin:0px auto;

        &:before,
        &:after {
             box-sizing: border-box;
         }

        }

        body {

            color: #606468;
            font: 87.5%/1.5em 'Open Sans', sans-serif;
            margin: 0;
        }

        a {
            color: #eee;
            font-size: 12px;
            text-decoration: none;
        }

        h2{
            color: white;
        }
        a:hover {
            text-decoration: none;
            color: black;
        }

        input {
            border: none;
            font-family: 'Open Sans', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5em;
            padding: 0;
            -webkit-appearance: none;
        }

        p {
            line-height: 1.5em;
        }

        .clearfix {
            *zoom: 1;

        &:before,
        &:after {
             content: ' ';
             display: table;
         }

        &:after {
             clear: both;
         }

        }

        .container {
            left: 50%;
            position: fixed;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        /* ---------- LOGIN ---------- */

        #login form{
            width: 250px;
        }
        #login, .logo{
            display:inline-block;
        }
        .logo{
            color:#fff;
            font-size:50px;
            line-height: 125px;
        }

        #login form span.fa {
            background-color: #fff;
            border-radius: 3px 0px 0px 3px;
            color: #000;
            display: block;
            float: left;
            height: 50px;
            font-size:24px;
            line-height: 50px;
            text-align: center;
            width: 50px;
        }

        #login form input {
            height: 50px;
        }
        fieldset{
            padding:0;
            border:0;
            margin: 0;

        }
        #login form input[type="text"], input[type="password"] {
            background-color: #fff;
            border-radius: 0px 3px 3px 0px;
            color: #000;
            margin-bottom: 1em;
            padding: 0 16px;
            width: 200px;
        }

        #login form input[type="submit"] {
            border-radius: 3px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            background-color: #000000;
            color: #eee;
            font-weight: bold;
            /* margin-bottom: 2em; */
            text-transform: uppercase;
            padding: 5px 10px;
            height: 30px;
        }

        #login form input[type="submit"]:hover {
            background-color: #fff;
            color:#000;
            border-color: #000;
        }

        #login > p {
            text-align: center;
        }

        #login > p span {
            padding-left: 5px;
        }
        .middle {
            display: flex;
            width: 600px;
        }
    </style>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>
<div class="main">
    <div class="container">
        <center>
            <div class="middle">
                <div id="login">
                    <fieldset class="clearfix">
                    {{FORM::open(['id'=>'lockForm','url'=>'unlock','method'=>'post'])}}
                    <div class="logo">
                        <img src="{{$photo}}" alt="" class="img-circle" height=200 width=200 />
                    </div>
                    <h2>{{$name}}</h2>
                    <hr>
                    <p>
                        <span class="fa fa-lock"></span>
                        {{FORM::password('password',['placeholder'=>'Fjalëkalimi','class'=>'form-control','required'])}}
                    </p>
                    <div>
                        {{FORM::hidden('log_id',$log_id)}}
                        <a href="{{url('login')}}"><span class="glyphicon glyphicon-chevron-left">Kthehu</span></a>
                        <span style="width:30%; text-align:right; display: inline-block;">
                            {{FORM::submit('Kyçu')}}
                        </span>
                    </div>
                    </fieldset>
                    <div class="clearfix"></div>
                    {{FORM::close()}}
                    <div class="clearfix"></div>

                </div>
            </div>
        </center>
    </div>
</div>
</body>
</html>