<!doctype html>
<html>
<head>
    <title>@yield('title')</title>
    @include('layouts.head')
    @yield('other')
</head>
<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            @include('layouts.header')
            <div class="right_col" role="main" style="min-height: 1704px;">
                @yield('body')
            </div>
        </div>
    </div>
    @include('layouts.footer')
    @include('layouts.scripts')
</body>
</html>