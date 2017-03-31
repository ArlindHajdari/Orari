@extends('layouts.master')
@section('title')
    Orari
@stop

@section('other')
    <style>
        .image-preview-input {
            position: relative;
            overflow: hidden;
            margin: 0px;
            color: #333;
            background-color: #fff;
            border-color: #ccc;
        }
        .image-preview-input input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            margin: 0;
            padding: 0;
            font-size: 20px;
            cursor: pointer;
            opacity: 0;
            filter: alpha(opacity=0);
        }
        .image-preview-input-title {
            margin-left:2px;
        }
    </style>

    <script src="{{asset('js/login.js')}}"></script>
    <script>
        $(document).on('click', '#close-preview', function(){
            $('.image-preview').popover('hide');
            // Hover befor close the preview
            $('.image-preview').hover(
                    function () {
                        $('.image-preview').popover('show');
                    },
                    function () {
                        $('.image-preview').popover('hide');
                    }
            );
        });

        $(function() {
            // Create the close button
            var closebtn = $('<button/>', {
                type:"button",
                text: 'x',
                id: 'close-preview',
                style: 'font-size: initial;',
            });
            closebtn.attr("class","close pull-right");
            // Set the popover default content
            $('.image-preview').popover({
                trigger:'manual',
                html:true,
                title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
                content: "There's no image",
                placement:'bottom'
            });
            // Clear event
            $('.image-preview-clear').click(function(){
                $('.image-preview').attr("data-content","").popover('hide');
                $('.image-preview-filename').val("");
                $('.image-preview-clear').hide();
                $('.image-preview-input input:file').val("");
                $(".image-preview-input-title").text("Browse");
            });
            // Create the preview image
            $(".image-preview-input input:file").change(function (){
                var img = $('<img/>', {
                    id: 'dynamic',
                    width:250,
                    height:200
                });
                var file = this.files[0];
                var reader = new FileReader();
                // Set preview image into the popover data-content
                reader.onload = function (e) {
                    $(".image-preview-input-title").text("Change");
                    $(".image-preview-clear").show();
                    $(".image-preview-filename").val(file.name);
                    img.attr('src', e.target.result);
                    $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                }
                reader.readAsDataURL(file);
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','url'=>'/hall']) }}

                    <div class="col-md-10 col-md-offset-1">

                        <div class="form-group">
                            {{ FORM::label('Emri Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('Emri',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Kapaciteti',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('Kapaciteti',null,['class'=>'form-control','required','placeholder'=>'Titulli']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Lloji Salles',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',$halls,null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>

                        {{-- <div class="form-group">
                            {{ FORM::label('Numri Personal',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('n_personal',null,['class'=>'form-control','required','placeholder'=>'Numri Personal']) }}
                            </div>
                        </div> --}}

                        {{-- <div class="form-group">
                            {{ FORM::label('Passwordi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('passwordi',null,['class'=>'form-control','required','placeholder'=>'Passwordi']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Email',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('email',null,['class'=>'form-control','required','placeholder'=>'Email']) }}

                            </div>
                        </div> --}}

                        {{-- <div class="form-group">
                            {{ FORM::label('Foto',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="input-group image-preview control-label pull-right"><!-- don't give a name === doesn't send on POST/GET -->
                            <span class="input-group-btn col-md-8 col-sm-8 col-xs-12">
                                <!-- image-preview-clear button -->
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="glyphicon glyphicon-remove"></span> Clear
                                </button>
                                <!-- image-preview-input -->
                                <div class="btn btn-default image-preview-input">
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                    <span class="image-preview-input-title">Browse</span>
                                    {!! FORM::file('photo') !!}
                                </div>
                            </span>
                            </div><!-- /input-group image-preview [TO HERE]-->
                        </div> --}}
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
                    {{ FORM::open(['class'=>'form-horizontal form-label-left input_mask','files'=>'true','url'=>'lendEdit']) }}

                    <div class="col-md-10 col-md-offset-1">

                        <div class="form-group">
                            {{ FORM::label('Emri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('emri',null,['class'=>'form-control','required','placeholder'=>'Emri']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Semestri',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('titulli',null,['class'=>'form-control','required','placeholder'=>'Titulli']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Cakto Fakultetin',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::select('dekan_i',['Amerika'],null,['class'=>'form-control col-md-8 col-sm-8 col-xs-12','required','placeholder'=>'Fakulteti']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Numri Personal',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('n_personal',null,['class'=>'form-control','required','placeholder'=>'Numri Personal']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Passwordi',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('passwordi',null,['class'=>'form-control','required','placeholder'=>'Passwordi']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Email',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                {{ FORM::text('email',null,['class'=>'form-control','required','placeholder'=>'Email']) }}

                            </div>
                        </div>

                        <div class="form-group">
                            {{ FORM::label('Foto',null,['class'=>'control-label col-md-4 col-sm-4 col-xs-12']) }}
                            <div class="input-group image-preview control-label pull-right"><!-- don't give a name === doesn't send on POST/GET -->
                            <span class="input-group-btn col-md-8 col-sm-8 col-xs-12">
                                <!-- image-preview-clear button -->
                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                    <span class="glyphicon glyphicon-remove"></span> Clear
                                </button>
                                <!-- image-preview-input -->
                                <div class="btn btn-default image-preview-input">
                                    <span class="glyphicon glyphicon-folder-open"></span>
                                    <span class="image-preview-input-title">Browse</span>
                                    {!! FORM::file('photo') !!}
                                </div>
                            </span>
                            </div><!-- /input-group image-preview [TO HERE]-->
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
                        <div class="form-group">
                            <p class="modal-title" style="font-size: 16px;">A jeni të sigurt që dëshironi të fshini?</p><br>
                            <form action="logout" method="POST" id="logout-form">
                                {{ csrf_field() }}
                                <button href="#" onclick="document.getElementById('logout-form').submit()" class="btn btn-success">Yes</button>
                                <button data-dismiss="modal" class="btn btn-danger">No</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- /Modal /Delete-->

    <!-- page content -->
            <div class="page-title">
                <div class="title_left">
                    <h3>Projects <small>Listing designi</small></h3>
                </div>

                <div class="title_right">
                    <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search for...">
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button">Go!</button>
                    </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="row">
                <div class="col-md-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Projects</h2>
                            <button type="button" class="btn btn-success btn-md pull-right" data-toggle="modal" data-target="#registerModal">Regjistro</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">

                            <p>Simple table with project listing with progress and editing options</p>

                            <!-- start project list -->
                            <table class="table table-striped projects">
                                <thead>
                                <tr>
                                    <th style="width: 1%">#</th>
                                    <th style="width: 20%">Project Name</th>
                                    <th>Team Members</th>
                                    <th>Project Progress</th>
                                    <th>Status</th>
                                    <th style="width: 20%">#Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        <a>Pesamakini Backend UI</a>
                                        <br />
                                        <small>Created 01.01.2015</small>
                                    </td>
                                    <td>
                                        <ul class="list-inline">
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                            <li>
                                                <img src="images/user.png" class="avatar" alt="Avatar">
                                            </li>
                                        </ul>
                                    </td>
                                    <td class="project_progress">
                                        <div class="progress progress_sm">
                                            <div class="progress-bar bg-green" role="progressbar" data-transitiongoal="57"></div>
                                        </div>
                                        <small>57% Complete</small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-xs">Success</button>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-xs"><i class="fa fa-folder"></i> View </a>
                                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i> Edit</button>
                                        <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#deleteModal"><i class="fa fa-trash-o"></i> Delete</button>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                            <!-- end project list -->

                        </div>
                    </div>
                </div>
            </div>

    <!-- /page content -->
@stop