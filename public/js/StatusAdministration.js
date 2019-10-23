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
        var status_id = $(e.relatedTarget).data('status_id');
        $("#delete-form").attr('action','http://localhost:8000/status-delete/'+status_id);
    });

    $('#editModal').on('shown.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var id = link.data('id');
        var status = link.data('status');

        var modal = $(this);
        modal.find("#status").val(status);
        $("#status-edit").attr('action','http://localhost:8000/status-edit/'+id);
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
