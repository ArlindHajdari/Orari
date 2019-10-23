@extends('layouts.master')
@section('title')
    Orari
@stop
@section('other')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="{{asset('sweetalert2/dist/sweetalert2.css')}}" rel="stylesheet">
    <script src="{{asset('sweetalert2/dist/sweetalert2.js')}}"></script>
    <style type="text/css">
        .genetics_identifier{
            display:none;
        }

        .block a:hover{
            color: silver;
        }
        .block a{
            color: #fff;
        }
        .block {
            position: fixed;
            background: #2184cd;
            padding: 20px;
            z-index: 1;
            top: 240px;
            opacity: 0.5;
        }
        #calendar3{
            opacity: 0.2;
        }
        #calendar2{
            opacity: 1;
        }

        div.relative {
            position: relative;
            height: 0px;
            border: none;
        }

        div.absolute {
            position: absolute;
            top: 80px;
            right: 0;
            width: 1258px;
            height: 0px;
            border: none;
        }
        .btn-circle {
          width: 30px;
          height: 30px;
          text-align: center;
          padding: 6px 0;
          font-size: 12px;
          line-height: 1.428571429;
          border-radius: 15px;
        }

        .show{
            display:block;
        }

        .hide{
            display: none;
        }

        .radio{
            margin-top: 0px !important;
        }

        .radio label{
            padding-left: 0px !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('.radio_genetics').on('click',function(e){

                if(($('.iradio_flat-green').hasClass('checked') || $('.iradio_flat-green .iradio_flat-green').hasClass('checked')) && $(this).find('input[type="radio"][name="genetics"]').val() == 'genetics' ){
                    $('.genetics_identifier').fadeIn();
                }else{
                    $('.genetics_identifier').fadeOut();
                }
            });

            var isFullRequest = null;
            $('#prof_id').attr('disabled','disabled');
            $('#lu_id').attr('disabled','disabled');
            $('#gr_id').attr('disabled','disabled');
            $('#subject_id').attr('disabled','disabled');

            $('#subject_id').html(' ');
            $('#prof_id').html(' ');
            $('#gr_id').html(' ');
            $('#lu_id').html(' ');
            $('#semestri').prop('checked',false);
            var max_hours = null;
            var c = $('#semestri').checked ? 1 : 0;

            $('#subject_id').removeAttr('disabled');

            var option = "<option value='0' selected>Cakto lëndën</option>";
            $.ajax({
                type: 'get',
                url: 'http://localhost:8000/getLendetFromSemester',
                data: {
                    'semester': c
                },
                success:function(data){
                    $.each(data, function(i,v){
                        option += "<option " +
                                "value='"+i+"'>"+v+"</option>";
                    });
                    $('#subject_id').append(option);
                },
                error:function(){
                    BootstrapDialog.show({
                        title: 'Gabim gjatë marrjes së të dhënave!',
                        message: 'Të dhënat per lush nuk u gjeten!',
                        buttons: [{
                            label: 'OK',
                            action: function (dialog) {
                                $('#lu_id').attr('disabled','disabled');
                                $('#lu_id').val('0');
                                $('#prof_id').attr('disabled','disabled');
                                $('#prof_id').val('0');
                                $('#gr_id').attr('disabled','disabled');
                                $('#gr_id').val('0');
                                $('#hall_id').attr('disabled','disabled');
                                $('#hall_id').val('0');
                                $('#calendar2').fullCalendar( 'destroy' );
                                $('#calendar3').fullCalendar( 'destroy' );
                                dialog.close();
                            }
                        }]
                    });
                }
            });

            $('#semestri').click(function(){
                $('#subject_id').html(' ');
                $('#prof_id').html(' ');
                $('#gr_id').html(' ');
                $('#lu_id').html(' ');
                $('#lu_id').attr('disabled','disabled');
                $('#prof_id').attr('disabled','disabled');
                $('#gr_id').attr('disabled','disabled');
                isFullRequest = !($('#prof_id').is(':disabled') || $('#subject_id').is(':disabled') || $('#lu_id').is(':disabled'));
                $('#calendar2').fullCalendar( 'destroy' );
                $('#calendar3').fullCalendar( 'destroy' );
                var c = this.checked ? 1 : 0;
                $('#createEventModal input[name=semestri]').val(c);
                $('#subject_id').removeAttr('disabled');

                var option = "<option value='0' selected>Cakto lëndën</option>";
                $.ajax({
                    type: 'get',
                    url: 'http://localhost:8000/getLendetFromSemester',
                    data: {
                        'semester': c
                    },
                    success:function(data){
                        $.each(data, function(i,v){
                            option += "<option " +
                                    "value='"+i+"'>"+v+"</option>";
                        });
                        $('#subject_id').append(option);
                    },
                    error:function(){
                        BootstrapDialog.show({
                            title: 'Gabim gjatë marrjes së të dhënave!',
                            message: 'Të dhënat per lush nuk u gjeten!',
                            buttons: [{
                                label: 'OK',
                                action: function (dialog) {
                                    $('#lu_id').attr('disabled','disabled');
                                    $('#lu_id').val('0');
                                    $('#prof_id').attr('disabled','disabled');
                                    $('#prof_id').val('0');
                                    $('#gr_id').attr('disabled','disabled');
                                    $('#gr_id').val('0');
                                    $('#hall_id').attr('disabled','disabled');
                                    $('#hall_id').val('0');
                                    $('#calendar2').fullCalendar( 'destroy' );
                                    $('#calendar3').fullCalendar( 'destroy' );
                                    dialog.close();
                                }
                            }]
                        });
                    }
                });
            });

            $('select[id="subject_id"]').on('change',function(){
                $('#prof_id').html(' ');
                $('#gr_id').html(' ');
                $('#lu_id').html(' ');
                isFullRequest = !($('#prof_id').is(':disabled') || $('#subject_id').is(':disabled') || $('#lu_id').is(':disabled'));
                $('#calendar2').fullCalendar( 'destroy' );
                $('#calendar3').fullCalendar( 'destroy' );
                var caktoLende = $(this).val();

                if(caktoLende == 0){
                    $('#prof_id').attr('disabled','disabled');
                    $('#lu_id').attr('disabled','disabled');
                    $('#gr_id').attr('disabled','disabled');
                    $('#prof_id').val('0');
                    $('#hall_id').val('0');
                    $('#lu_id').val('0');
                    $('#gr_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                    $('#calendar3').fullCalendar( 'destroy' );
                }else {

                    $('#lu_id').removeAttr('disabled');
                    var option = "<option value='0' selected>Cakto L/U</option>";
                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getlushByLende',
                        data: {
                            id: $(this).val()
                        },
                        success:function(data){
                            $.each(data, function(i,v){
                                option += "<option " +
                                        "value='"+i+"'>"+v+"</option>";
                            });
                            $('#lu_id').append(option);
                        },
                        error:function(){
                            BootstrapDialog.show({
                                title: 'Gabim gjatë marrjes së të dhënave!',
                                message: 'Të dhënat per lush nuk u gjeten!',
                                buttons: [{
                                    label: 'OK',
                                    action: function (dialog) {
                                        $('#lu_id').attr('disabled','disabled');
                                        $('#lu_id').val('0');
                                        $('#prof_id').attr('disabled','disabled');
                                        $('#prof_id').val('0');
                                        $('#gr_id').attr('disabled','disabled');
                                        $('#gr_id').val('0');
                                        $('#hall_id').attr('disabled','disabled');
                                        $('#hall_id').val('0');
                                        $('#calendar2').fullCalendar( 'destroy' );
                                        $('#calendar3').fullCalendar( 'destroy' );
                                        dialog.close();
                                    }
                                }]
                            });
                        }
                    });
                }
            });

            $('select[id="lu_id"]').on('change',function(){
                $('#prof_id').html(' ');
                var lu_id = $(this).val();
                isFullRequest = ($('#prof_id').is(':disabled') && $('#subject_id').is(':disabled') && $('#lu_id').is(':disabled'));
                $('#calendar2').fullCalendar( 'destroy' );
                $('#calendar3').fullCalendar( 'destroy' );
                if(lu_id == 0){
                    $('#prof_id').attr('disabled','disabled');
                    $('#prof_id').val('0');
                    $('#gr_id').attr('disabled','disabled');
                    $('#gr_id').val('0');
                    $('#hall_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                    $('#calendar3').fullCalendar( 'destroy' );
                }else {
                    $('#prof_id').removeAttr('disabled');

                    var option = "<option value='0' selected>Cakto mësimdhënësin</option>";

                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getProfByLUSHandSubject',
                        data:{
                            'subject_id':$('#subject_id').val(),
                            'lush_id':$('#lu_id').val()
                        },
                        success:function(data){
                            $.each(data, function(i,v){
                                option += "<option " +
                                        "value='"+i+"'>"+v+"</option>";
                            });
                            $('#prof_id').append(option);
                        },
                        error:function(){
                            BootstrapDialog.show({
                                title: 'Gabim gjatë marrjes së të dhënave!',
                                message: 'Të dhënat per mesimdhenes nuk u gjeten!',
                                buttons: [{
                                    label: 'OK',
                                    action: function (dialog) {
                                        $('#prof_id').attr('disabled','disabled');
                                        $('#prof_id').val('0');
                                        $('#gr_id').attr('disabled','disabled');
                                        $('#gr_id').val('0');
                                        $('#hall_id').attr('disabled','disabled');
                                        $('#hall_id').val('0');
                                        $('#calendar2').fullCalendar('destroy');
                                        $('#calendar3').fullCalendar('destroy');
                                        dialog.close();
                                    }
                                }]
                            });
                        }
                    });
                }
            });

            $('select[id="prof_id"]').on('change',function(){
                $('#gr_id').html(' ');
                $('#hall_id').val('0');
                var caktoLU = $(this).val();
                $('#calendar2').fullCalendar( 'destroy' );
                $('#calendar3').fullCalendar( 'destroy' );
                isFullRequest = !($('#prof_id').is(':disabled') || $('#subject_id').is(':disabled') || $('#lu_id').is(':disabled'));
                if(caktoLU == 0){
                    $('#gr_id').attr('disabled','disabled');
                    $('#gr_id').val('0');
                    $('#hall_id').val('0');
                    $('#calendar2').fullCalendar( 'destroy' );
                    $('#calendar3').fullCalendar( 'destroy' );
                }else {
                    $('#hall_id').removeAttr('disabled');
                    var option = "<option selected value='0'>Grupi</option>";
                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getGroupByLende',
                        data: {
                            'subject_id':$('#subject_id').val(),
                            'prof_id':$('#prof_id').val(),
                            'lush_id':$('#lu_id').val()
                        },
                        success:function(data){
                            $.each(data, function(i,v){
                                option += "<option value='"+i+"'>"+v+"</option>";
                            });
                            $('#gr_id').append(option);
                        },
                        error:function(){
                            BootstrapDialog.show({
                                title: 'Gabim gjatë marrjes së të dhënave!',
                                message: 'Të dhënat për grup nuk u gjeten!',
                                buttons: [{
                                    label: 'OK',
                                    action: function (dialog) {
                                        $('#gr_id').attr('disabled','disabled');
                                        $('#gr_id').val('0');
                                        $('#hall_id').attr('disabled','disabled');
                                        $('#hall_id').val('0');
                                        $('#calendar2').fullCalendar( 'destroy' );
                                        $('#calendar3').fullCalendar( 'destroy' );
                                        dialog.close();
                                    }
                                }]
                            });
                        }
                    });
                }
            });

            $('select[id="hall_id"]').on('change',function(){
                var hall = $(this).val();
                $('#calendar2').fullCalendar( 'destroy' );
                $('#calendar3').fullCalendar( 'destroy' );
                if(hall == 0){
                    $('#gr_id').attr('disabled','disabled');
                    $('#gr_id').val('0');
                }
                else{
                    $('#gr_id').removeAttr('disabled');
                    $('#semester').val($('#semestri').is(':checked'));
                    $('#OrariRegister').submit();
                }
            });

            $('#OrariRegister').submit(function(e){
                e.preventDefault();

                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });
                isFullRequest = !($('#prof_id').is(':disabled') || $('#subject_id').is(':disabled') || $('#lu_id').is(':disabled'));

                $.ajax({
                    type:'POST',
                    url:$(this).attr('action'),
                    data:$(this).serialize()+
                    '&semester='+$('#semestri').is(':checked'),
                    success: function(data){
                        json = $.parseJSON(data);
                        var availability = json.availability;
                        max_hours = json.max_hours;
                        limits = json.limits;

                        $.each(availability, function(i,v){
                            if(v.allowed == false){
                                v.color = '#a30606';
                            }else{
                                v.color = '#333';
                            }
                        });

                        $('#calendar3').fullCalendar({
                            header:false,
                            selectable: true,  // perdoruesi mund ti selektoj kohen dhe diten
                            selectHelper: true,
                            slotEventOverlap: false,
                            hiddenDays: [0],                   // fsheh te Dielen
                            columnFormat: 'dddd',
                            allDaySlot: false,                  // tere dita slot
                            minTime: limits.start,
                            maxTime: limits.end,
                            defaultView: 'agendaWeek',
                            defaultDate: '2017-04-18',
                            navLinks: false,                    // can click day/week names to navigate views
                            editable: true,
                            events: availability,
                            eventLimit: true,                   // allow "more" link when too many events
                            draggable: false,					// te zhvendosshme
                            eventDurationEditable: true,
                            selectOverlap: false,				// me selektu evente mbi njera tjetren
                            eventOverlap: false,
                            displayEventTime: false,
                            slotDuration: '00:15:00',
                            eventStartEditable: false,
                            eventConstraint:{
                                start: limits.start,
                                end: limits.end
                            },
                            visibleRange:{
                                start: "2017-04-17",
                                end: "2017-04-22"
                            },
                            height: 1218,
                            locale:'sq',

                        });


                        $('#calendar2').fullCalendar({
                            header:false,
                            selectable: true,  // perdoruesi mund ti selektoj kohen dhe diten
                            selectHelper: true,
                            slotEventOverlap: false,
                            hiddenDays: [0],                   // fsheh te Dielen
                            columnFormat: 'dddd',
                            allDaySlot: false,                  // tere dita slot
                            minTime: "08:00:00",
                            maxTime: "20:00:00",
                            defaultView: 'agendaWeek',
                            defaultDate: '2017-04-18',
                            navLinks: false,                    // can click day/week names to navigate views
                            editable: true,
                            events: json.schedule,
                            eventLimit: true,                   // allow "more" link when too many events
                            draggable: true,					// te zhvendosshme
                            eventDurationEditable: true,
                            selectOverlap: false,				// me selektu evente mbi njera tjetren
                            eventOverlap: false,				// zevendesim pa ngaterresa
                            slotDuration: '00:15:00',
                            eventStartEditable  : true,
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
                            eventClick:  function(event, jsEvent, view) {
                                endtime = $.fullCalendar.moment(event.end).format('h:mm');
                                starttime = $.fullCalendar.moment(event.start).format('dddd, h:mm');
                                if(event.editable == true){
                                    var mywhen = starttime + ' - ' + endtime; // nese klikohet eventi na shfaqet modali me detajet(oren e fillimit edhe mbarimit)
                                    $('#modalTitle').html(event.title);
                                    $('#modalWhen').text(mywhen);
                                    $('#avail_id').val(event.id);
                                    $('#calendarModal').modal();
                                }else{
                                    return false;
                                }

                            },
                            select: function(start, end, jsEvent) {
                                console.log(isFullRequest);
                                if(!isFullRequest){
                                    $('#calendar2').fullCalendar('unselect');
                                    return false;
                                }

                                var mEnd = $.fullCalendar.moment(end);
                                var mStart = $.fullCalendar.moment(start);

                                $.ajax({
                                    type: 'POST',
                                    data: {
                                        'start':mStart.format(),
                                        'end':mEnd.format(),
                                        'user_id':$('#prof_id').val(),
                                        'lush_id':$('#lu_id').val(),
                                        'subject_id':$('#subject_id').val(),
                                        '_token': '{{csrf_token()}}',
                                        'semester': $('#semestri').is(':checked')
                                    },
                                    url: 'http://localhost:8000/getMaxHourPerDay',
                                    success: function(e){
                                        mEnd = $.fullCalendar.moment(e.end);
                                        mStart = $.fullCalendar.moment(e.start);

                                        var differencua = mEnd.diff(mStart,'minutes');

                                        if (mEnd.isAfter(mStart, 'day') || differencua <= 44 || !(differencua <= max_hours) || !(differencua <= e.max_minutes_limit)) {
                                            $('#calendar2').fullCalendar('unselect');
                                        } else {
                                            endtime = $.fullCalendar.moment(end).format('h:mm');
                                            starttime = $.fullCalendar.moment(start).format('dddd, h:mm');
                                            var mywhen = starttime + ' - ' + endtime;
                                            var user = $('#prof_id').val(), lush_id= $('#lu_id').val(), group_id=$('#gr_id').val(), hall_id = $('#hall_id').val(), subject_id = $('#subject_id').val();
                                            start = moment(start).format();
                                            end = moment(end).format();	// nese caktohen oret na shfaqet modali me oren e fillimit
                                            // edhe mbarimit
                                            $('#createEventModal #start').val(start);
                                            $('#createEventModal #end').val(end);
                                            $('#createEventModal #when').text(mywhen);
                                            $('#createEventModal input[name=user_id]').val(user);
                                            $('#createEventModal .user_id').text($('#prof_id option[value='+user+']').text());
                                            $('#createEventModal input[name=lush_id]').val(lush_id);
                                            $('#createEventModal .lush_id').text($('#lu_id option[value='+lush_id+']').text());
                                            $('#createEventModal input[name=group_id]').val(group_id);
                                            $('#createEventModal .group_id').text($('#gr_id option[value='+group_id+']').text());
                                            $('#createEventModal input[name=hall_id]').val(hall_id);
                                            $('#createEventModal .hall_id').text($('#hall_id option[value='+hall_id+']').text());
                                            $('#createEventModal input[name=subject_id]').val(subject_id);
                                            $('#createEventModal .subject_id').text($('#subject_id option[value='+subject_id+']').text());
                                            $('#createEventModal').modal('toggle');
                                        }
                                    },
                                    error: function(e){
                                        BootstrapDialog.show({
                                            title: 'Gabim',
                                            message: 'Profesori ka arritur orët e lejuara!',
                                            buttons: [{
                                                label: 'OK',
                                                action: function(dialog) {
                                                    dialog.close();
                                                }
                                            }]
                                        });
                                    }
                                });
                            },
                            eventDrop: function(event){
                                $.ajax({
                                    url: 'http://localhost:8000/edit-schedule/'+event.id,
                                    data: {
                                        id: event.id,
                                        start: moment(event.start).format(),
                                        end: moment(event.end).format(),
                                        _token: '{{csrf_token()}}',
                                        semester: $('#semestri').is(':checked')
                                    },
                                    type: "PATCH",
                                    success: function(json) {
                                        //code here
                                    },
                                    error: function(json){
                                        BootstrapDialog.show({
                                            title: json.responseJSON['title'],
                                            message: json.responseJSON['msg'],
                                            buttons: [{
                                                label: 'OK',
                                                action: function(dialog) {
                                                    dialog.close();
                                                    $('#calendar2').fullCalendar( 'destroy' );
                                                    $('#calendar3').fullCalendar( 'destroy' );
                                                    $('#OrariRegister').submit();
                                                }
                                            }]
                                        });
                                    }
                                });
                            },
                            eventRender: function(jsEvent,element, event){
                                if(jsEvent.editable == false){
                                    jsEvent.startEditable = false;
                                    jsEvent.durationEditable = false;
                                }
                                return element
                            },
                            eventResize: function(event) {
                                $.ajax({
                                    url: 'http://localhost:8000/edit-schedule/'+event.id,
                                    data: {
                                        id: event.id,
                                        start: moment(event.start).format(),
                                        end: moment(event.end).format(),
                                        _token: '{{csrf_token()}}',
                                        semester: $('#semestri').is(':checked')
                                    },
                                    type: "PATCH",
                                    success: function(json) {
                                        //code here
                                    },
                                    error: function(json){
                                        BootstrapDialog.show({
                                            title: json.responseJSON['title'],
                                            message: json.responseJSON['msg'],
                                            buttons: [{
                                                label: 'OK',
                                                action: function(dialog) {
                                                    dialog.close();
                                                    $('#calendar2').fullCalendar( 'destroy' );
                                                    $('#calendar3').fullCalendar( 'destroy' );
                                                    $('#OrariRegister').submit();
                                                }
                                            }]
                                        });
                                    }
                                });
                            }
                        });
                    },
                    error: function(data){
                        //
                    }
                });
            });

            $('#store-schedule').submit(function(e){
                e.preventDefault();

                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });

                var forma = $(this), url=forma.attr('action'),formData = forma.serialize();

                $.ajax({
                    url: url,
                    data: formData,
                    type: "POST",
                    success: function(json) {
                        max_hours = json.max_hours;
                        $("#calendar2").fullCalendar('renderEvent',
                                {
                                    id: json.id,
                                    start: json.start,
                                    end: json.end,
                                    title: json.title,
                                    editable: true
                                },
                                true);
                    },
                    error: function(data){
                        BootstrapDialog.show({
                            title: data.responseJSON['title'],
                            message: data.responseJSON['msg'],
                            buttons: [{
                                label: 'OK',
                                action: function(dialog) {
                                    dialog.close();
                                }
                            }]
                        });
                    }
                });
                $("#createEventModal").modal('hide');
            });

            $('#delete-schedule').submit(function(e){
                e.preventDefault();

                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });

                var ID = $('#avail_id').val();
                var form = $(this);
                var url = form.attr('action');
                $.ajax({
                    url: url,
                    data: {
                        'id': ID,
                        'subject_id': $('#subject_id').val(),
                        'lush_id': $('#lu_id').val(),
                        'user_id': $('#prof_id').val(),
                        'semester': $('#semestri').is(':checked')
                    },
                    type: "DELETE",
                    success: function(json) {
                        if(json.success){
                            max_hours = json.max_hours;
                            $("#calendar2").fullCalendar('removeEvents',ID);
                        } else
                            return false;
                    }
                });

                $("#calendarModal").modal('hide');
            });

            $('#generate-schedule').on('submit',function(e){
                e.preventDefault();
                var dialog = new BootstrapDialog({
                    message: function(dialog){
                        var message = $('<center><h3>Duke u ngarkuar</h3><center>');

                        return message;
                    },
                    closable: false,
                });
                dialog.realize();
                dialog.getModalHeader().hide();
                dialog.getModalFooter().hide();
                dialog.getModalBody().css('background-color', '#0088cc');
                dialog.getModalBody().css('color', '#fff');

                $.ajaxSetup({
                    headers:{
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    }
                });

                $.ajax({
                    type: 'POST',
                    data: $(this).serialize(),
                    url: $(this).attr('action'),
                    beforeSend: function(){
                        dialog.open();
                    },
                    complete: function(){
                        dialog.close();
                    },
                    success: function(data){
                        if(data.genetic){
                            BootstrapDialog.show({
                                title: data.title,
                                message: function(){
                                    var $message = "<div>Rezultatet e mësimdhënësve për orarin e gjeneruar<br><br>";
                                    $.each(data.msg, function(i,v){
                                        $message += v[0]+": "+Math.floor(v[1])+"%"+"<br>";
                                    });
                                    $message += "</div>";
                                    return $($message);
                                },
                                buttons: [{
                                    label: 'OK',
                                    action: function(dialog) {
                                        window.location.reload();
                                    }
                                }]
                            });
                        }else{
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
                    },
                    error: function(data){
                        if(data.responseJSON['server']){
                            swal(
                              data.responseJSON['title'],
                              data.responseJSON['msg'],
                              'error'
                            );
                        }else{
                            $(this).find('#semester').tooltip({title: data.responseJSON['msg'],placement: "right"}).tooltip('show');
                        }
                    }
                });
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
                {{FORM::open(['id'=>'store-schedule','url'=>'store-schedule'])}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Shto në orar</h4>
                </div>
                <div class="modal-body">
                    {{FORM::hidden('start',null,['id'=>'start'])}}
                    {{FORM::hidden('end',null,['id'=>'end'])}}
                    {{FORM::hidden('hall_id',null)}}
                    {{FORM::hidden('user_id',null)}}
                    {{FORM::hidden('lush_id',null)}}
                    {{FORM::hidden('group_id',null)}}
                    {{FORM::hidden('subject_id',null)}}
                    {{FORM::hidden('semestri','0')}}
                    <div class="control-group">
                        <label class="control-label" for="when">Kur:</label>
                        <div class="controls controls-row" id="when" style="margin-top:5px;">
                        </div>
                        <label class="control-label" for="subject_id">Lënda:</label>
                        <div class="controls controls-row subject_id" style="margin-top:5px;">
                        </div>
                        <label class="control-label" for="lush_id">Ligjëratë/Ushtrime:</label>
                        <div class="controls controls-row lush_id" style="margin-top:5px;">
                        </div>
                        <label class="control-label" for="user_id">Mësimdhënësi:</label>
                        <div class="controls controls-row user_id" style="margin-top:5px;">
                        </div>
                        <label class="control-label" for="group_id">Grupi:</label>
                        <div class="controls controls-row group_id" style="margin-top:5px;">
                        </div>
                        <label class="control-label" for="hall_id">Salla:</label>
                        <div class="controls controls-row hall_id" style="margin-top:5px;">
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
                {{FORM::open(['id'=>'delete-schedule','novalidate','method'=>'delete','url'=>'delete-schedule'])}}
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
    <div class="modal fade" id="deleteModal">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body" style="padding:25px 10px">
                    <div align="middle">
                        {{FORM::open(['id'=>'delete-form','method'=>'DELETE','url'=>'deleteSchedulers'])}}
                        <div class="form-group">
                            <p class="modal-title" style="font-size: 16px;">A jeni të sigurt që dëshironi të fshini?</p>
                            <br>
                            <button href="#" onclick="document.getElementById('delete-form').submit()" class="btn
                            btn-success">Po
                            </button>
                            <button data-dismiss="modal" class="btn btn-danger">Jo</button>
                        </div>
                        {{FORM::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="generatorModal" class="modal fade" style="backdrop-filter: blur(10px)">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Gjenerimi i orarit</h4>
                </div>
                <div class="modal-body">
                    {{FORM::open(['id'=>'generate-schedule','novalidate','url'=>'generateScheduler'])}}
                    <div class="row">
                        <label class="col-md-4 col-sm-4 col-xs-12 control-label">Semestri:</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                {{FORM::select('semester',[0=>'Cakto semestrin']+$semesters,0,['class'=>'form-control','required','style'=>'border-radius:2px','id'=>'semester'])}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-md-4 col-sm-4 col-xs-12 control-label">Mënyra e gjenerimit:</label>
                        <div class="col-md-8 col-sm-8 col-xs-12">
                            <div class="form-group">
                                <div class="radio">
                                    <label class="radio_genetics">
                                      <div class="iradio_flat-green" style="position: relative;">
                                          <input type="radio" class="flat" checked="false" name="genetics" value="genetics" style="position: absolute; opacity: 0;">
                                          <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                      </div> Gjenetikë
                                    </label>
                                  </div>
                                  <div class="radio">
                                      <label class="radio_genetics">
                                        <div class="iradio_flat-green" style="position: relative;">
                                            <input type="radio" class="flat" checked="true" name="genetics" value="linear" style="position: absolute; opacity: 0;">
                                            <ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins>
                                        </div> Linearë
                                      </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row genetics_identifier">
                        <label class="col-md-4 col-sm-4 col-xs-12 control-label">Shkalla e crossover-it:</label>
                		<div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::number('crossover',0.25,['class'=>'form-control text-center','min'=>'0.1','max'=>'0.9','step'=>'0.05'])}}
                		</div>
                	</div>
                    <div class="row genetics_identifier">
                        <label class="col-md-4 col-sm-4 col-xs-12 control-label">Shkalla e mutacionit:</label>
                		<div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::number('mutation',0.01,['class'=>'form-control text-center','min'=>'0.01','max'=>'0.9','step'=>'0.01'])}}
                		</div>
                	</div>
                    <div class="row genetics_identifier">
                        <label class="col-md-4 col-sm-4 col-xs-12 control-label">Numri i iterimeve:</label>
                		<div class="col-md-8 col-sm-8 col-xs-12">
                                {{FORM::number('iteration',100,['class'=>'form-control text-center','min'=>'100','max'=>'10000','step'=>'50'])}}
                		</div>
                	</div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <div class="col-md-7 col-sm-7 col-xs-12 col-md-offset-5">
                            {{FORM::submit('Gjenero',['class'=>'btn btn-info pull-right'])}}
                        </div>
                    </div>
                </div>
                {{FORM::close()}}
            </div>
        </div>
    </div>
    <div class="x_title">
        <h2>Orari</h2>
        <button type="button" class="btn btn-danger btn-md pull-right" data-toggle="modal"
                data-target="#deleteModal">Fshij
        </button>
        <div class="clearfix"></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="row">
                    {{ FORM::open(['id'=>'OrariRegister']) }}
                    <div class="col-md-1">
                        {{ FORM::label('semestri','Dimër/Verë',['class'=>'control-label']) }}
                        <div class="">
                            <label>
                                {{FORM::checkbox('semestri',null,null,['id'=>'semestri','class'=>'js-switch',
                        'data-switchery'=>'true','style'=>'display:none;'])}}
                                {{FORM::hidden('semester',null,['id'=>'semester'])}}
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{ FORM::label('Lenda',null,['class'=>'control-label']) }}
                        <select name="subject_id" id="subject_id" class="form-control"
                                required data-live-search="true">
                        </select>
                        {{--{{ FORM::select('subject_id',[0=>'Cakto Lenden']+$profSub,0,['class'=>'selectpicker form-control','required','id'=>'subject_id','data-live-search'=>'true']) }}--}}
                    </div>

                    <div class="col-md-2">
                        {{ FORM::label('L/U',null,['class'=>'control-label']) }}
                        <select name="lu_id" id="lu_id" class="form-control col-md-8 col-sm-8 col-xs-12"
                                required>
                        </select>
                        {{--{{ FORM::select('lu_id',null,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'lu_id']) }}--}}
                    </div>
                    <div class="col-md-3">
                        {{ FORM::label('Mësimdhënësi',null,['class'=>'control-label']) }}
                        <select name="prof_id" id="prof_id" class="form-control col-md-8 col-sm-8 col-xs-12"
                                required>
                        </select>
                        {{--{{ FORM::select('prof_id','',0,['class'=>'form-control shkas col-md-8 col-sm-8 col-xs-12','required','id'=>'prof_id']) }}--}}
                    </div>
                    <div class="col-md-3">
                        <div class="col-md-5" style="padding-left:0px">
                            {{ FORM::label('Salla',null,['class'=>'control-label']) }}
                            {{ FORM::select('hall_id',[0=>'Salla']+$HFC2,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'hall_id']) }}
                        </div>
                        <div class="col-md-5">
                            {{ FORM::label('Grupi',null,['class'=>'control-label']) }}
                            <select name="gr_id" id="gr_id" class="form-control col-md-8 col-sm-8 col-xs-12">
                            </select>
                            {{--{{ FORM::select('gr_id',null,0,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','id'=>'gr_id']) }}--}}
                        </div>
                        <div class="col-md-2">
                            {{ FORM::label("CSP",null,['class'=>'control-label']) }}
                            <button type="button" class="btn btn-primary btn-circle" data-toggle="modal" data-target="#generatorModal">
                              <i class="glyphicon glyphicon-record"></i>
                            </button>
                            <!-- <button type="button" style="color:green" data-toggle="modal" class="glyphicon glyphicon-record" data-target="#generatorModal"></button> -->
                        </div>
                    </div>
                    {{ FORM::close() }}
                </div>
            </div>
            <div class="x_content">
                <div id="calendar2" class="relative"></div>
                <div id="calendar3"></div>
            </div>
        </div>
    </div>
</div>
@stop
