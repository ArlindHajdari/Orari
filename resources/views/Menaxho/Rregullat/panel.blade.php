@extends('layouts.master')
@section('title')
    Orari
@stop

@section('other')
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script src="{{asset('js/settings.js')}}"></script>
@stop

@section('body')
    <!-- Modal Edit-->
    <div class="modal fade" id="editModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ndryshimi</h4>
                </div>
                <div class="modal-body">
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','method'=>'PATCH','id'=>'settings-edit']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Max. orë për profesor',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::number('max_hour_day_professor',null,['class'=>'form-control','id'=>'max_hour_day_professor']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Max. orë për asistent',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::number('max_hour_day_assistant',null,['class'=>'form-control','id'=>'max_hour_day_assistant'])}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-4">
                            {{ FORM::submit('Ndrysho',['class'=>'btn btn-success pull-right']) }}
                        </div>
                    </div>
                </div>
            </div>
            {{ FORM::close() }}
        </div>
    </div>
    <!-- /Modal /Edit-->
    <!-- page content -->
    <div class="page-title">
        <div class="title_left">
            <h3>Xalfa <small>Lista me rregullave</small></h3>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>XALFA</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 17%">Semestri verorë</th>
                            <th style="width: 17%">Semestri dimëror</th>
                            <th style="width: 24%">Maksimum orë në ditë për profesor</th>
                            <th style="width: 30%">Maksimum orë në ditë për asistent</th>
                            <th style="width: 10%">Opsionet</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($data as $value)
                        <tr>
                            <td>{{ $value->start_summer_semester }} - {{$value->end_summer_semester}}</td>
                            <td>{{ $value->start_winter_semester }} - {{$value->end_winter_semester}}</td>
                            <td>{{ $value->max_hour_day_professor }}</td>
                            <td>{{ $value->max_hour_day_assistant }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                        data-id="{{$value->id}}" data-target="#editModal"
                                        data-max_hour_p="{{$value->max_hour_day_professor}}"
                                        data-max_hour_a="{{$value->max_hour_day_assistant}}">
                                    <i class="fa fa-pencil"></i>
                                    Ndrysho
                                </button>
                            </td>
                        </tr>
                        @empty
                            <div class="alert alert-info">
                                <strong>Njoftim!</strong> Nuk ka të dhëna për tu shfaqur!
                            </div>
                        @endforelse
                        </tbody>
                    </table>
                    {{$data->render()}}
                    <!-- end project list -->
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
@stop