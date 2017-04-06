@extends('layouts.master')
@section('title')
    Orari
@stop

@section('other')
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

    <script src="{{asset('js/proflende.js')}}"></script>
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','url'=>'prosubRegister']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','url'=>'profLendeEdit']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
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

    <!-- Modal Delete-->
    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog modal-sm">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body" style="padding:25px 10px">
                    <div align="middle">
                        <div class="form-group">
                            <p class="modal-title" style="font-size: 16px;">A jeni të sigurt që dëshironi të fshini?</p><br>
                            {{FORM::open(['id'=>'delete-form','novalidate','method'=>'DELETE'])}}
                                <button href="#" onclick="document.getElementById('delete-form').submit()" class="btn btn-success">Po</button>
                                <button data-dismiss="modal" class="btn btn-danger">Jo</button>
                            {{FORM::close()}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Modal /Delete-->

    <!-- page content -->
            <div class="page-title">
                <div class="title_left">
                    <h3>Projects <small>Listing designi</small></h3>
                </div>
                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        {{FORM::open(['novalidate','id'=>'search-form'])}}
                        <div class="input-group">
                            {{FORM::text('search',null,['class'=>'form-control','placeholder'=>'Kërko për...'])}}
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="document
                      .getElementById('search-form').submit();">Kërko!</button>
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
                            <h2>Projects</h2>
                            <button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal" data-target="#registerModal">Regjistro</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <p>Simple table with project listing with progress and editing options</p>

                            @forelse($data as $prosub)
                            <table class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th style="width: 20%">Lënda</th>
                                    <th>Profesori</th>
                                    <th>Asistentet</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        {{$prosub->user->first_name}} {{$prosub->user->last_name}}
                                    </td>
                                    <td>
                                        {{$prosub->subject->subject}}
                                    </td>
                                    <td>
                                        {{$prosub->user->first_name}} {{$prosub->user->last_name}}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal" data-id="{{$prosub->id}}"><i class="fa fa-trash-o"></i> Delete</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            @empty
                                <div class="alert alert-info">
                                    <strong>Njoftim!</strong> Nuk ka të dhëna për tu shfaqur!
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
    <!-- /page content -->
@stop