@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <script src="{{asset('js/sallatRegister.js')}}"></script>
    <script>
        $(document).ready(function() {
            var caktoFakultet;
            var caktoFakultet2;
            var caktoFakultet3;
            var caktoFakultet5;

            $('#faculty_id2').attr('disabled','disabled');
            $('#sec_faculty_id2').attr('disabled','disabled');

            $('select[id="halltype_id2"]').on('change',function(e){

                var caktoSalle = $(this).val();

                if(caktoSalle == 0)
                {
                    $('#faculty_id2').attr('disabled','disabled');
                    //$('#sec_faculty_id').val(null);
                }else {
                    $('#faculty_id2').removeAttr('disabled');
                }
            });

            $('select[id="faculty_id2"]').on('change',function(e){

                caktoFakultet = $(this).val();

                if(caktoFakultet == 0)
                {
                    $('#sec_faculty_id2').attr('disabled','disabled');
                    //$('#sec_faculty_id').val(null);
                }else {
                    $('#sec_faculty_id2').removeAttr('disabled');
                }
            });

            $('select[id="sec_faculty_id2"]').on('change',function(e){
                caktoFakultet2 = $(this).val();

                if(caktoFakultet == caktoFakultet2)
                {
                    BootstrapDialog.show({
                        title: 'Gabim gjatë futjes së të dhënave',
                        message: 'Ju lutem caktoni fakultete te ndryshme!',
                        buttons: [{
                            label: 'OK',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });

                    $('#faculty_id2').val('0');
                    $('#sec_faculty_id2').attr('disabled','disabled');
                    $('#sec_faculty_id2').val(null);
                }
            });

            $('#editModal').on('shown.bs.modal', function (e) {
                caktoFakultet3 = $('#faculty_id').val();
            });

            $('select[id="subjeect_id"]').on('change',function(e){

                caktoFakultet5 = $(this).val();

                if(caktoFakultet5 == 0)
                {
                    $('#sec_faculty_id').val('0');
                    $('#sec_faculty_id').attr('disabled','disabled');
                }else
                {
                    $('#sec_faculty_id').removeAttr('disabled');
                }
            });

            $('select[id="sec_faculty_id"]').on('change',function(e){

                var caktoFakultet4 = $(this).val();

                if(caktoFakultet3 == caktoFakultet4 || caktoFakultet4 == caktoFakultet5)
                {
                    BootstrapDialog.show({
                        title: 'Gabim gjatë futjes së të dhënave',
                        message: 'Ju lutem caktoni fakultete te ndryshme!',
                        buttons: [{
                            label: 'OK',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });

                    $('#sec_faculty_id').val('0');
                }
            });

        });
    </script>
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
                                {{ FORM::text('hall',null,['class'=>'form-control','required','placeholder'=>'Salla']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Kapaciteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('capacity',null,['class'=>'form-control','required','placeholder'=>'Kapaciteti']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Lloji Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('halltype_id',[0=>'Cakto llojin e sallës']+$halltypes,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'halltype_id2']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Cakto fakultetin primar',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('faculty_id',[0=>'Cakto fakultetin primar']+$faculty,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'faculty_id2']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Cakto fakultetin sekondar',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('sec_faculty_id',[null=>'Cakto fakultetin sekondar']+$secfaculty,null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','id'=>'sec_faculty_id2']) }}
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','method'=>'PATCH','id'=>'salla-edit']) }}
                    <div class="col-md-10 col-md-offset-1">
                 
                        <div class="form-group">
                            {{ FORM::label('Emri Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('hall',null,['class'=>'form-control','required','placeholder'=>'Salla','id'=>'hall']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Kapaciteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('capacity',null,['class'=>'form-control','required','placeholder'=>'Kapaciteti','id'=>'capacity']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Lloji Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('halltype_id',$halltypes,null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'halltype_id']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Fakulteti primar',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('faculty_id',[0=>'Cakto fakultetin primar']+$faculty,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'faculty_id']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Fakulteti sekondar',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('sec_faculty_id',[null=>'Cakto fakultetin sekondar']+$secfaculty,null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','id'=>'sec_faculty_id']) }}
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
            <h3>Xalfa <small>Lista e sallave</small></h3>
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
                    <h2>Sallat</h2>
                    <button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal" data-target="#registerModal">Regjistro</button>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- start project list -->
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 20%">Emri</th>
                            <th style="width: 10%">Kapaciteti</th>
                            <th style="width: 10%">Lloji i salles</th>
                            <th style="width: 20%">Fakulteti primar</th>
                            <th style="width: 20%">Fakulteti sekondar</th>
                            <th style="width: 20%">Opsionet</th>
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
                                {{$hall->hallType}}
                            </td>
                            <td>
                                {{$hall->faculty }}
                            </td>
                            <td>
                                {{$hall->sec_faculty }}
                            </td>

                            <td>
                                <button type="button" id="btnEdit" class="btn btn-info btn-xs" data-toggle="modal" data-id="{{$hall->id}}" data-target="#editModal"
                                        data-hall="{{$hall->hall}}"
                                        data-capacity="{{$hall->capacity}}"
                                        data-halltype_id="{{$hall->halltype_id}}"
                                        data-faculty_id="{{$hall->faculty_id}}"
                                        data-sec_faculty_id="{{$hall->sec_faculty_id}}">
                                        <i class="fa fa-pencil"></i> Ndrysho</button>
                                <button type="button" class="btn btn-danger btn-xs" data-id="{{$hall->id}}"
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
                    {{$data->render()}}
                    <!-- end project list -->
                </div>

            </div>
        </div>
    </div>
    <!-- /page content -->
@stop