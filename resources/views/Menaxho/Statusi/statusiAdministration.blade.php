@extends('layouts.master')
@section('title')
    Orari
@stop

@section('other')
    <script src="{{asset('js/StatusAdministration.js')}}"></script>
@stop

@section('body')

    <!-- Modal Register-->
    <div class="modal fade" id="registerModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Regjistrimi</h4>
                </div>
                <div class="modal-body">
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','id'=>'status-register', 'url'=>'status-register']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Statusi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('status',null,['class'=>'form-control','required','id'=>'status']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-4">
                            {{ FORM::submit('Regjistro',['class'=>'btn btn-success pull-right']) }}
                        </div>
                    </div>
                </div>
            </div>
            {{ FORM::close() }}
        </div>
    </div>
    <!-- /Modal /Register-->

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
                {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','method'=>'PATCH','id'=>'status-edit', 'url'=>'status-edit']) }}
                <div class="form-group">
                    {{ FORM::label('Statusi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                    <div class="col-md-8 col-sm-8 col-xs-12">
                        {{ FORM::text('status',null,['class'=>'form-control','required','id'=>'status']) }}
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

    <!-- Modal Delete-->
    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body" style="padding:25px 10px">
                    <div align="middle">
                        {{FORM::open(['id'=>'delete-form','method'=>'DELETE'])}}
                        <div class="form-group">
                            <p class="modal-title" style="font-size: 16px;">A jeni të sigurt që dëshironi të fshini?</p>
                            <br>
                            <button href="#" onclick="document.getElementById('delete-form').submit()" class="btn
                            btn-success">Po
                            </button>
                            <button data-dismiss="modal" class="btn btn-danger">Jo</button>
                        </div>
                        {{FORM::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Modal /Delete-->


    <!-- page content -->
    <div class="page-title">
        <div class="title_left">
            <h3>Xalfa
                <small>Lista e statuseve</small>
            </h3>
        </div>
        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                {{FORM::open(['novalidate','id'=>'search-form'])}}
                <div class="input-group">
                    {{FORM::text('search',null,['placeholder'=>'Kërko për...','class'=>'form-control','id'=>'search'])}}
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"
                              onclick="document.getElementById('search-form').submit();">Kërko!
                      </button>
                    </span>
                </div>
                {{FORM::close()}}
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Statusi</h2>
                    <button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal"
                            data-target="#registerModal">Regjistro
                    </button>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 80%">Statusi</th>
                            <th style="width: 20%">Opsionet</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($statuses as $value)
                            <tr>
                                <td>{{ $value->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                            data-status="{{$value->name}}"
                                            data-id="{{$value->id}}"
                                            data-target="#editModal">
                                        <i class="fa fa-pencil"></i>
                                        Ndrysho
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs"
                                            data-status_id="{{$value->id}}" data-toggle="modal"
                                            data-target="#deleteModal"><i class="fa fa-trash-o"></i> Fshij
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
                    {{$statuses->render()}}
                </div>
            </div>
        </div>
    </div>

    <!-- /page content -->
@stop
