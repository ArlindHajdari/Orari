@extends('layouts.master')
@section('title')
    Orari
@stop
@section('body')
    <div class="x_panel">
        <div class="x_title">
            <h2>Regjistro Lëndët</h2>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <br>
            {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','url'=>'lendEdit']) }}

            <div class="col-md-6 col-md-offset-3">

                <div class="form-group">
                    {{ FORM::label('Emri',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ FORM::text('emri',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Emri',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        {{ Form::select('age', ['Kerrpani', 'Kerrpani 2', 'Kerrpani 3']) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ FORM::label('Semestri',null,['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) }}
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
                        <img id="img" src="#" />
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