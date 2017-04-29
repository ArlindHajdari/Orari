@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <link href="{{asset('fullcalendar/fullcalendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('fullcalendar/fullcalendar.print.css')}}" rel="stylesheet" media="print">

    <script>
        $(document).ready(function() {
            var data = {!! $json !!};

            $.each(data, function(i,v){
                if(v.allowed == false){
                    v.color = '#a30606';
                }
            });

            $('#calendar2').fullCalendar({
                header: false,
                selectable: true,  // perdoruesi mund ti selektoj kohen dhe diten
                selectHelper: true,
                slotEventOverlap: false,
                hiddenDays: [0], // fsheh te Dielen
                columnFormat: 'dddd',
                dayClick: function(date, jsEvent, view, resourceObj) {
                    var data,day,start_millis,end_millis;

                    start_millis = view.minTime._milliseconds;
                    end_millis = view.maxTime._milliseconds;

                    data = $.fullCalendar.moment(date).format('dddd, YYYY-MM-DD');
                    day = moment(date).format();

                    $('#alldaymodal #day').val(day);
                    $('#alldaymodal #start_milli').val(start_millis);
                    $('#alldaymodal #end_milli').val(end_millis);
                    $('#alldaymodal #when').text(data);
                    $('#alldaymodal').modal('toggle');
                },
                allDaySlot: true,
                allDayText: 'Tëkërëbitën',
                timeFormat: 'HH:mm',
                minTime: "08:00:00",
                maxTime: "20:00:00",
                defaultView: 'agendaWeek',
                defaultDate: '2017-04-18',
                noEventsMessage:'Nuk ka të dhëna për ti shfaqur!',
                navLinks: false,    // can click day/week names to navigate views
                eventLimit: true,    // allow "more" link when too many events
                events: data,
                selectOverlap: false,				// me selektu evente mbi njera tjetren
                eventOverlap: false,				// zevendesim pa ngaterresa
                slotDuration: '00:15:00',
                eventConstraint:{
                    start: "08:00:00",
                    end: "20:00:00"
                },
                visibleRange: {
                    start: '2017-04-17',
                    end: '2017-04-22'
                },
                height: 1218,
                locale:'sq',
                eventClick:  function(event, jsEvent, view) {
                    endtime = $.fullCalendar.moment(event.end).format('HH:mm');
                    starttime = $.fullCalendar.moment(event.start).format('dddd, HH:mm');
                    var mywhen = starttime + ' - ' + endtime;

                    var title = "";
                    if(event.color){
                        title = 'I padisponueshëm!';
                    }else{
                        title = 'I disponueshëm';
                    }
                    $('#modalTitle').html(title);
                    $('#modalWhen').text(mywhen);
                    $('#id').val(event.id);
                    $('#start').val(event.start);
                    $('#end').val(event.end);
                    $('#calendarModal').modal();
                },

                //header and other values
                select: function(start, end, jsEvent) {
                    var mEnd = $.fullCalendar.moment(end);
                    var mStart = $.fullCalendar.moment(start);

                    var diff = mStart.diff(mEnd,'minutes')*-1;

                    if (mEnd.isAfter(mStart, 'day') || diff < 60) {
                        $('#calendar2').fullCalendar('unselect');
                    } else {
                        endtime = $.fullCalendar.moment(end).format('H:mm');
                        starttime = $.fullCalendar.moment(start).format('dddd, H:mm');
                        var mywhen = starttime + ' - ' + endtime;
                        start = moment(start).format();
                        end = moment(end).format();
                        $('#createEventModal #startTime').val(start);
                        $('#createEventModal #endTime').val(end);
                        $('#createEventModal #when').text(mywhen);
                        $('#createEventModal').modal('toggle');
                    }
                }
            });

            $('#register-day-availability').submit(function(e){
                // We don't want this to act as a link so cancel the link action
                e.preventDefault();	// nese klikohet butoni add e shton te dhenen ne orar(tabele)

                var forma = $(this), url=forma.attr('action'),formData = forma.serialize();

                $.ajax({
                    url: url,
                    data: formData,//funksioni per regjistrimin e te dhenave
                    type: "POST",
                    success: function(json) {
                        $("#calendar2").fullCalendar('renderEvent',
                                {
                                    id: json.id,
                                    start: json.start.date,
                                    end: json.end.date,
                                    color:json.color,
                                },
                                true);
                    }
                });
                $("#alldaymodal").modal('hide');
            });

            $('#register-availability').submit(function(e){
                // We don't want this to act as a link so cancel the link action
                e.preventDefault();	// nese klikohet butoni add e shton te dhenen ne orar(tabele)
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();

                var forma = $(this), url=forma.attr('action'),formData = forma.serialize();

                $.ajax({
                    url: url,
                    data: formData,
                    type: "POST",
                    success: function(json) {
                        $("#calendar2").fullCalendar('renderEvent',
                                {
                                    id: json.id,
                                    start: startTime,
                                    end: endTime,
                                    color:json.color,
                                },
                                true);
                    }
                });
                $("#createEventModal").modal('hide');
            });

            $('#delete-availability').submit(function(e){
                e.preventDefault();

                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });

                var form = $(this);
                var ID = $('#calendarModal #id').val();
                var url = form.attr('action')+'/'+ID;

                $.ajax({
                    url: url,
                    type: "DELETE",
                    success: function(json) {
                        $("#calendar2").fullCalendar('removeEvents',json.ID);
                    }
                });

                $("#calendarModal").modal('hide');
            });
        });
    </script>
