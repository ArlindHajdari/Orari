$(document).ready(function(){
    $('#form-register').submit(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        var formData = new FormData(this),url = $(this).attr('action');

        $.ajax({
            type: 'POST',
            data: formData,
            url: url,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            statusCode: {
                500: function(data){
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
                400: function(data){
                    $.each(data.responseJSON['errors'], function(i,v){
                        $.each(this, function(index,value){
                            var errorID = '#'+i;
                            $(errorID).tooltip({title: value,placement: "right"}).tooltip('show');
                        })
                    });
                },
                200: function(data){
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.msg,
                        buttons: [{
                            label: 'OK',
                            action: function() {
                                window.location.reload();
                            }
                        }]
                    });
                }
            }
        });
    });

    $('#form-edit').submit(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        var formData = new FormData(this),url = $(this).attr('action');

        $.ajax({
            type: 'POST',
            data: formData,
            url: url,
            processData: false,
            contentType: false,
            dataType: 'JSON',
            statusCode: {
                500: function(data){
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
                400: function(data){
                    $.each(data.responseJSON['errors'], function(i,v){
                        $.each(this, function(index,value){
                            var errorID = '#'+i;
                            $(errorID).tooltip({title: value,placement: "right"}).tooltip('show');
                        })
                    });
                },
                200: function(data){
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.msg,
                        buttons: [{
                            label: 'OK',
                            action: function() {
                                window.location.reload();
                            }
                        }]
                    });
                }
            }
        });
    });

    $('#deleteModal').on('shown.bs.modal', function(e) {
        var Id = $(e.relatedTarget).data('id');
        $("#delete-form").attr('action','http://localhost:8000/delete-prosub/'+Id);
    });

    $('#editModal').on('shown.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var subject = link.data('subject_id'),profesor = link.data('prof_id');
        var asistent1 = (link.data('asis_id1') == null) ? 0 : link.data('asis_id1');
        var asistent2 = (link.data('asis_id2') == null) ? 0 : link.data('asis_id2');
        var asistent3 = (link.data('asis_id3') == null) ? 0 : link.data('asis_id3');
        var asistent4 = (link.data('asis_id4') == null) ? 0 : link.data('asis_id4');
        var asistent5 = (link.data('asis_id5') == null) ? 0 : link.data('asis_id5');
        var lecture_hours = link.data('lecture_hours'),exercise_hours = link.data('exercise_hours');

        var id = link.data('id');

        var modal = $(this);
        modal.find("#subject_id").val(subject);
        modal.find("#prof_id").val(profesor);
        modal.find("#asis_id1").val(asistent1);
        modal.find("#asis_id2").val(asistent2);
        modal.find("#asis_id3").val(asistent3);
        modal.find("#asis_id4").val(asistent4);
        modal.find("#asis_id5").val(asistent5);
        modal.find("#lecture_hours").val(lecture_hours);
        modal.find("#exercise_hours").val(exercise_hours);

        $('.selectpicker').selectpicker('refresh');

        $("#form-edit").attr('action','http://localhost:8000/prolende-edit/'+id+'/'+asistent1+'/'+asistent2+'/'+asistent3+'/'+asistent4+'/'+asistent5);
    });

    var asis_id1;
    var asis_id2;
    var asis_id3;
    var asis_id4;
    var asis_id5;

    var asis_id6;
    var asis_id7;
    var asis_id8;
    var asis_id9;
    var asis_id10;

    var asis_id11;
    var asis_id12;
    var asis_id13;
    var asis_id14;
    var asis_id15;

    $('#asis_id11').attr('disabled','disabled');
    $('#asis_id21').attr('disabled','disabled');
    $('#asis_id31').attr('disabled','disabled');
    $('#asis_id41').attr('disabled','disabled');
    $('#asis_id51').attr('disabled','disabled');

    $('select[id="subject_id1"]').on('change',function(e){

        var caktoLenden = $(this).val();

        if(caktoLenden == 0)
        {
            $('#asis_id11').val('0');
            $('#asis_id21').val('0');
            $('#asis_id31').val('0');
            $('#asis_id41').val('0');
            $('#asis_id51').val('0');
            $('#asis_id11').attr('disabled','disabled');
            $('#asis_id21').attr('disabled','disabled');
            $('#asis_id31').attr('disabled','disabled');
            $('#asis_id41').attr('disabled','disabled');
            $('#asis_id51').attr('disabled','disabled');
            $('.selectpicker').selectpicker('refresh');
        }else {
            $('#asis_id11').removeAttr('disabled');
        }
    });

    $('select[id="asis_id11"]').on('change',function(e) {

        asis_id1 = $(this).val();

        if (asis_id1 == 0) {
            $('#asis_id21').attr('disabled', 'disabled');
            $('#asis_id31').attr('disabled', 'disabled');
            $('#asis_id41').attr('disabled', 'disabled');
            $('#asis_id51').attr('disabled', 'disabled');
        }else if(asis_id1 == asis_id2 || asis_id1 == asis_id3 || asis_id1 == asis_id4 || asis_id1 == asis_id5) {

            BootstrapDialog.show({
                title: 'Gabim gjatë futjes së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });

            $('#asis_id11').val('0');
            $('#asis_id21').val('0');
            $('#asis_id31').val('0');
            $('#asis_id41').val('0');
            $('#asis_id51').val('0');
            $('#asis_id21').attr('disabled', 'disabled');
            $('#asis_id31').attr('disabled', 'disabled');
            $('#asis_id41').attr('disabled', 'disabled');
            $('#asis_id51').attr('disabled', 'disabled');
            $('.selectpicker').selectpicker('refresh');
        }else {
            $('#asis_id21').removeAttr('disabled');
        }
    });

    $('select[id="asis_id21"]').on('change',function(e){

        asis_id2 = $(this).val();

        if (asis_id2 == 0) {
            $('#asis_id31').attr('disabled', 'disabled');
            $('#asis_id41').attr('disabled', 'disabled');
            $('#asis_id51').attr('disabled', 'disabled');
        }else if(asis_id2 == asis_id1 || asis_id2 == asis_id3 || asis_id2 == asis_id4 || asis_id2 == asis_id5) {

            BootstrapDialog.show({
                title: 'Gabim gjatë futjes së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });

            $('#asis_id21').val('0');
            $('#asis_id31').val('0');
            $('#asis_id41').val('0');
            $('#asis_id51').val('0');
            $('#asis_id31').attr('disabled', 'disabled');
            $('#asis_id41').attr('disabled', 'disabled');
            $('#asis_id51').attr('disabled', 'disabled');
            $('.selectpicker').selectpicker('refresh');
        }else
        {
            $('#asis_id31').removeAttr('disabled');
        }
    });

    $('select[id="asis_id31"]').on('change',function(e){

        asis_id3 = $(this).val();

        if (asis_id3 == 0) {
            $('#asis_id41').attr('disabled', 'disabled');
            $('#asis_id51').attr('disabled', 'disabled');
        }else if(asis_id3 == asis_id1 || asis_id3 == asis_id2 || asis_id3 == asis_id4 || asis_id3 == asis_id5) {
            BootstrapDialog.show({
                title: 'Gabim gjatë futjes së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });

            $('#asis_id31').val('0');
            $('#asis_id41').val('0');
            $('#asis_id41').attr('disabled', 'disabled');
            $('#asis_id51').val('0');
            $('#asis_id51').attr('disabled', 'disabled');
            $('.selectpicker').selectpicker('refresh');
        }else
        {
            $('#asis_id41').removeAttr('disabled');
        }
    });

    $('select[id="asis_id41"]').on('change',function(e){

        asis_id4 = $(this).val();

        if (asis_id4 == 0) {
            $('#asis_id51').attr('disabled', 'disabled');
        }else if(asis_id4 == asis_id1 || asis_id4 == asis_id2 || asis_id4 == asis_id3 || asis_id4== asis_id5) {
            BootstrapDialog.show({
                title: 'Gabim gjatë futjes së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id41').val('0');
            $('#asis_id51').val('0');
            $('#asis_id51').attr('disabled', 'disabled');
            $('.selectpicker').selectpicker('refresh');
        }else
        {
            $('#asis_id51').removeAttr('disabled');
        }
    });

    $('select[id="asis_id51"]').on('change',function(e){

        asis_id5 = $(this).val();

        if(asis_id5 == asis_id4 || asis_id5 == asis_id3 || asis_id5 == asis_id2 || asis_id5 == asis_id1) {
            BootstrapDialog.show({
                title: 'Gabim gjatë futjes së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id51').val('0');
        }
    });

    ////////////////////////////////////////////REGJISTRIMI////////////////////////////////////////////////
    ////////////////////////////////////////////REGJISTRIMI////////////////////////////////////////////////
    ////////////////////////////////////////////REGJISTRIMI////////////////////////////////////////////////

    $('#editModal').on('shown.bs.modal', function (e) {
        asis_id6 = $('#asis_id1').val();
        asis_id7 = $('#asis_id2').val();
        asis_id8 = $('#asis_id3').val();
        asis_id9 = $('#asis_id4').val();
        asis_id10 = $('#asis_id5').val();
    });

    $('select[id="asis_id1"]').on('change',function(e){

        asis_id11 = $(this).val();

        if((asis_id11 == asis_id12) || (asis_id11 == asis_id6) || (asis_id11 == asis_id7)){
            BootstrapDialog.show({
                title: 'Gabim gjatë ndryshimit së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id2').val('0');
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $('select[id="asis_id2"]').on('change',function(e){

        asis_id12 = $(this).val();

        if((asis_id11 == asis_id12)|| (asis_id12 == asis_id13) || (asis_id12 == asis_id6)) {
            BootstrapDialog.show({
                title: 'Gabim gjatë ndryshimit së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id2').val('0');
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $('select[id="asis_id3"]').on('change',function(e){

        asis_id13 = $(this).val();

        if((asis_id13 == asis_id12) || (asis_id13 == asis_id1) || (asis_id13 == asis_id14) || (asis_id13 == asis_id6) || (asis_id13 == asis_id7)) {
            BootstrapDialog.show({
                title: 'Gabim gjatë ndryshimit së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id3').val('0');
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $('select[id="asis_id4"]').on('change',function(e){

        asis_id14 = $(this).val();

        if((asis_id14 == asis_id15) || (asis_id14 == asis_id13) || (asis_id14 == asis_id12) || (asis_id14 == asis_id11) || (asis_id14 == asis_id6) || (asis_id14 == asis_id7) || (asis_id14 == asis_id8)) {
            BootstrapDialog.show({
                title: 'Gabim gjatë ndryshimit së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id4').val('0');
            $('.selectpicker').selectpicker('refresh');
        }
    });

    $('select[id="asis_id5"]').on('change',function(e){

        asis_id15 = $(this).val();

        if((asis_id15 == asis_id13) || (asis_id15 == asis_id12) || (asis_id15 == asis_id11) || (asis_id15 == asis_id6) || (asis_id15 == asis_id7) || (asis_id15 == asis_id8) || (asis_id15 == asis_id14)) {
            BootstrapDialog.show({
                title: 'Gabim gjatë ndryshimit së të dhënave',
                message: 'Ju lutem caktoni asistent te ndryshëm!',
                buttons: [{
                    label: 'OK',
                    action: function (dialog) {
                        dialog.close();
                    }
                }]
            });
            $('#asis_id5').val('0');
            $('.selectpicker').selectpicker('refresh');
        }
    });

});
