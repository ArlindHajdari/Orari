@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <script src="{{ asset('js/LendetRegister.js') }}"></script>
@stop
@section('body')



    <!-- Modal Register-->
    <div class="modal fade" id="registerModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Regjistrimi i Lendes</h4>
                </div>
                <div class="modal-body">
                    {{ FORM::open(['id'=>'register-form','class'=>'form-horizontal form-label-left input_mask','url'=>'LendetReg']) }}

                    <div class="col-md-10 col-md-offset-1">

                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('subject',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('ects',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('ects',null,['class'=>'form-control','required','placeholder'=>'ECTS']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('semester',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('semester',null,['class'=>'form-control','required','placeholder'=>'Semestri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Lloji',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('subjecttype_id',array_merge(['0'=>'Cakto Llojin'],$subjecttype),null,['class'=>'form-control','required','style'=>'border-radius:2px'])}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Departamenti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('department',array_merge(['0'=>'Cakto Departamentin'],$department),null,['class'=>'form-control','required','style'=>'border-radius:2px'])}}
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','id'=>'lendet-edit','method'=>'PATCH',]) }}

                    <div class="col-md-10 col-md-offset-1">

                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('subject',null,['class'=>'form-control','required','placeholder'=>'Emri','id'=>'subject']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('ECTS',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('ects',null,['class'=>'form-control','required','placeholder'=>'Titulli','id'=>'ects']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Semestri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('semester',null,['class'=>'form-control','required','placeholder'=>'Numri Personal','id'=>'semester']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Lloji',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('subjecttype_id',array_merge(['0'=>'Cakto Llojin'],$subjecttype),null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'subjecttype_id'])}}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Departamenti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('department_id',array_merge(['0'=>'Cakto Departamentin'],$department),null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'department_id'])}}
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
            <h3>Lendet</h3>
        </div>

        <div class="title_right">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                {{FORM::open(['novalidate','id'=>'search-form'])}}
                <div class="input-group">
                    {{FORM::text('search',null,['placeholder'=>'Kërko për...','class'=>'form-control','id'=>'search'])}}
                    <span class="input-group-btn">
                              <button class="btn btn-default" type="button" onclick="document.getElementById('search-form').submit();">Kërko!</button>
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

                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            {{--<th style="width: 1%">#</th>--}}
                            <th style="width: 20%">Lenda</th>
                            <th>ECTS</th>
                            <th>Semestri</th>
                            <th>Lloji i Lendes</th>
                            <th>Departamenti</th>
                            <th style="width: 20%">#Edit</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($lendet->getCollection()->all() as $lende)
                        <tr>
                            {{--<td>{{ $lende->id }}</td>--}}
                            <td>
                                <a>{{ $lende->subject }}</a>
                                <br />
                                {{--<small>Created 01.01.2015</small>--}}
                            </td>
                            <td>
                                <a>{{ $lende->ects }}</a>
                                <br />
                                {{--<small>Created 01.01.2015</small>--}}
                            </td>
                            <td>
                                <a>{{ $lende->semester }}</a>
                                <br />
                                {{--<small>Created 01.01.2015</small>--}}
                            </td>
                            <td>
                                <a>{{ $lende->subjecttype }}</a>
                                <br />
                                {{--<small>Created 01.01.2015</small>--}}
                            </td>

                            <td>
                                <a>{{ $lende->department }}</a>
                                <br />
                                {{--<small>Created 01.01.2015</small>--}}
                            </td>
                            {{--<td>--}}
                                {{--<ul class="list-inline">--}}
                                    {{--<li>--}}
                                        {{--<img src="images/user.png" class="avatar" alt="Avatar">--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<img src="images/user.png" class="avatar" alt="Avatar">--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<img src="images/user.png" class="avatar" alt="Avatar">--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                        {{--<img src="images/user.png" class="avatar" alt="Avatar">--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</td>--}}
                            {{--<td class="project_progress">--}}
                                {{--<div class="progress progress_sm">--}}
                                    {{--<div class="progress-bar bg-green" role="progressbar" data-transitiongoal="57"></div>--}}
                                {{--</div>--}}
                                {{--<small>57% Complete</small>--}}
                            {{--</td>--}}
                            {{--<td>--}}
                                {{--<button type="button" class="btn btn-success btn-xs">Success</button>--}}
                            {{--</td>--}}
                            <td>
                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                        data-id="{{$lende->id}}" data-target="#editModal"
                                        data-subject="{{$lende->subject}}" data-ects="{{$lende->ects}}"
                                        data-semester="{{$lende->semester}}" data-subjecttype_id="{{$lende->subjecttype_id}}"
                                        data-department_id="{{$lende->department_id}}" >
                                    <i class="fa fa-pencil"></i> Edit</button>

                                <button type="button" class="btn btn-danger btn-xs" data-id="{{$lende->id}}"
                                        data-toggle="modal"
                                        data-target="#deleteModal"><i class="fa fa-trash-o"></i> Delete</button>
                            </td>
                        </tr>
                        @empty
                            <div class="alert alert-info" role="alert"><center><h4>Nuk ka te dhena per tu shfaqur!</h4></center></div>
                            {{--<center><h4></h4></center>--}}
                        @endforelse

                        @if(session('message'))
                            <div class="alert alert-success" role="alert"><center><h4>{{session('message')}}</h4></center></div>

                        @endif

                        </tbody>

                    </table>
                    <center>{{ $lendet->links() }}</center>
                    <!-- end project list -->

                </div>
            </div>
        </div>
    </div>

    <!-- /page content -->
@stop