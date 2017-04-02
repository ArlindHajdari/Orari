@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <script src="{{asset('js/dekanetRegjister.js')}}"></script>
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','id'=>'dekanRegister','url'=>'register-dekan','novalidate']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('first_name',null,['class'=>'form-control','required','placeholder'=>'Emri','id'=>'first_name'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Mbiemri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('last_name',null,['class'=>'form-control','required','placeholder'=>'Mbiemri','id'=>'last_name'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Fjalëkalimi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::password('password',['class'=>'form-control','placeholder'=>'Fjalëkalimi','required','id'=>'password'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Titulli akademik',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('academic_title_id',array_merge(['0'=>'Zgjedhe titullin akademik'],$academicalTitles),null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'academid_title_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Puna',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('cpa_id',array_merge(['0'=>'Zgjedhe profesor/asistent'],$cpas),null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'cpa_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Numri personal',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('personal_number',null,['class'=>'form-control','required','placeholder'=>'Numri personal','maxlength'=>'10','id'=>'personal_number'])}}

                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Email',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::email('email',null,['class'=>'form-control','required','placeholder'=>'E-mail','id'=>'email'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Roli',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('role',array_merge(['0'=>'Zgjedhe rolin'],$roles),null,
                                ['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'role'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Foto',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="input-group image-preview control-label pull-right"><!-- don't give a name === doesn't send on POST/GET -->
                                <label class="btn btn-info">
                                    <span class="glyphicon glyphicon-add"> Zgjedh</span>
                                    {!! FORM::file('photo',['required','style'=>'display:none','id'=>'photo']) !!}
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true',
                    'method'=>'PATCH','id'=>'dekan-edit']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Log ID',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('log_id',null,['class'=>'form-control','required','placeholder'=>'Log ID','id'=>'log_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('first_name',null,['class'=>'form-control','required','placeholder'=>'Emri','id'=>'first_name'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Mbiemri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('last_name',null,['class'=>'form-control','required',
                                'placeholder'=>'Mbiemri','id'=>'last_name'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Fjalëkalimi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::password('password',['class'=>'form-control','placeholder'=>'Fjalëkalimi','required','id'=>'password'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Titulli akademik',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('academic_title_id',array_merge(['0'=>'Zgjedhe titullin akademik'],
                                $academicalTitles),null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'academic_title_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Puna',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('cpa_id',array_merge(['0'=>'Zgjedhe profesor/asistent'],$cpas),null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'cpa_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Numri personal',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::text('personal_number',null,['class'=>'form-control','required','placeholder'=>'Numri personal','maxlength'=>'10','id'=>'personal_number'])}}

                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Email',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::email('email',null,['class'=>'form-control','required','placeholder'=>'E-mail','id'=>'email'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Roli',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('role_id',array_merge(['0'=>'Zgjedhe rolin'],$roles),null,
                                ['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'role_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Foto',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="input-group image-preview control-label pull-right">
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
                        {{FORM::open(['id'=>'delete-form','method'=>'DELETE'])}}
                        <div class="form-group">
                            <p class="modal-title" style="font-size: 16px;">A jeni të sigurt që dëshironi të fshini?</p><br>
                            <button href="#" onclick="document.getElementById('delete-form').submit()" class="btn
                            btn-success">Po</button>
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
            <h3>Xalfa <small>Lista e dekanëve</small></h3>
        </div>

        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                {{FORM::open(['novalidate','id'=>'search-form'])}}
                <div class="input-group">
                    {{FORM::text('search',null,['placeholder'=>'Kërko për...','class'=>'form-control','id'=>'search'])}}
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
                    <p>Tabela me të dhënat e mësimdhënësve</p>
                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 20%">Emri</th>
                            <th>Email</th>
                            <th>Numri personal</th>
                            <th>Log ID</th>
                            <th>Foto</th>
                            <th style="width: 20%">#Edit</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($data as $user)
                        <tr>
                            <td>
                                <a>{{$user->full_name}}</a>
                            </td>
                            <td>
                                {{$user->email}}
                            </td>
                            <td>
                                {{$user->personal_number}}
                            </td>
                            <td>
                                {{$user->log_id}}
                            </td>
                            <td>
                                <ul class="list-inline">
                                    <li>
                                        <img src="{{$user->photo}}" class="avatar" alt="Avatar">
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                        data-id="{{$user->id}}" data-target="#editModal"
                                        data-first_name="{{$user->first_name}}" data-last_name="{{$user->last_name}}"
                                        data-password="{{$user->password}}" data-log_id="{{$user->log_id}}"
                                        data-academic_title_id="{{$user->academic_title_id}}"
                                        data-cpa_id="{{$user->cpa_id}}"
                                        data-personal_number="{{$user->personal_number}}" data-email="{{$user->email}}"
                                        data-photo="{{$user->photo}}"><i
                                            class="fa
                                        fa-pencil"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-xs" data-id="{{$user->id}}"
                                        data-toggle="modal"
                                        data-target="#deleteModal"><i class="fa fa-trash-o"></i> Delete</button>
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