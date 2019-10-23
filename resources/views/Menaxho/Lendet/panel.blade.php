@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <script src="{{ asset('js/LendetRegister.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#subject_lush').multiselect({
                selectAllText: 'Zgjedh te gjitha!',
                nonSelectedText: 'Asnjë e zgjedhur',
                selectAllNumber: false,
                enableFiltering: false,
                allSelectedText: 'Të gjitha të zgjedhura'
            });
        });
    </script>
@stop
@section('body')
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
                                {{ FORM::text('subject',null,['class'=>'form-control','required','placeholder'=>'Emri','id'=>'subject']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('ects',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('ects',null,['class'=>'form-control','required','placeholder'=>'ECTS','id'=>'ects']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('semester',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::number('semester',null,['class'=>'form-control','required','placeholder'=>'Semestri','id'=>'semester']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Lloji',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('subjecttype_id',['0'=>'Cakto Llojin']+$subjecttype,0,['class'=>'form-control','required','style'=>'border-radius:2px'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Departamenti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('department_id',['0'=>'Cakto Departamentin']+$department,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'department_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('LUSH',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('subject_lush[]',$subject_lush,1,['multiple','class'=>'form-control','required','style'=>'border-radius:2px','id'=>'subject_lush'])}}
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
    <div class="modal fade" id="editModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Ndryshimi</h4>
                </div>
                <div class="modal-body">
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','id'=>'lendet-edit','method'=>'PATCH']) }}
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
                                {{ FORM::number('semester',null,['class'=>'form-control','required','placeholder'=>'Numri Personal','id'=>'semester']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Lloji',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('subjecttype_id',['0'=>'Cakto Llojin']+$subjecttype,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'subjecttype_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Departamenti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('department_id',['0'=>'Cakto Departamentin']+$department,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'department_id'])}}
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
    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog modal-sm">
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
    <div class="page-title">
        <div class="title_left">
            <h3>Xalfa <small>Lista e lëndëve</small></h3>
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
                    <h2>Lëndët</h2>
                    <button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal" data-target="#registerModal">Regjistro</button>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 20%">Lenda</th>
                            <th style="width: 5%">ECTS</th>
                            <th style="width: 5%">Semestri</th>
                            <th style="width: 10%">Lloji i Lendes</th>
                            <th style="width: 10%">Departamenti</th>
                            <th style="width: 20%">LUSH</th>
                            <th style="width: 20%">Opsionet</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($lendet as $lende)
                        <tr>
                            <td>
                                <a>{{ $lende->subject }}</a>
                                <br />
                            </td>
                            <td>
                                <a>{{ $lende->ects }}</a>
                                <br />
                            </td>
                            <td>
                                <a>{{ $lende->semester }}</a>
                                <br />
                            </td>
                            <td>
                                <a>{{ $lende->subjecttype }}</a>
                                <br />
                            </td>
                            <td>
                                <a>{{ $lende->department }}</a>
                                <br />
                            </td>
                            <td>
                                <a>
                                    @foreach(getLushForSubject($lende->id) as $lushi)
                                        @if(!$loop->last)
                                            {{$lushi}} |
                                        @else
                                            {{$lushi}}
                                        @endif
                                    @endforeach
                                </a>
                                <br />
                            </td>
                            <td>
                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                        data-id="{{$lende->id}}" data-target="#editModal"
                                        data-subject="{{$lende->subject}}" data-ects="{{$lende->ects}}"
                                        data-semester="{{$lende->semester}}" data-subjecttype_id="{{$lende->subjecttype_id}}"
                                        data-department_id="{{$lende->department_id}}">
                                    <i class="fa fa-pencil"></i> Ndrysho</button>
                                <button type="button" class="btn btn-danger btn-xs" data-id="{{$lende->id}}"
                                        data-toggle="modal"
                                        data-target="#deleteModal"><i class="fa fa-trash-o"></i> Fshij</button>
                            </td>
                        </tr>
                        @empty
                            <div class="alert alert-info">
                                <strong>Njoftim!</strong> Nuk ka të dhëna për tu shfaqur!
                            </div>
                        @endforelse
                        </tbody>
                    </table>
                    <center>{{ $lendet->links() }}</center>
                </div>
            </div>
        </div>
    </div>
@stop
