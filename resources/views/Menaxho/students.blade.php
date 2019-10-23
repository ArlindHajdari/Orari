@extends('layouts.master')

@section('title')
    Orari
@endsection

@section('other')
    <link href="{{asset('css/print.css')}}" rel="stylesheet" media="print">
    <link href="{{asset('css/print.css')}}" rel="stylesheet">

    <script type="text/javascript">
        $(document).ready(function(){

            $('#facultySelect').attr('disabled','disabled');
            $('#department_id').attr('disabled','disabled');
            $('#semester_id').attr('disabled','disabled');
            $('#printButton').attr('disabled','disabled');
            $('#tbody').html('');

            var faculty, department, date;

            var c = $('#semestri').is(':checked') ? '1' : '0';

            $.ajax({
                type: 'get',
                url: 'http://localhost:8000/getSemesters',
                data: {
                    'c':c
                },
                success:function(data){

                    console.log(data);
                    $("#datepicker").datepicker({
                        dateFormat:'yy-mm-dd',
                        minDate: data.start,
                        maxDate: data.end,
                        onSelect: function(dateText, inst){
                            var Date = $(this).val();
                            date = Date;
                            $('#facultySelect').removeAttr('disabled');
                            $('#facultySelect').val(0);
                            $('#department_id').attr('disabled','disabled');
                            $('#department_id').val(0);
                            $('#semester_id').attr('disabled','disabled');
                            $('#semester_id').val(0);
                            $('#department_id').html(' ');
                            $('#semester_id').html(' ');
                            $('#programi').html(' ');
                            $('#department').html(' ');
                            $('#viti').html(' ');
                            $('#tbody').html('');
                        }
                    });
                },
                error:function() {
                }
            });

            $('#semestri').click(function(){
                $('#tbody').html(' ');
                $('#datepicker').html(' ');
                $('#datepicker').datepicker('destroy');
                $('#facultySelect').val(0);
                $('#facultySelect').attr('disabled','disabled');
                $('#department_id').val(0);
                $('#department_id').attr('disabled','disabled');
                $('#department_id').html(' ');
                $('#semester_id').val(0);
                $('#semester_id').attr('disabled','disabled');
                $('#semester_id').html(' ');

                var c = $('#semestri').is(':checked') ? '1' : '0';

                $.ajax({
                    type: 'get',
                    url: 'http://localhost:8000/getSemesters',
                    data: {
                        'c':c
                    },
                    success:function (data) {

                        $("#datepicker").datepicker({
                            dateFormat:'yy-mm-dd',
                            minDate: data.start,
                            maxDate: data.end,
                            onSelect: function(dateText, inst){
                                var Date = $(this).val();
                                date = Date;
                                $('#facultySelect').removeAttr('disabled');
                                $('#facultySelect').val(0);
                                $('#department_id').attr('disabled','disabled');
                                $('#department_id').val(0);
                                $('#semester_id').attr('disabled','disabled');
                                $('#semester_id').val(0);
                                $('#department_id').html(' ');
                                $('#semester_id').html(' ');
                                $('#programi').html(' ');
                                $('#department').html(' ');
                                $('#viti').html(' ');
                                $('#tbody').html('');
                            }
                        });
                    },
                    error: function () {
                    }
                });
            });

            $('select[id="facultySelect"]').on('change',function () {
                $('#department_id').html(' ');
                $('#semester_id').html(' ');
                $('#programi').html(' ');
                $('#department').html(' ');
                $('#viti').html(' ');
                $('#tbody').html('');

                var facultySelect = $(this).val();
                var Programi = $("#facultySelect option:selected").text();
                faculty=facultySelect;

                if(facultySelect == 0){
                    $('#department_id').attr('disabled','disabled');
                    $('#department_id').val(0);
                    $('#semester_id').attr('disabled','disabled');
                    $('#semester_id').val(0);
                }else{
                    $('#semester_id').attr('disabled','disabled');
                    $('#semester_id').val(0);
                    $('#programi').append(Programi);
                    $('#department_id').removeAttr('disabled');

                    var option = "<option value='0' selected>Cakto Departamentin</option>";
                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getDepartmentByFaculty',
                        data:{'id':facultySelect},
                        success:function(data){
                            $.each(data, function(i,v){
                                option += "<option value='"+i+"'>"+v+"</option>";
                            });
                            $('#department_id').append(option);
                        },
                        error:function(){
                            console.log('Të dhënat nuk u gjeten!');
                        }
                    })
                }
            });

            $('select[id="department_id"]').on('change',function () {
                $('#semester_id').html(' ');
                $('#department').html(' ');
                $('#semestri').html(' ');
                $('#viti').html(' ');
                $('#tbody').html('');

                var department_id = $(this).val();
                var Departmenti = $("#department_id option:selected").text();
                var c = $('#semestri').is(':checked') ? '1' : '0';
                department=department_id;

                if(department_id == 0){
                    $('#semester_id').attr('disabled','disabled');
                    $('#semester_id').val(0);
                }else{
                    $('#semester_id').removeAttr('disabled');
                    $('#department').append(Departmenti);

                    var option = "<option value='0' selected>Cakto Semestrin</option>";

                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getSemesterByDepartment',
                        data:{
                            'id':department_id,
                            'c':c
                        },
                        success:function(data){
                            $.each(data, function(i,v){
                                option += "<option value='"+v+"'>"+v+"</option>";
                            });
                            $('#semester_id').append(option);
                        },
                        error:function(){
                            console.log('Të dhënat nuk u gjeten!');
                        }
                    })
                }
            });

            $('select[id="semester_id"]').on('change',function () {
                $('#semestri').html(' ');
                $('#viti').html(' ');
                $('#tbody').html(' ');
                $('#csemester').html(' ')

                var semester_id = $(this).val();

                if(semester_id == 0){
                    $('#printButton').attr('disabled','disabled');
                }else{
                    if(semester_id== '1' || semester_id=='2') {
                        $('#viti').append('1');
                    }else if(semester_id== '3' || semester_id=='4'){
                        $('#viti').append('2');
                    }else if(semester_id=='5' || semester_id=='6'){
                        $('#viti').append('3');
                    }else if(semester_id=='7' || semester_id=='8'){
                        $('#viti').append('4');
                    }else if(semester_id=='9' || semester_id=='10'){
                        $('#viti').append('5');
                    }else if(semester_id=='11' || semester_id=='12'){
                        $('#viti').append('6');
                    }

                    $('#csemester').append(semester_id);
                    $('#printButton').removeAttr('disabled');

                    var table = null;

                    $.ajax({
                        type:'get',
                        url: 'http://localhost:8000/showScheduleByData',
                        data: {
                            'faculty': faculty,
                            'department': department,
                            'semester': semester_id,
                            'date': date
                        },
                        success:function(data){
                            $.each(data.cps,function(i,cps){
                                var diqka = 1;
                                table += '<tr>';

                                table += '<td class="tbl">';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && diqka == 1){
                                        table += prof.subjecttype;
                                        table += '</td><td class="tbl">';
                                        table += prof.subject + '</br>';
                                        diqka = 2;
                                    }
                                });

                                $.each(data.res,function(i,res){
                                    if(cps.id == res.id){
                                        table += res.teacher + ' (' + res.lush +  ')' +'</br>';
                                    }
                                });
                                table += '</td>';

                                table += '<td>';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && prof.day_name == 'Monday'){
                                        table += prof.hall + '-' + prof.lush + '-' + prof.start_time + '-' + prof.end_time + ' ' + prof.group + '<br/>';
                                    }
                                });
                                table += '</td>';

                                table += '<td>';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && prof.day_name == 'Tuesday'){
                                        table += prof.hall + '-' + prof.lush + '-' + prof.start_time + '-' + prof.end_time + ' ' + prof.group + '<br/>';
                                    }
                                });
                                table += '</td>';

                                table += '<td>';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && prof.day_name == 'Wednesday'){
                                        table += prof.hall + '-' + prof.lush + '-' + prof.start_time + '-' + prof.end_time + ' ' + prof.group + '<br/>';
                                    }
                                });
                                table += '</td>';

                                table += '<td>';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && prof.day_name == 'Thursday'){
                                        table += prof.hall + '-' + prof.lush + '-' + prof.start_time + '-' + prof.end_time + ' ' + prof.group + '<br/>';
                                    }
                                });
                                table += '</td>';

                                table += '<td>';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && prof.day_name == 'Friday'){
                                        table += prof.hall + '-' + prof.lush + '-' + prof.start_time + '-' + prof.end_time + ' ' + prof.group + '<br/>';
                                    }
                                });
                                table += '</td>';

                                table += '<td>';
                                $.each(data.prof,function(i,prof){
                                    if(cps.id == prof.id && prof.day_name == 'Saturday'){
                                        table += prof.hall + '-' + prof.lush + '-' + prof.start_time + '-' + prof.end_time + ' ' + prof.group + '<br/>';
                                    }
                                });
                                table += '</td>';

                                table += '</tr>';
                            });
                            $('#tbody').append(table);
                        },
                        error:function () {
                        }
                    })
                }
            });
        });
    </script>
