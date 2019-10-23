$(document).ready(function(){
    $('#mesimdhensitRegister').submit(function(e){
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

    $('#mesimdhenesi-edit').submit(function(e){
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
                            console.log(i,value);
                            var errorID = '#'+i;
                            $('#mesimdhenesi-edit').find(errorID).tooltip({title: value,placement: "right"}).tooltip('show');
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
        var id = $(e.relatedTarget).data('id');
        $("#delete-form").attr('action','http://localhost:8000/mesimdhenesi-delete/'+id);
    });

    $('#editModal').on('shown.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var first_name = link.data('first_name');
        var last_name = link.data('last_name');
        var email = link.data('email');
        var personal_number = link.data('personal_number');
        var academic_title_id = link.data('academic_title_id');
        var cpa_id = link.data('cpa_id');
        var role_id = link.data('role_id');
        var photo = link.data('photo');
        var status_id = link.data('status_id');
        var id = link.data('id');
        console.log(status_id);
        var modal = $(this);
        modal.find("#first_name").val(first_name);
        modal.find("#last_name").val(last_name);
        modal.find("#email").val(email);
        modal.find("#personal_number").val(personal_number);
        modal.find("#academic_title_id").val(academic_title_id);
        modal.find("#role_id").val(role_id);
        modal.find("#cpa_id").val(cpa_id);
        modal.find("#status_id").val(status_id);
        var real_photo=photo.split('/')[1];
        $("#mesimdhenesi-edit").attr('action','http://localhost:8000/mesimdhenesi-edit/'+id+'/'+real_photo);
    });
});
