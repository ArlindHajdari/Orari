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
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://code.jquery.com/jquery-3.2.1.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script src="{{asset('js/uploadPhoto.js')}}"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
      <script src="{{asset('js/login.js')}}"></script>
      <style>
          .image-preview-input {
              position: relative;
              overflow: hidden;
              margin: 0px;
              color: #333;
              background-color: #fff;
              border-color: #ccc;
          }
          .image-preview-input input[type=file] {
              position: absolute;
              top: 0;
              right: 0;
              margin: 0;
              padding: 0;
              font-size: 20px;
              cursor: pointer;
              opacity: 0;
              filter: alpha(opacity=0);
          }
          .image-preview-input-title {
              margin-left:2px;
          }
      </style>
  </head>

  <body class="login">
    <div>
      {{--<a class="hiddenanchor" id="signup"></a>--}}
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
              {{FORM::open(['id'=>'loginForm'])}}
              <h1>Login Form</h1>
              <div>
                  {{FORM::text('log_id',null,['class'=>'form-control','required','placeholder'=>'ID ose E-mail'])}}
              </div>
              <div>
                  {{FORM::password('password',['placeholder'=>'Fjalëkalimi','class'=>'form-control','required'])}}
              </div>
              <div>
                  {{FORM::submit('Kyçu',['class'=>'btn btn-default submit'])}}
                    <a class="reset_pass" href="{{url('/')}}">Keni humbur fjalëkalimin?</a>
              </div>

              <div class="clearfix"></div>

              <div class="separator">
                <div class="clearfix"></div>
                <br />

                <div>
                  <h1><i class="fa fa-xalfa"></i> XALFA - Për krijimin e orarit!</h1>
                  <p>©2016 All Rights Reserved. XALFA Inc. Privacy and Terms</p>
                </div>
              </div>
              {{FORM::close()}}
          </section>
        </div>
      </div>
    </div>

  </body>
</html>