@endsection

@section('body')
<div class="noprint row" style="padding:75px 0px 20px" id="parentdiv">

    {{FORM::open(['novalidate','id'=>'showSchedule'])}}
    <div class="col-md-1">
        {{ FORM::label('semestri','Dimër/Verë',['class'=>'control-label']) }}
        <div>
            <label>
                {{FORM::checkbox('semestri',null,null,['id'=>'semestri','class'=>'js-switch',
                'data-switchery'=>'true','style'=>'display:none;'])}}
            </label>
        </div>
    </div>
    <div class="col-md-2">
        {{ FORM::label('Data','Data',['class'=>'control-label']) }}
        <div>
            <label>
                {{ FORM::text('datepicker',null,['class'=>'form-control','required','id'=>'datepicker']) }}
                {{--<input type="text" id="datepicker"></p>--}}
            </label>
        </div>
    </div>
    <div class="col-md-3">
        {{FORM::label('faculty','Cakto Fakultetin')}}
        {{FORM::select('faculty',['0'=>'Cakto Fakultetin']+$faculty,0,['class'=>'form-control','id'=>'facultySelect'])}}
    </div>
    <div class="col-md-3">
        {{FORM::label('department','Cakto Departmentin')}}
        <select name="department_id" id="department_id" class="form-control col-md-3 col-sm-6 col-xs-12','required">
        </select>
    </div>
    <div class="col-md-3">
        {{FORM::label('semester','Cakto Semestrin')}}
        <select name="semester_id" id="semester_id" class="form-control col-md-3 col-sm-6 col-xs-12','required">
        </select>
    </div>
        {{FORM::close()}}
    <div class="col-md-12">
        {{FORM::submit('Printo',['id'=>'printButton','class'=>'btn btn-success pull-left','onclick'=>'print()'])}}
    </div>
