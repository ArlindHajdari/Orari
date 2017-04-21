<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{url('/')}}" class="site_title"><i class="fa fa-cubes"></i> <span>Orari</span></a>
        </div>
        <div class="clearfix"></div>
        <!-- menu profile quick info -->
        <div class="profile clearfix">
            <div class="profile_pic">
                <img src="{{asset(Sentinel::getUser()->photo)}}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                @if(Sentinel::check())
                    <span>Welcome,</span>
                    <h2>{{Sentinel::getUser()->first_name}} {{Sentinel::getUser()->last_name}}</h2>
                @endif
            </div>
        </div>
        <!-- /menu profile quick info -->

        <br />

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <li><a><i class="fa fa-cogs"></i>Menaxho<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(Sentinel::getUser()->roles()->first()->slug == 'admin')
                                <li><a href="{{ url('FacultyPanel') }}">Fakultetet</a>
                                </li>
                                <li><a href="{{ url('departamentPanel') }}">Departamentet</a>
                                </li>
                                <li><a href="{{ url('dekanet') }}">Dekanët</a>
                                </li>
                                <li><a href="{{ url('LendetPanel') }}">Lëndët</a>
                                </li>
                                <li><a href="{{ url('sallat') }}">Sallë</a>
                                </li>
                                <li><a href="{{ url('mesimdhenesit') }}">Mësimdhënësit</a>
                                </li>
                            @elseif(explode('_',Sentinel::getUser()->roles()->first()->slug)[0] == 'dekan')
                                <li><a href="{{ url('proflende') }}">Profesor-Lëndë</a>
                                </li>
                            @else
                                <li><a href="{{ url('disponueshmeria') }}">Disponueshmëria</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                </ul>
            <div class="menu_section">
                <ul class="nav side-menu">
                    @if(explode('_',Sentinel::getUser()->roles()->first()->slug)[0] == 'dekan')
                        <li>
                            <a href="{{url('OrariPanel')}}"><i class="fa fa-calendar-plus-o">
                                </i> Orari<span class="label label-success pull-right">Coming Soon</span>
                            </a>
                        </li>
                    @endif
                    {{--<li><a><i class="fa fa-windows"></i> Extras <span class="fa fa-chevron-down"></span></a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a href="page_403.html">403 Error</a></li>--}}
                            {{--<li><a href="page_404.html">404 Error</a></li>--}}
                            {{--<li><a href="page_500.html">500 Error</a></li>--}}
                            {{--<li><a href="plain_page.html">Plain Page</a></li>--}}
                            {{--<li><a href="login.html">Login Page</a></li>--}}
                            {{--<li><a href="pricing_tables.html">Pricing Tables</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li><a><i class="fa fa-sitemap"></i> Multilevel Menu <span class="fa fa-chevron-down"></span></a>--}}
                        {{--<ul class="nav child_menu">--}}
                            {{--<li><a href="#level1_1">Level One</a>--}}
                            {{--<li><a>Level One<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                    {{--<li class="sub_menu"><a href="level2.html">Level Two</a>--}}
                                    {{--</li>--}}
                                    {{--<li><a href="#level2_1">Level Two</a>--}}
                                    {{--</li>--}}
                                    {{--<li><a href="#level2_2">Level Two</a>--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</li>--}}
                            {{--<li><a href="#level1_2">Level One</a>--}}
                            {{--</li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li><a href="javascript:void(0)"><i class="fa fa-laptop"></i> Landing Page <span class="label label-success pull-right">Coming Soon</span></a></li>--}}
                </ul>
            </div>
            </div>
        </div>
        <!-- /sidebar menu -->

        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen" id="full_screen">
                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            {{FORM::open(['url'=>'logout','id'=>'logout-form'])}}
            <a href="#" onclick="document.getElementById('logout-form').submit()">
            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
            {{FORM::close()}}
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            {{FORM::open(['url'=>'logout','id'=>'logout-forma'])}}
            <ul class="nav navbar-nav navbar-right">
                <li class="" id="user_info">
                    <a href="#" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{Sentinel::getUser()->photo}}" alt="Foto">
                        @if(Sentinel::check())
                            {{Sentinel::getUser()->first_name}} {{Sentinel::getUser()->last_name}}
                        @endif
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="#">
                                <span>Profili</span>
                            </a>
                        </li>
                        @if(explode('_',Sentinel::getUser()->roles()->first()->slug)[0] == 'dekan')
                            <li><a href="{{url('kontakti')}}">Kontakto</a></li>
                        @endif
                        <li>
                            <a href="#" onclick="document.getElementById('logout-forma').submit()">
                                <i class="fa fa-sign-out pull-right"></i>
                                Ç'kyçu
                            </a>
                        </li>
                    </ul>
                </li>
                {{FORM::close()}}
                {{--<li role="presentation" class="dropdown">--}}
                    {{--<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">--}}
                        {{--<i class="fa fa-envelope-o"></i>--}}
                        {{--<span class="badge bg-green">6</span>--}}
                    {{--</a>--}}
                    {{--<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">--}}
                        {{--<li>--}}
                            {{--<a>--}}
                                {{--<span class="image"><img src="{{asset('images/img.jpg')}}" alt="Profile Image" /></span>--}}
                        {{--<span>--}}
                          {{--<span>John Smith</span>--}}
                          {{--<span class="time">3 mins ago</span>--}}
                        {{--</span>--}}
                        {{--<span class="message">--}}
                          {{--Film festivals used to be do-or-die moments for movie makers. They were where...--}}
                        {{--</span>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a>--}}
                                {{--<span class="image"><img src="{{asset('images/img.jpg')}}" alt="Profile Image" /></span>--}}
                        {{--<span>--}}
                          {{--<span>John Smith</span>--}}
                          {{--<span class="time">3 mins ago</span>--}}
                        {{--</span>--}}
                        {{--<span class="message">--}}
                          {{--Film festivals used to be do-or-die moments for movie makers. They were where...--}}
                        {{--</span>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a>--}}
                                {{--<span class="image"><img src="{{asset('images/img.jpg')}}" alt="Profile Image" /></span>--}}
                        {{--<span>--}}
                          {{--<span>John Smith</span>--}}
                          {{--<span class="time">3 mins ago</span>--}}
                        {{--</span>--}}
                        {{--<span class="message">--}}
                          {{--Film festivals used to be do-or-die moments for movie makers. They were where...--}}
                        {{--</span>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a>--}}
                                {{--<span class="image"><img src="{{asset('images/img.jpg')}}" alt="Profile Image" /></span>--}}
                        {{--<span>--}}
                          {{--<span>John Smith</span>--}}
                          {{--<span class="time">3 mins ago</span>--}}
                        {{--</span>--}}
                        {{--<span class="message">--}}
                          {{--Film festivals used to be do-or-die moments for movie makers. They were where...--}}
                        {{--</span>--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<div class="text-center">--}}
                                {{--<a>--}}
                                    {{--<strong>See All Alerts</strong>--}}
                                    {{--<i class="fa fa-angle-right"></i>--}}
                                {{--</a>--}}
                            {{--</div>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
            </ul>
        </nav>
    </div>
</div>