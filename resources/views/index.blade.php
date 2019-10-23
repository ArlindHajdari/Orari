@extends('layouts.master')
@section('title')
    Orari
@stop
@section('body')
    <div class="row">
        <div class="col-md-12 col-xs-12 col-sm-12">
            @if(Sentinel::getUser()->roles()->first()->slug == 'admin')
                @if($users->count() <> $faculties->count())
                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                        </button>
                        <strong>Lajmërim!</strong> Ju lutem regjistroni të gjithë dekanet
                    </div>

                @endif

                <div class="row tile_count">
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-graduation-cap"></i> Dekanët</span>
                        <div class="count">{{$users->count()}}</div>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-university"></i> Fakultetet</span>
                        <div class="count">{{$faculties->count()}}</div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-server"></i> Departamentet</span>
                        <div class="count">{{$departments->count()}}</div>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-clock-o"></i> Sallat</span>
                        <div class="count">{{$halls->count()}}</div>
                    </div>

                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-book"></i> Lëndët</span>
                        <div class="count">{{$subjects->count()}}</div>
                    </div>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <span class="count_top"><i class="fa fa-graduation-cap"></i> Mësimdhënës</span>
                        <div class="count">{{$users2->count()}}</div>
                        {{--<span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>34% </i> From last Week</span>--}}
                    </div>

                </div>

                {!! Charts::assets() !!}
                <center>
                    {!! $chart2->render() !!}
                </center>

            @elseif(explode('_',Sentinel::getUser()->roles()->first()->slug)[0] == 'dekan')

                @if($disponueshmeria->count() == 0)

                    <div class="alert alert-danger alert-dismissible fade in" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                        </button>
                        <strong>Lajmërim!</strong> Ju lutem caktoni disponueshmërinë
                    </div>

                @endif

                <div class="row tile_count">

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-university"></i></div>
                            <div class="count">{{$departments2->count()}}</div>
                            <h3>Departamente</h3>
                        </div>
                    </div>

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-book"></i></div>
                            <div class="count">{{$subjects2->count()}}</div>
                            <h3>Lëndë</h3>
                        </div>
                    </div>

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-users"></i></div>
                            <div class="count">{{$cps->count()}}</div>
                            <h3>Mësimdhënës-Lëndë</h3>
                        </div>
                    </div>

                    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <div class="tile-stats">
                            <div class="icon"><i class="fa fa-clock-o"></i></div>
                            <div class="count">{{$halls2->count()}}</div>
                            <h3>Salla</h3>
                        </div>
                    </div>

                </div>

                {!! Charts::assets() !!}
                <center>
                    {!! $chart->render() !!}
                </center>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="dashboard_graph">

                        <div class="row x_title">
                            <div class="col-md-6">
                                <h3>Network Activities
                                    <small>Graph title sub-title</small>
                                </h3>
                            </div>
                            <div class="col-md-6">
                                <div id="reportrange" class="pull-right"
                                     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <span>May 20, 2017 - June 18, 2017</span> <b class="caret"></b>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <div id="chart_plot_01" class="demo-placeholder"
                                 style="padding: 0px; position: relative;">
                                <canvas class="flot-base"
                                        style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 970px; height: 280px;"
                                        width="970" height="280"></canvas>
                                <div class="flot-text"
                                     style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
                                    <div class="flot-x-axis flot-x1-axis xAxis x1Axis"
                                         style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                                        <div style="position: absolute; max-width: 121px; top: 264px; left: 14px; text-align: center;"
                                             class="flot-tick-label tickLabel">Jan 01
                                        </div>
                                        <div style="position: absolute; max-width: 121px; top: 264px; left: 169px; text-align: center;"
                                             class="flot-tick-label tickLabel">Jan 02
                                        </div>
                                        <div style="position: absolute; max-width: 121px; top: 264px; left: 324px; text-align: center;"
                                             class="flot-tick-label tickLabel">Jan 03
                                        </div>
                                        <div style="position: absolute; max-width: 121px; top: 264px; left: 479px; text-align: center;"
                                             class="flot-tick-label tickLabel">Jan 04
                                        </div>
                                        <div style="position: absolute; max-width: 121px; top: 264px; left: 634px; text-align: center;"
                                             class="flot-tick-label tickLabel">Jan 05
                                        </div>
                                        <div style="position: absolute; max-width: 121px; top: 264px; left: 789px; text-align: center;"
                                             class="flot-tick-label tickLabel">Jan 06
                                        </div>
                                    </div>
                                    <div class="flot-y-axis flot-y1-axis yAxis y1Axis"
                                         style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                                        <div style="position: absolute; top: 252px; left: 13px; text-align: right;"
                                             class="flot-tick-label tickLabel">0
                                        </div>
                                        <div style="position: absolute; top: 232px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">10
                                        </div>
                                        <div style="position: absolute; top: 213px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">20
                                        </div>
                                        <div style="position: absolute; top: 194px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">30
                                        </div>
                                        <div style="position: absolute; top: 174px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">40
                                        </div>
                                        <div style="position: absolute; top: 155px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">50
                                        </div>
                                        <div style="position: absolute; top: 136px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">60
                                        </div>
                                        <div style="position: absolute; top: 116px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">70
                                        </div>
                                        <div style="position: absolute; top: 97px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">80
                                        </div>
                                        <div style="position: absolute; top: 78px; left: 7px; text-align: right;"
                                             class="flot-tick-label tickLabel">90
                                        </div>
                                        <div style="position: absolute; top: 58px; left: 1px; text-align: right;"
                                             class="flot-tick-label tickLabel">100
                                        </div>
                                        <div style="position: absolute; top: 39px; left: 2px; text-align: right;"
                                             class="flot-tick-label tickLabel">110
                                        </div>
                                        <div style="position: absolute; top: 20px; left: 1px; text-align: right;"
                                             class="flot-tick-label tickLabel">120
                                        </div>
                                        <div style="position: absolute; top: 1px; left: 1px; text-align: right;"
                                             class="flot-tick-label tickLabel">130
                                        </div>
                                    </div>
                                </div>
                                <canvas class="flot-overlay"
                                        style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 970px; height: 280px;"
                                        width="970" height="280"></canvas>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
                            <div class="x_title">
                                <h2>Top Campaign Performance</h2>
                                <div class="clearfix"></div>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-6">
                                <div>
                                    <p>Facebook Campaign</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="80" style="width: 80%;"
                                                 aria-valuenow="79"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p>Twitter Campaign</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="60" style="width: 60%;"
                                                 aria-valuenow="59"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-6">
                                <div>
                                    <p>Conventional Media</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="40" style="width: 40%;"
                                                 aria-valuenow="39"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p>Bill boards</p>
                                    <div class="">
                                        <div class="progress progress_sm" style="width: 76%;">
                                            <div class="progress-bar bg-green" role="progressbar"
                                                 data-transitiongoal="50" style="width: 50%;"
                                                 aria-valuenow="49"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
        </div>

        @else

            @if($disponueshmeria->count() == 0)

                <div class="alert alert-danger alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">×</span>
                    </button>
                    <strong>Lajmërim!</strong> Ju lutem caktoni disponueshmërinë
                </div>

            @endif

        @endif
    </div>
    </div>
@stop