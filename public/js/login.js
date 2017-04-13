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
                     window.location.replace(data.url);
                }
            }
        });
    });
});