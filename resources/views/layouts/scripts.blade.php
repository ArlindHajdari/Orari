<script src="{{asset('jquery/dist/jquery.min.js')}}"></script>
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="{{asset('bootstrap/dist/js/bootstrap.min.js')}}"></script>
<script src="{{asset('fastclick/lib/fastclick.js')}}"></script>
<script src="{{asset('nprogress/nprogress.js')}}"></script>
<script src="{{asset('Chart.js/dist/Chart.min.js')}}"></script>
<script src="{{asset('gauge.js/dist/gauge.min.js')}}"></script>
<script src="{{asset('bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
<script src="{{asset('iCheck/icheck.min.js')}}"></script>
<script src="{{asset('skycons/skycons.js')}}"></script>
<script src="{{asset('Flot/jquery.flot.js')}}"></script>
<script src="{{asset('Flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('Flot/jquery.flot.time.js')}}"></script>
<script src="{{asset('Flot/jquery.flot.stack.js')}}"></script>
<script src="{{asset('Flot/jquery.flot.resize.js')}}"></script>
<script src="{{asset('flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
<script src="{{asset('flot-spline/js/jquery.flot.spline.min.js')}}"></script>
<script src="{{asset('flot.curvedlines/curvedLines.js')}}"></script>
<script src="{{asset('DateJS/build/date.js')}}"></script>
<script src="{{asset('jqvmap/dist/jquery.vmap.js')}}"></script>
<script src="{{asset('jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
<script src="{{asset('jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
<script src="{{asset('moment/min/moment.min.js')}}"></script>
<script src="{{asset('bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('js/custom.min.js')}}"></script>
<script src="{{asset('fullcalendar/fullcalendar.min.js')}}"></script>
<script src="{{asset('moment/locale/sq.js')}}"></script>
<script src="{{asset('fullscreen-master/release/jquery.fullscreen.js')}}"></script>
<script src="{{asset('switchery/dist/switchery.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="{{asset('js/bootstrap-multiselect.js')}}"></script>
<script>
    $('#full_screen').click(function(){
        if($.fullscreen.isFullScreen()){
            $.fullscreen.exit();
        }else {
            $('#container').fullscreen();
        }
    });
</script>
