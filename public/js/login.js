$(document).ready(function(){
    $('#loginForm').submit(function(e){
        e.preventDefault();

        $.ajaxSetup({
         header:{
             'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
         }
        });

        var form = $(this), formdata = form.serialize(), url = form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: formdata,
            dataType: 'JSON',
            statusCode:{
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
                200: function(data){
                    window.location.replace(data.responseJSON['url']);
                }
            }
        });
    });

    $('#registerForm').submit(function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        var form = $(this), formData=new FormData(this), url=form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
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
                    var msg="";
                    $.each(data.responseJSON['msg'], function(index, value){
                        $.each(this,function(i,v){
                            msg += v+'\n';
                        })
                    });
                    BootstrapDialog.show({
                        title: data.responseJSON['title'],
                        message: msg,
                        buttons: [{
                            label: 'Close',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                },
                200: function(data){
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.msg,
                        buttons: [{
                            label: 'Close',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                }
            }
        });
    });


    $('#test').submit(function(e){
        e.preventDefault();
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        var form = $(this), formData=new FormData(this), url=form.attr('action');

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
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
                    console.log(data);
                    // var msg="";
                    // $.each(data.responseJSON['msg'], function(index, value){
                    //     $.each(this,function(i,v){
                    //         msg += v+'\n';
                    //     })
                    // });
                    // BootstrapDialog.show({
                    //     title: data.responseJSON['title'],
                    //     message: msg,
                    //     buttons: [{
                    //         label: 'Close',
                    //         action: function(dialog) {
                    //             dialog.close();
                    //         }
                    //     }]
                    // });
                },
                200: function(data){
                    BootstrapDialog.show({
                        title: data.title,
                        message: data.msg,
                        buttons: [{
                            label: 'Close',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                }
            }
        });
    });

});