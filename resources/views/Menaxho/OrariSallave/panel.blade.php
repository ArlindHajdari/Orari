@extends('layouts.master')

@section('title')
    Orari
@endsection

@section('other')
    <link href="{{asset('css/printHall.css')}}" rel="stylesheet" media="print">

    <script type="text/javascript">
        $(document).ready(function(){
            $('#hallsTable').html(' ');

            $('select[id="halls_id"]').on('change', function(){
                $('#hallsTable').html(' ');
                $('#salla').html(' ');
                $('#salla').text($('#halls_id option:selected').text());

                var c = $('#semestri').is(':checked') ? '1' : '0';
                console.log(c);

                var hall_id = $(this).val();

                if (hall_id == 0) {
                    $('#hallsTable').html(' ');
                }else{
                    var halls;
                    $.ajax({
                        type: 'get',
                        url: 'http://localhost:8000/getHallsSchedule',
                        data: {
                            'semester':c,
                            'hall_id':hall_id
                        },
                        success:function(data){

                            $.each(data,function (i,val) {
                                halls += "<tr>";

                                if(val.day_time == "Monday"){
                                    halls += "<td>"+val.subject+"</td>";
                                    halls += "<td>"+val.teacher+"</td>";
                                    halls += "<td>"+val.start_time+"</td>";
                                    halls += "<td>"+val.end_time+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                }else if(val.day_time == "Tuesday"){
                                    halls += "<td>"+val.subject+"</td>";
                                    halls += "<td>"+val.teacher+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td>"+val.start_time+"</td>";
                                    halls += "<td>"+val.end_time+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                }else if(val.day_time == "Wednesday"){
                                    halls += "<td>"+val.subject+"</td>";
                                    halls += "<td>"+val.teacher+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td>"+val.start_time+"</td>";
                                    halls += "<td>"+val.end_time+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                }else if(val.day_time == "Thursday"){
                                    halls += "<td>"+val.subject+"</td>";
                                    halls += "<td>"+val.teacher+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td>"+val.start_time+"</td>";
                                    halls += "<td>"+val.end_time+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                }else if(val.day_time == "Friday"){
                                    halls += "<td>"+val.subject+"</td>";
                                    halls += "<td>"+val.teacher+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td>"+val.start_time+"</td>";
                                    halls += "<td>"+val.end_time+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                }else if(val.day_time == "Saturday"){
                                    halls += "<td>"+val.subject+"</td>";
                                    halls += "<td>"+val.teacher+"</td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td></td>";
                                    halls += "<td>"+val.start_time+"</td>";
                                    halls += "<td>"+val.end_time+"</td>";
                                }
                                halls += "</tr>";
                            })
                            $('#hallsTable').append(halls);
                        },
                        error:function(){
                        console.log('Të dhënat nuk u gjeten!');
                        }
                    })
                }
            });

            $('#semestri').click(function(){
                $('#hallsTable').html(' ');
                $('#halls_id').val(0);
            });
        });
    </script>

@endsection

@section('body')

<div class="noprint row" style="padding:75px 0px 20px" id="parentdiv">
    {{FORM::open(['novalidate','id'=>'hallsSchedule'])}}
    <div class="col-md-1">
        {{ FORM::label('semestri','Dimër/Verë',['class'=>'control-label']) }}
        <div>
            <label>
                {{FORM::checkbox('semestri',null,null,['id'=>'semestri','class'=>'js-switch',
                'data-switchery'=>'true','style'=>'display:none;'])}}
            </label>
        </div>
    </div>
    <div class="col-md-3">
        {{FORM::label('halls','Salla')}}
        {{FORM::select('halls',['0'=>'Zgjedh Sallën']+$halls,0,['class'=>'form-control','id'=>"halls_id"])}}
    </div>
    {{FORM::close()}}
    <div class="col-md-12">
        {{FORM::submit('Printo',['id'=>'printButton','class'=>'btn btn-success pull-right','onclick'=>'print()'])}}
    </div>
</div>

<div>
    <div class="diqka col-md-12 text-center">
        <img src="{{asset('images/kadrizeka.png')}}" width="100px" height="100px"><br>
        UNIVERSITETI "KADRI ZEKA" UNIVERSITY<br>
        Zija Shemsiu pn., 60000, Gjilan, Republika e Kosovës<br>
        www.uni-gjilan.net   tel:028380112<br>
    </div>
</div>

<div class="container">
    <div class="col-md-12 text-center" style="padding-top: 2%">
        <table class="table table-bordered" style="background-color: #FFFFFF">
            <tr>
                <th colspan="14">
                    <h4>Salla <span id="salla"></span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ORARI I LIGJËRATAVE DHE USHTRIMEVE</h4>
                </th>
            </tr>
            <tr>
                <td rowspan="2" style="text-align: center">
                    LËNDA
                </td>
                <td rowspan="2">
                    MËSIMDHËNËSI/ASISTENTI-JA
                </td>
                <td colspan="2">
                    E HËNË
                </td>
                <td colspan="2">
                    E MARTE
                </td>
                <td colspan="2">
                    E MËRKURE
                </td>
                <td colspan="2">
                    E ENJTE
                </td>
                <td colspan="2">
                    E PREMTE
                </td>
                <td colspan="2">
                    E SHTUNE
                </td>
            </tr>
            <tr>
                <td>PREJ</td>
                <td>DERI</td>
                <td>PREJ</td>
                <td>DERI</td>
                <td>PREJ</td>
                <td>DERI</td>
                <td>PREJ</td>
                <td>DERI</td>
                <td>PREJ</td>
                <td>DERI</td>
                <td>PREJ</td>
                <td>DERI</td>
            </tr>
            <tbody id="hallsTable"></tbody>
        </table>
    </div>
</div>
@endsection