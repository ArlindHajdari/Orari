<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@yield('page_title')</title>
    @include('layouts.head')
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-12">
                <div class="col-middle">
                    <div class="text-center">
                        <h1 class="error-number">@yield('number')</h1>
                        <h2>@yield('title')</h2>
                        <p>@yield('message')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('fastclick/lib/fastclick.js')}}"></script>
    <script src="{{asset('nprogress/nprogress.js')}}"></script>
    <script src="{{asset('js/custom.min.js')}}"></script>
</body>
</html>