</div>

<div style="background-color: #FFFFFF; border-radius: 6px; padding: 5px">
    <div class="row">
        <div class="col-md-3" style="margin-left: 6%">
            <img class="wh" src="{{asset('images/kadrizeka.png')}}" width="120px" height="120px">
        </div>
        <div class="pullright col-md-6 text-center" style="color: #1c2b5f">
            <span class="r1" style="font-size: 20px; color: #1c2b5f">UNIVERSITETI "KADRI ZEKA" GJILAN</span><br>
            <span class="r2" style="font-size: 20px; color: #1c2b5f; font-weight: bold">
                FAKULTETI I SHKENCAVE KOMPJUTERIKE</span><br>
            <span class="r3" style="font-size: 16px; color: #1c2b5f; font-weight: bold">
                Programi:<span class="r3" id="programi"></span>
                -Specializimi <span class="r3" id="department"></span>
            </span><br>
            <span class="r4" style="font-size: 16px; color: #1c2b5f; font-weight: bold">Viti <span class="r4" id="viti"></span> - Semestri <span class="r4" id="csemester"></span> 2016/2017</span><br>
            <span class="r5" style="font-size: 14px; color: #1c2b5f">Orari i ligjëratave dhe ushtrimeve</span>
        </div>
    </div>

    <div class="row" style="padding: 10px 0px">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr class="tbl">
                    <th width="1%">O/Z</th>
                    <th width="18%">LËNDË / MËSIMDHËNËSI / ASISTENTI</th>
                    <th width="13.5%" class="text-center">E HËNË</th>
                    <th width="13.5%" class="text-center">E MARTË</th>
                    <th width="13.5%" class="text-center">E MËRKURË</th>
                    <th width="13.5%" class="text-center">E ENJTE</th>
                    <th width="13.5%" class="text-center">E PREMTE</th>
                    <th width="13.5%" class="text-center">E SHTUNË</th>
                </tr>
                <tbody id="tbody">
                </tbody>
            </table>
        </div>
        <div class="fr1 col-md-12" style="padding-left: 60px">
            Gr X - Grupet e Ligjëratave/Ushtrimeve, L - Ligjërata, U - Ushtrime, O - Obligative, Z - Zgjedhore<br>
            Semestri fillon më 20 shkurt 2017 dhe mbaron më 31 maj 2017.
        </div>
    </div>
</div>
@endsection
