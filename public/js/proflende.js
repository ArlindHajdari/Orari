$(document).ready(function(){
    $('#prosubRegister').submit(function(e){
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

    $('#profLendeEdit').submit(function(e){
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
        $("#delete-form").attr('action','http://localhost:8000/prosub-delete/'+Id);
    });

    $('#editModal').on('show.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var subject = link.data('subject_id');
        var profesor = link.data('prof_id');
        var asistent = link.data('asis_id');

        var modal = $(this);
        modal.find("#subject_id").val(subject);
        modal.find("#user_id").val(profesor);
        modal.find("#email").val(asistent);

        $("#prosub-edit").attr('action','http://localhost:8000/prolende-edit/'+id);
    });
});
