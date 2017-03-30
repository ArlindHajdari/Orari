@extends('layouts.master')
@section('title')
    Orari
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','url'=>'lendEdit']) }}

                    <div class="col-md-10 col-md-offset-1">

                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('emri',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Semestri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('titulli',null,['class'=>'form-control','required','placeholder'=>'Titulli']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Numri Personal',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('n_personal',null,['class'=>'form-control','required','placeholder'=>'Numri Personal']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Passwordi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('passwordi',null,['class'=>'form-control','required','placeholder'=>'Passwordi']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Email',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('email',null,['class'=>'form-control','required','placeholder'=>'Email']) }}

                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Foto',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="input-group image-preview control-label pull-right"><!-- don't give a name === doesn't send on POST/GET -->
                                <label class="btn btn-info">
                                    <span class="glyphicon glyphicon-add"> Zgjedh</span>
                                    {!! FORM::file('photo',['required','style'=>'display:none']) !!}
                                </label>
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','url'=>'lendEdit']) }}

                    <div class="col-md-10 col-md-offset-1">

                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('emri',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Semestri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('titulli',null,['class'=>'form-control','required','placeholder'=>'Titulli']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Numri Personal',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('n_personal',null,['class'=>'form-control','required','placeholder'=>'Numri Personal']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Passwordi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('passwordi',null,['class'=>'form-control','required','placeholder'=>'Passwordi']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Email',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('email',null,['class'=>'form-control','required','placeholder'=>'Email']) }}

                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Foto',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="input-group image-preview control-label pull-right"><!-- don't give a name === doesn't send on POST/GET -->
                                <label class="btn btn-info">
                                    <span class="glyphicon glyphicon-add"> Zgjedh</span>
                                    {!! FORM::file('photo',['required','style'=>'display:none']) !!}
                                </label>
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
                            <form action="logout" method="POST" id="logout-form">
                                {{ csrf_field() }}
                                <button href="#" onclick="document.getElementById('logout-form').submit()" class="btn btn-success">Yes</button>
                                <button data-dismiss="modal" class="btn btn-danger">No</button>
                            </form>
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
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                        </div>
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

                            <!-- start project list -->
                            <table class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th style="width: 20%">Project Name</th>
                                    <th>Team Members</th>
                                    <th>Project Progress</th>
                                    <th>Status</th>
                                    <th style="width: 20%">#Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <a>Pesamakini Backend UI</a>
                                        <br />
                                        <small>Created 01.01.2015</small>
                                    </td>
                                    <td>
                                        <ul class="list-inline">
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="project_progress">
                                        <div class="progress progress_sm">
                                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="57"></div>
                                        </div>
                                        <small>57% Complete</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-xs">Success</button>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
                                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash-o"></i> Delete</button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <!-- end project list -->

                        </div>
                    </div>
                </div>
            </div>

    <!-- /page content -->
@stop