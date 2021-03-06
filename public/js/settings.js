$(document).ready(function(){
    $('#settings-edit').submit(function(e){
        e.preventDefault();

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            }
        });

        var formData = $(this).serialize(),url = $(this).attr('action');

        $.ajax({
            type: 'PATCH',
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

    $('#editModal').on('shown.bs.modal', function(e) {
        var link = $(e.relatedTarget);

        var max_hour_p = link.data('max_hour_p');
        var max_hour_a = link.data('max_hour_a');
        var summer_semester = link.data('summer_semester');
        var winter_semester = link.data('winter_semester');
        var start_schedule_time = link.data('start_schedule_time');
        var end_schedule_time = link.data('end_schedule_time');
        var id = link.data('id');

        var modal = $(this);
        modal.find("#max_hour_day_professor").val(max_hour_p);
        modal.find("#max_hour_day_assistant").val(max_hour_a);
        modal.find("#summer_semester").val(summer_semester);
        modal.find("#winter_semester").val(winter_semester);
        modal.find("#start_schedule_time").val(start_schedule_time);
        modal.find("#end_schedule_time").val(end_schedule_time);
        $("#settings-edit").attr('action','http://localhost:8000/settings/'+id);
    });
});
