@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <style>
        input[type="file"] {
            display: none;
        }
        .custom-file-upload{
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
    </style>

    <script src="{{asset('js/login.js')}}"></script>
    <script>
        function readURL(input){
            if (input.files && input.files[0]) {
                $('#img').css('display','block');
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img')
                            .attr('src', e.target.result)
                            .width(200)
                            .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
@section('body')
    <div class="x_panel">
        <div class="x_title">
            <h2>Modifiko Dekanët</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">

            <div class="col-md-6 col-md-offset-3">
            {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','url'=>'dekEdit']) }}

                <div class="form-group">
                    {{ FORM::label('Cakto Dekanin',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <select class="form-control">
                            <option>Cakto Dekanin</option>
                            <option>Option one</option>
                            <option>Option two</option>
                            <option>Option three</option>
                            <option>Option four</option>
                        </select>
                    </div>
                </div>

                {{--<div class="form-group">--}}
                    {{--{{ FORM::label('Emri',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}--}}
                    {{--<div class="col-md-9 col-sm-9 col-xs-12">--}}
                        {{--{{ Form::select('age', ['Kerrpani', 'Kerrpani 2', 'Kerrpani 3']) }}--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="form-group">
                    {{ FORM::label('Emri',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('emri',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Mbiemri',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('mbiemri',null,['class'=>'form-control','required','placeholder'=>'Mbiemri']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Titulli',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('titulli',null,['class'=>'form-control','required','placeholder'=>'Titulli']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Cakto Fakultetin:',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('dekan_i',null,['class'=>'form-control','required','placeholder'=>'Cakto Fakultetin:']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Numri Personal',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('n_personal',null,['class'=>'form-control','required','placeholder'=>'Numri Personal']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Passwordi',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('passwordi',null,['class'=>'form-control','required','placeholder'=>'Passwordi']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Email',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('email',null,['class'=>'form-control','required','placeholder'=>'Email']) }}

                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Photo',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::label('file-upload',null,['class'=>'btn btn-info']) }}
                        {{ FORM::file('img',['id'=>'file-upload','class'=>'form-control','onchange="readURL(this)"'])}}
                        <br><br>
                        <img id="img" src="#" style="display:none" />
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        {{ FORM::submit('Regjistro',['class'=>'btn btn-success pull-right']) }}
                    </div>
                </div>
            </div>
            {{ FORM::close() }}

        </div>
    </div>
@stop