@extends('layouts.master')
@section('other')
    <link href="{{asset('css/slider.css')}}" type="text/css" rel="stylesheet"/>
     <script>
         $(function() {
             $("#slider-range").slider({
                 range: true,
                 min: 480,
                 max: 1080,
                 step: 15,
                 values: [600, 720],
                 slide: function(e, ui) {
                     var hours1 = Math.floor(ui.values[0] / 60);
                     var minutes1 = ui.values[0] - (hours1 * 60);

                     if (hours1.length == 1) hours1 = '0' + hours1;
                     if (minutes1.length == 1) minutes1 = '0' + minutes1;
                     if (minutes1 == 0) minutes1 = '00';
                     if (hours1 >= 12) {
                         if (hours1 == 12) {
                             hours1 = hours1;
                             minutes1 = minutes1;
                         } else {
                             hours1 = hours1;
                             minutes1 = minutes1;
                         }
                     } else {
                         hours1 = hours1;
                         minutes1 = minutes1;
                     }
                     if (hours1 == 0) {
                         hours1 = 12;
                         minutes1 = minutes1;
                     }



                     $('.slider-time').html(hours1 + ':' + minutes1);
                     $('#ora').val($("#time-range .slider-time").text());

                     var hours2 = Math.floor(ui.values[1] / 60);
                     var minutes2 = ui.values[1] - (hours2 * 60);

                     if (hours2.length == 1) hours2 = '0' + hours2;
                     if (minutes2.length == 1) minutes2 = '0' + minutes2;
                     if (minutes2 == 0) minutes2 = '00';
                     if (hours2 >= 12) {
                         if (hours2 == 12) {
                             hours2 = hours2;
                             minutes2 = minutes2;
                         } else if (hours2 == 24) {
                             hours2 += 11;
                             minutes2 = 59;
                         } else {
                             hours2 = hours2;
                             minutes2 = minutes2;
                         }
                     } else {
                         hours2 = hours2;
                         minutes2 = minutes2;
                     }

                     $('.slider-time2').html(hours2 + ':' + minutes2);
                     $('#ora2').val($("#time-range .slider-time2").text());
                 }
             });
         });
         $('#message').submit(function(e) {
             e.preventDefault();

             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                 }
             });

             var formData = $(this).serialize();

             var url = $(this).attr('action');
             $.ajax({
                 type: 'POST',
                 data: formData,
                 url: url,
                 dataType: 'JSON',
                 statusCode: {
                     500: function(data) {
                         BootstrapDialog.show({
                             title: data.responseJSON['title'],
                             message: data.responseJSON['msg'],
                             buttons: [{
                                 label: 'Close',
                                 action: function(dialog) {
                                     dialog.close();
                                 }
                             }]
                         });
                     },
                     400: function(data) {
                         $.each(data.responseJSON['errors'], function(i, v) {
                             $.each(this, function(index, value) {
                                 var errorID = '#' + i;
                                 $(errorID).tooltip({ title: value, placement: "right" }).tooltip('show');
                             })
                         });
                     },
                     200: function(data) {
                         BootstrapDialog.show({
                             title: data.title,
                             message: data.msg,
                             buttons: [{
                                 label: 'OK',
                                 action: function(dialog) {
                                     window.location.reload();
                                 }
                             }]
                         });
                     }
                 }
             });
         });
     </script>
@stop
@section('body')
    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','id'=>'message','novalidate']) }}
                     <div class="col-md-10 col-md-offset-1">
                         <div class="form-group">
                            {{ FORM::label('Dekanet',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('dekan_id',$dekanet,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'dek_id'])}}
                            </div>
                        </div>
                        <div class="form-group">
                             {{ FORM::label('Salla',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                             <div class="col-md-8 col-sm-8 col-xs-12">
                                 {{FORM::select('salla_id',[0=>'Zgjedh sallën!']+$HFC,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'salla_id'])}}
                             </div>
                         </div>
                        <div class="form-group">
                            {{ FORM::label('Ditet',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::select('ditet',[0=>'Zgjedh ditën!']+$ditet,null,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'ditet'])}}
                            </div>
                        </div>
                        {{FORM::hidden('ora','10:00',['id'=>'ora'])}}
                        {{FORM::hidden('ora2','12:00',['id'=>'ora2'])}}
                        <div class="form-group">
                            <div id="time-range">
                                <p>Koha: <span class="slider-time">10:00</span> - <span class="slider-time2">12:00</span>

                                </p>
                                <div class="sliders_step1">
                                    <div id="slider-range"></div>
                                </div>
                            </div>
                        </div>
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-4">
                            {{ FORM::submit('Dergo',['class'=>'btn btn-success pull-right']) }}
                        </div>
                    </div>
                    </div>
    {{ FORM::close() }}
@stop
