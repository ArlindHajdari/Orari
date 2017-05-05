@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <link href="{{asset('fullcalendar/fullcalendar.min.css')}}" rel="stylesheet">
    <link href="{{asset('fullcalendar/fullcalendar.print.css')}}" rel="stylesheet" media="print">
    <link href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css" rel="stylesheet">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    {{--<script>--}}

        {{--$(document).ready(function() {--}}
            {{--$('#subject_id').attr('disabled','disabled');--}}
            {{--$('#hall_id').attr('disabled','disabled');--}}
            {{--$('select[id="prof_id"]').on('change',function(){--}}
                {{--var  cakto = $(this).val();--}}
                {{--console.log(cakto);--}}
                {{--if(cakto > 0){--}}
                    {{--$('#disabled_input').removeAttr('disabled');--}}
                {{--}--}}

            {{--});--}}
        {{--});--}}
    {{--</script>--}}


    <script>
        $(document).ready(function() {

            var arrProf = [];

            $('#prof_id').attr('disabled','disabled');
            $('#hall_id').attr('disabled','disabled');
            $('#lu_id').attr('disabled','disabled');
            $('#gr_id').attr('disabled','disabled');

            $('select[id="subject_id"]').on('change',function(e){
                $('#prof_id').html(' ');
                var caktoLende = $(this).val();

                console.log(caktoLende);

                if(caktoLende == 0){
                    $('#prof_id').attr('disabled','disabled');
                    $('#hall_id').attr('disabled','disabled');
                    $('#lu_id').attr('disabled','disabled');
                    $('#gr_id').attr('disabled','disabled');
                    $('#prof_id').val('0');
                    $('#hall_id').val('0');
                    $('#lu_id').val('0');
                    $('#gr_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                }else {
                    $('#prof_id').removeAttr('disabled');

                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getProfByLende',
                        data:{'id':$('#subject_id').val()},
                        success:function(data){
                            $.each(data, function(i,v){
                                var option = "<option value='0'>Cakto Profesorin</option><option value='"+i+"'>"+v+"</option>";
                                $('#prof_id').append(option);
                            });
                        },
                        error:function(){
                            console.log('T');
                        }
                    })
                }
            });

            $('select[id="prof_id"]').on('change',function(){

                var caktoProf = $(this).val();

                if(caktoProf == 0){
                    console.log(caktoProf);
                    $('#lu_id').attr('disabled','disabled');
                    $('#lu_id').val('0');
                    $('#gr_id').attr('disabled','disabled');
                    $('#gr_id').val('0');
                    $('#hall_id').attr('disabled','disabled');
                    $('#hall_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                }else {
                    $('#lu_id').removeAttr('disabled');
                }
            });

            $('select[id="lu_id"]').on('change',function(){

                var caktoLU = $(this).val();

                if(caktoLU == 0){
                    console.log(caktoLU);
                    $('#gr_id').attr('disabled','disabled');
                    $('#gr_id').val('0');
                    $('#hall_id').attr('disabled','disabled');
                    $('#hall_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                }else {
                    $('#gr_id').removeAttr('disabled');
                }
            });

            $('select[id="gr_id"]').on('change',function(){

                var caktoGr = $(this).val();

                if(caktoGr == 0){
                    console.log(caktoGr);
                    $('#hall_id').attr('disabled','disabled');
                    $('#hall_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                }else {
                    $('#hall_id').removeAttr('disabled');
                }
            });

            $('select[id="hall_id"]').on('change',function(){

                var hall = $(this).val();

                if(hall == 0){
                    $('#calendar2').fullCalendar( 'destroy' );
                }
                else{

                    $('#calendar2').fullCalendar({
                        header: false,
                        selectable: true,  // perdoruesi mund ti selektoj kohen dhe diten
                        selectHelper: true,
                        slotEventOverlap: false,
                        hiddenDays: [0] ,                   // fsheh te Dielen
                        columnFormat: 'dddd',
                        allDaySlot: false,                  // tere dita slot
                        minTime: "08:00:00",
                        maxTime: "20:00:00",
                        defaultView: 'agendaWeek',
                        navLinks: false, // can click day/week names to navigate views
                        editable: true, // perdoruesi nuk mund te editoj eventet pasi i ka krijuar ato
                        eventLimit: true, // allow "more" link when too many events
                        draggable: true,					// te zhvendosshme
                        eventDurationEditable: true,		// perdoruesi nuk mund te ndryshoj kohen e eventit
                        {{--events: {!!  $json!!},--}}
                        selectOverlap: false,				// me selektu evente mbi njera tjetren
                        eventOverlap: false,				// zevendesim pa ngaterresa
                        slotDuration: '00:15:00',

                        eventConstraint:{
                            start: "08:00:00",
                            end: "20:00:00"
                        },
                        visibleRange:{
                            start: "2017-04-17",
                            end: "2017-04-22"
                        },
                        height: 1218,
                        locale:'sq',
                        color: 'green',
                        eventClick:  function(event, jsEvent, view) {
                            endtime = $.fullCalendar.moment(event.end).format('h:mm');
                            starttime = $.fullCalendar.moment(event.start).format('dddd, h:mm');
                            var mywhen = starttime + ' - ' + endtime; // nese klikohet eventi na shfaqet modali me detajet(oren e fillimit edhe mbarimit)
                            $('#modalTitle').html(event.title);
                            $('#modalWhen').text(mywhen);
                            $('#avail_id').val(event.id);
                            $('#calendarModal').modal();
                        },

                        //header and other values
                        select: function(start, end, jsEvent) {
                            var mEnd = $.fullCalendar.moment(end);
                            var mStart = $.fullCalendar.moment(start);


                            var difference = mStart.diff(mEnd, 'minutes')*-1;

                            if (mEnd.isAfter(mStart, 'day') || difference <= 44) {
                                $('#calendar2').fullCalendar('unselect');
                            } else {
                                endtime = $.fullCalendar.moment(end).format('h:mm');
                                starttime = $.fullCalendar.moment(start).format('dddd, h:mm');
                                var mywhen = starttime + ' - ' + endtime;
                                start = moment(start).format();
                                end = moment(end).format();	// nese caktohen oret na shfaqet modali me oren e fillimit
                                // edhe mbarimit
                                $('#createEventModal #startTime').val(start);
                                $('#createEventModal #endTime').val(end);
                                $('#createEventModal #when').text(mywhen);
                                $('#createEventModal').modal('toggle');
                            }
                        },
                        eventDrop: function(event){
                            $.ajax({
                                url: 'http://localhost:8000/edit-availability/'+event.id,
                                data: {
                                    start: moment(event.start).format(),
                                    end: moment(event.end).format(),
                                    _token: '{{csrf_token()}}'
                                },
                                type: "PATCH",
                                success: function(json) {
                                    //alert(json);
                                },
                                error: function(json){
                                    BootstrapDialog.show({
                                        title: 'Gabim gjatë modifikimit',
                                        message: 'Të dhënat nuk janë të sakta!',
                                        buttons: [{
                                            label: 'OK',
                                            action: function(dialog) {
                                                window.location.reload();
                                            }
                                        }]
                                    });
                                }
                            });
                        },
                        eventResize: function(event) {
                            $.ajax({
                                url: 'http://localhost:8000/edit-availability/'+event.id,
                                data: {
                                    start: moment(event.start).format(),
                                    end: moment(event.end).format(),
                                    _token: '{{csrf_token()}}'
                                },
                                type: "PATCH",
                                success: function(json) {
                                    //alert(json);
                                },
                                error: function(){
                                    BootstrapDialog.show({
                                        title: 'Gabim gjatë modifikimit',
                                        message: 'Të dhënat nuk janë të sakta!',
                                        buttons: [{
                                            label: 'OK',
                                            action: function(dialog) {
                                                window.location.reload();
                                            }
                                        }]
                                    });
                                }
                            });
                        }
                    });
                }
            });

            $('#register-availability').submit(function(e){
                // We don't want this to act as a link so cancel the link action
                e.preventDefault();	// nese klikohet butoni add e shton te dhenen ne orar(tabele)
                var startTime = $('#startTime').val();
                var endTime = $('#endTime').val();

                var forma = $(this), url=forma.attr('action'),formData = forma.serialize();

                $.ajax({
                    url: url,
                    data: formData,//funksioni per regjistrimin e te dhenave
                    type: "POST",
                    success: function(json) {
                        $("#calendar2").fullCalendar('renderEvent',
                                {
                                    id: json.id,
                                    start: startTime,
                                    end: endTime
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

                var ID = $('#avail_id').val();
                var form = $(this);
                var url = form.attr('action')+'/'+ID;
                console.log(url);
                $.ajax({
                    url: url,
                    data: ID,
                    type: "DELETE",
                    success: function(json) {
                        if(json.success)
                            $("#calendar2").fullCalendar('removeEvents',ID);
                        else
                            return false;
                    }
                });

                $("#calendarModal").modal('hide');
            });
        });
    </script>
    @stop
    @section('body')
            <!-- Modal -->
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
                {{FORM::hidden('id',null,['id'=>'avail_id'])}}
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
                <div class="row">
                    {{ FORM::open(['id'=>'OrariRegister']) }}
                    <div class="col-md-3">
                        {{ FORM::label('Cakto Lenden',null,['class'=>'control-label']) }}
                        {{ FORM::select('subject_id',[0=>'Cakto Lenden']+$profSub,0,['class'=>'selectpicker form-control','required','id'=>'subject_id','data-live-search'=>'true']) }}
                    </div>

                    <div class="col-md-3">
                        {{ FORM::label('Cakto Profesorin',null,['class'=>'control-label']) }}
                        {{--<div id="prof_id"></div>--}}
                        <select name="prof_id" id="prof_id" class="form-control shkas col-md-8 col-sm-8 col-xs-12','required">
                        </select>
                        {{--{{ FORM::select('prof_id','',0,['class'=>'productname form-control shkas col-md-8 col-sm-8 col-xs-12','required','id'=>'prof_id']) }}--}}
                    </div>

                    <div class="col-md-2">
                        {{ FORM::label('Cakto L/U',null,['class'=>'control-label']) }}
                        {{ FORM::select('lu_id',[0=>'L/U',1=>'Ligjërat',2=>'Ushtrime',3=>'Seminar',4=>'ProSeminar',5=>'LAB'],0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'lu_id']) }}
                    </div>
                    <div class="col-md-1">
                        {{ FORM::label('Grupi',null,['class'=>'control-label']) }}
                        {{ FORM::select('gr_id',[0=>'Grupi',1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'],0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'gr_id']) }}
                    </div>
                    <div class="col-md-3">
                        {{ FORM::label('Cakto Sallen',null,['class'=>'control-label']) }}
                        {{ FORM::select('hall_id',[0=>'Cakto Sallen']+$HFC,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'hall_id']) }}

                    </div>
                    {{FORM::hidden('startTime',null,['id'=>'startTime'])}}
                    {{FORM::hidden('endTime',null,['id'=>'endTime'])}}

                    {{--{{ FORM::submit('Ruaj',['class'=>'btn btn-primary']) }}--}}
                </div>
                {{ FORM::close() }}
            </div>
                <div class="x_content">
                    <div id="calendar2"></div>
                </div>
            </div>
        </div>
    </div>
@stop