$(document).ready(function(){
    $('#register-form').submit(function(e){
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

    $('#edit-form').submit(function(e){
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
        var acadmic_title_id = $(e.relatedTarget).data('id');
        $("#delete-form").attr('action','http://localhost:8000/academicTitleDelete/'+acadmic_title_id);
    });

    $('#editModal').on('show.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var academic_title = link.data('academic_title');
        var id = link.data('id');

        var modal = $(this);
        modal.find("#academic_title").val(academic_title);
        $("#edit-form").attr('action','http://localhost:8000/academicTitleEdit/'+id);
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