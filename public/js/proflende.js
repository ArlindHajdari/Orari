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
        $("#delete-form").attr('action','http://localhost:8000/delete-prosub/'+Id);
    });

    $('#editModal').on('show.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var subject = link.data('subject_id');
        var profesor = link.data('prof_id');
        var asistent1 = (link.data('asis_id1') == null) ? 0 : link.data('asis_id1');
        var asistent2 = (link.data('asis_id2') == null) ? 0 : link.data('asis_id2');
        var asistent3 = (link.data('asis_id3') == null) ? 0 : link.data('asis_id3');
        var asistent4 = (link.data('asis_id4') == null) ? 0 : link.data('asis_id4');
        var asistent5 = (link.data('asis_id5') == null) ? 0 : link.data('asis_id5');

        var id = link.data('id');

        var modal = $(this);
        modal.find("#subject_id").val(subject);
        modal.find("#prof_id").val(profesor);
        modal.find("#asis_id1").val(asistent1);
        modal.find("#asis_id2").val(asistent2);
        modal.find("#asis_id3").val(asistent3);
        modal.find("#asis_id4").val(asistent4);
        modal.find("#asis_id5").val(asistent5);

        $("#form-edit").attr('action','http://localhost:8000/prolende-edit/'+id+'/'+asistent1+'/'+asistent2+'/'+asistent3+'/'+asistent4+'/'+asistent5);
    });
});
