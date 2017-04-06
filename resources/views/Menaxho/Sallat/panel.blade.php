@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <script src="{{asset('js/sallatRegister.js')}}"></script>
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','id'=>'sallaRegister','url'=>'salla-register','novalidate']) }}
                    <div class="col-md-10 col-md-offset-1">
                        
                        <div class="form-group">
                            {{ FORM::label('Emri Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('hall',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Kapaciteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('capacity',null,['class'=>'form-control','required','placeholder'=>'Titulli']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Lloji Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('halltype_id',['1'=>'laborator','2'=>'teori'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
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
                    'method'=>'PATCH','id'=>'salla-edit']) }}
                    <div class="col-md-10 col-md-offset-1">
                 
                        <div class="form-group">
                            {{ FORM::label('Emri Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('hall',null,['class'=>'form-control','required','placeholder'=>'Emri','id'=>'hall']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Kapaciteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('capacity',null,['class'=>'form-control','required','placeholder'=>'Titulli','id'=>'capacity']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Lloji Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('halltype_id',['1'=>'laborator','2'=>'teori'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti','id'=>'halltype_id']) }}
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
                            <th>Kapaciteti</th>
                            <th>Lloji i salles</th>
                            <th style="width: 20%">#Edit</th>
                        </tr>
                        </thead>

                        <tbody>
                       
                        @forelse($data as $hall)
                        <tr>
                            <td>
                                {{$hall->hall}}
                            </td>
                            <td>
                                {{$hall->capacity}}
                            </td>
                            <td>
                                {{$hall->halltype->halltype}}
                            </td>
                          
                            <td>
                                <button type="button" class="btn btn-info btn-xs" data-toggle="modal"
                                        data-id="{{$hall->id}}" data-target="#editModal"
                                        data-hall="{{$hall->hall}}" data-capacity="{{$hall->capacity}}" data-halltype_id="{{$hall->halltype_id}}"> 
                                        <i class="fa fa-pencil"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-xs" data-id="{{$hall->id}}"
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