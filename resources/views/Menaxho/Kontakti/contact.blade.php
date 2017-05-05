@extends('layouts.master')

@section('body')
 {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','id'=>'message','url'=>'kontakti','novalidate']) }}
                    <div class="col-md-10 col-md-offset-1">
                        <div class="form-group">
                            {{ FORM::label('Dekani',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('dekan_id',[0=>'Zgjedh dekanin!']+$dekanet,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'dekan_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Salla',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('salla_id',[0=>'Zgjedh sallën!']+$halls,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'salla_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('Ditet',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('ditet',[0=>'Zgjedh ditën!']+$ditet,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'ditet'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('OraPrej',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('oraprej',[0=>'Zgjedh fillimin e orës!']+$oraprej,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'oraprej'])}}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ FORM::label('OraDeri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('oraderi',[0=>'Zgjedh mbarimin e orës!']+$oraderi,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'oraderi'])}}
                            </div>
                        </div>
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-4">
                            {{ FORM::submit('Dergo',['class'=>'btn btn-success pull-right']) }}
                        </div>
                    </div>
            {{ FORM::close() }}
        </div>
@stop