@stop
@section('body')
    <!-- Modal -->
    <div id="alldaymodal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                {{FORM::open(['id'=>'register-day-availability','novalidate','url'=>'day-availability'])}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Shto në disponueshmëri</h4>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <table>
                            <tr>
                                <td style="min-height:15%">Mundesh </td>
                                <td>
                                    &nbsp;
                                    <label id="" class="">
                                        <div class="iradio_flat-green" style="position: relative;">
                                            {{FORM::radio('allowed',1,0,['class'=>'flat','style'=>'position: absolute; opacity: 0;'])}}
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td style="min-height:15%">Nuk mundesh </td>
                                <td>
                                    &nbsp;
                                    <label id="" class="">
                                        <div class="iradio_flat-green" style="position: relative;">
                                            {{FORM::radio('allowed',0,0,['class'=>'flat','style'=>'position: absolute; opacity: 0;'])}}
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    {{FORM::hidden('day',null,['id'=>'day'])}}
                    {{FORM::hidden('start_milli',null,['id'=>'start_milli'])}}
                    {{FORM::hidden('end_milli',null,['id'=>'end_milli'])}}
                    <div class="control-group">
                        <label class="control-label" for="when">Kur:</label>
                        <div class="controls controls-row" id="when" style="margin-top:5px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Anulo</button>
                    {{FORM::submit('Ruaj',['class'=>'btn btn-primary'])}}
                </div>
                {{FORM::close()}}
            </div>
        </div>
    </div>
    <div id="createEventModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                {{FORM::open(['id'=>'register-availability','novalidate'])}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Shto në disponueshmëri</h4>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <table>
                            <tr>
                                <td style="min-height:15%">Mundesh </td>
                                <td>
                                    &nbsp;
                                    <label id="" class="">
                                        <div class="iradio_flat-green" style="position: relative;">
                                            {{FORM::radio('allowed',1,0,['class'=>'flat','style'=>'position: absolute; opacity: 0;'])}}
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td style="min-height:15%">Nuk mundesh </td>
                                <td>
                                    &nbsp;
                                    <label id="" class="">
                                        <div class="iradio_flat-green" style="position: relative;">
                                            {{FORM::radio('allowed',0,0,['class'=>'flat','style'=>'position: absolute; opacity: 0;'])}}
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    {{FORM::hidden('startTime',null,['id'=>'startTime'])}}
                    {{FORM::hidden('endTime',null,['id'=>'endTime'])}}
                    <div class="control-group">
                        <label class="control-label" for="when">Kur:</label>
                        <div class="controls controls-row" id="when" style="margin-top:5px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Anulo</button>
                    {{FORM::submit('Ruaj',['class'=>'btn btn-primary'])}}
                </div>
                {{FORM::close()}}
            </div>
        </div>
    </div>
    <div id="calendarModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                {{FORM::open(['id'=>'delete-availability','novalidate','method'=>'delete','url'=>'delete-availability'])}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detajet</h4>
                </div>
                <div id="modalBody" class="modal-body">
                    <h4 id="modalTitle" class="modal-title"></h4>
                    <div id="modalWhen" style="margin-top:5px;"></div>
                </div>
                {{FORM::hidden('start',null,['id'=>'start'])}}
                {{FORM::hidden('id',null,['id'=>'id'])}}
                {{FORM::hidden('end',null,['id'=>'end'])}}
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    {{FORM::submit('Fshij',['class'=>'btn btn-danger'])}}
                </div>
                {{FORM::close()}}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_content">
                    <div id="calendar2"></div>
                </div>
            </div>
        </div>
    </div>
@stop