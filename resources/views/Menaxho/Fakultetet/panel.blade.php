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
                            {{ FORM::label('Fakullteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('fakullteti',null,['class'=>'form-control','required',
                                'placeholder'=>'Fakullteti']) }}
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
                            {{ FORM::label('Fakullteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('fakullteti',null,['class'=>'form-control','required','placeholder'=>'Fakullteti']) }}
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
                    <h3>Xalfa <small>Lista e fakulteteve</small></h3>
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
                            <h2>Fakulteti</h2>
                            <button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal" data-target="#registerModal">Regjistro</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <p>Tabela me të dhënat e fakultetit</p>

                            <!-- start project list -->
                            <table class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th style="width: 20%">Fakullteti</th>
                                    <th style="width: 20%">#Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>#</td>
                                    <td>Stërrsh</td>
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