$(document).ready(function(){
    $('#status-register').submit(function(e){
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

    $('#status-edit').submit(function(e){
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
        var status_id = $(e.relatedTarget).data('id');
        $("#delete-form").attr('action','http://localhost:8000/statusDelete/'+status_id);
    });

    $('#editModal').on('shown.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var academic_title = link.data('academic_title_id');
        var status = link.data('status');
        var normal_hours = link.data('normal_hours');
        var extra_hours = link.data('extra_hours');

        var modal = $(this);
        modal.find("#academic_title").val(academic_title);
        modal.find("#status").val(status);
        modal.find("#normal_hours").val(normal_hours);
        modal.find("#extra_hours").val(extra_hours);
        $("#status-edit").attr('action','http://localhost:8000/statusEdit/'+academic_title+'/'+status);
    });
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
