$(document).ready(function(){
    $('select[id="subject_id"]').on('change',function(){
        $('#lush_id').html(' ');
        var subject_id = $(this).val();

        if(subject_id == 0){
            $('#lush_id').attr('disabled','disabled');
            $('#lush_id').val('0');
        }else {
            $('#lush_id').removeAttr('disabled');
            var option = "<option value='0' selected>Cakto L/USH</option>";

            $.ajax({
                type: 'get',
                url: 'http://localhost:8000/getLushFromSubject',
                data:{
                    'subject_id':$('#subject_id').val()
                },
                success:function(data){
                    $.each(data, function(i,v){
                        option += "<option " +
                                "value='"+i+"'>"+v+"</option>";
                    });
                    $('#lush_id').append(option);
                },
                error:function(){
                    BootstrapDialog.show({
                        title: 'Gabim gjatë marrjes së të dhënave!',
                        message: 'Të dhënat per l/ush nuk u gjeten!',
                        buttons: [{
                            label: 'OK',
                            action: function (dialog) {
                                $('#lush_id').attr('disabled','disabled');
                                $('#lush_id').val('0');
                                dialog.close();
                            }
                        }]
                    });
                }
            });
        }
    });

    $('#groupLushSubject-register').submit(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        var formData = $(this).serialize(),url = $(this).attr('action');

        $.ajax({
            type: 'POST',
            data: formData,
            url: url,
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
                    console.log(data);
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

    // $('#grouplushsubject-edit').submit(function(e){
    //     e.preventDefault();
    //
    //     $.ajaxSetup({
    //         headers:{
    //             'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
    //         }
    //     });
    //
    //     var formData = new FormData(this),url = $(this).attr('action');
    //
    //     $.ajax({
    //         type: 'POST',
    //         data: formData,
    //         url: url,
    //         processData: false,
    //         contentType: false,
    //         dataType: 'JSON',
    //         statusCode: {
    //             500: function(data){
    //                 BootstrapDialog.show({
    //                     title: data.responseJSON['title'],
    //                     message: data.responseJSON['msg'],
    //                     buttons: [{
    //                         label: 'Close',
    //                         action: function(dialog) {
    //                             dialog.close();
    //                         }
    //                     }]
    //                 });
    //             },
    //             400: function(data){
    //                 $.each(data.responseJSON['errors'], function(i,v){
    //                     $.each(this, function(index,value){
    //                         var errorID = '#'+i;
    //                         $(errorID).tooltip({title: value,placement: "right"}).tooltip('show');
    //                     })
    //                 });
    //             },
    //             200: function(data){
    //                 BootstrapDialog.show({
    //                     title: data.title,
    //                     message: data.msg,
    //                     buttons: [{
    //                         label: 'OK',
    //                         action: function() {
    //                             window.location.reload();
    //                         }
    //                     }]
    //                 });
    //             }
    //         }
    //     });
    // });

    $('#deleteModal').on('shown.bs.modal', function(e) {
        var group_id = $(e.relatedTarget).data('group_id');
        var subject_lush_id = $(e.relatedTarget).data('subject_lush_id');
        $("#delete-form").attr('action','http://localhost:8000/groupLushSubject-delete/'+group_id+'/'+subject_lush_id);
    });

    // $('#editModal').on('shown.bs.modal', function(e) {
    //     var link = $(e.relatedTarget);
    //
    //     var subject_id = $(e.relatedTarget).data('subject_id');
    //     var lush_id = $(e.relatedTarget).data('lush_id');
    //
    //     var modal = $(this);
    //     modal.find("#subject_id").val(subject_id);
    //     modal.find("#lush_id").val(lush_id);
    //     $("#status-edit").attr('action','http://localhost:8000/groupLushSubject-edit/'+subject_id+'/'+lush_id);
    // });
});

$('#search-form').submit(function(e){
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
        processData: false,
        contentType: false,
        url: url,
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
            }
        }
    });
});
