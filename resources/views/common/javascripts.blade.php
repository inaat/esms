<script type="text/javascript">
    base_path = "{{url('/')}}";
    //used for push notification
</script>

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js?v=$asset_v"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js?v=$asset_v"></script>
<![endif]-->
<script src="{{ asset('js/vendor.js?v=' . $asset_v) }}" data-pagespeed-no-defer></script>
<script src="{{ asset('/js/sidebarapp.js?v=' . $asset_v) }}" ></script>

@if(file_exists(public_path('js/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    <script src="{{ asset('js/lang/' . session()->get('user.language', config('app.locale') ) . '.js?v=' . $asset_v) }}"></script>
@else
    <script src="{{ asset('js/lang/en.js?v=' . $asset_v) }}"></script>
@endif
    {{-- <script src="https://getdatepicker.com/5-4/theme/js/tempusdominus-bootstrap-4.js"></script> --}}

@php
    $system_details_date_format = session('system_details.date_format', config('constants.default_date_format'));
    $datepicker_date_format = str_replace('d', 'dd', $system_details_date_format);
    $datepicker_date_format = str_replace('m', 'mm', $datepicker_date_format);
    $datepicker_date_format = str_replace('Y', 'yyyy', $datepicker_date_format);

    $moment_date_format = str_replace('d', 'DD', $system_details_date_format);
    $moment_date_format = str_replace('m', 'MM', $moment_date_format);
    $moment_date_format = str_replace('Y', 'YYYY', $moment_date_format);

    $system_details_time_format = session('system_details.time_format');
    $moment_time_format = 'HH:mm';
    if($system_details_time_format == 12){
        $moment_time_format = 'hh:mm A';
    }

    $common_settings = !empty(session('system_details.common_settings')) ? session('system_details.common_settings') : [];

    $default_datatable_page_entries = !empty($common_settings['default_datatable_page_entries']) ? $common_settings['default_datatable_page_entries'] : 25;
@endphp

<script data-pagespeed-no-defer>
var __mapping_attendance_count_interval = "{{config('constants.mapping_attendance_count_interval', 60)}}000";

var sidebarcolor=localStorage.getItem("sidebarcolor");
var headercolor=localStorage.getItem("headercolor");
var theme=localStorage.getItem("theme");
if(sidebarcolor){
    if(theme){
    var side=theme;

    }else{
    var side='color-sidebar  '+sidebarcolor+'  '+headercolor +' '+theme;
    }
  $('html').attr('class', side);
}else{
    $('html').attr('class','color-sidebar sidebarcolor1 color-header headercolor1');
}
    moment.tz.setDefault('{{ Session::get("system_details.time_zone") }}');
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        @if(config('app.debug') == false)
            $.fn.dataTable.ext.errMode = 'throw';
        @endif
    });

    var financial_year = {
        start: moment("{{ Session::get('financial_year.start') }}"),
        end: moment("{{ Session::get('financial_year.end') }}"),
    } ; 
 
      var mathcss="{{ asset('/js/tinymce/matheditor/html/css/math.css')}}";

    @if(file_exists(public_path('AdminLTE/plugins/select2/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    //Default setting for select2
    $.fn.select2.defaults.set("language", "{{session()->get('user.language', config('app.locale'))}}");
    @endif

    var datepicker_date_format = "{{$datepicker_date_format}}";
    var moment_date_format = "{{$moment_date_format}}";
    var moment_time_format = "{{$moment_time_format}}";

    var app_locale = "{{session()->get('user.language', config('app.locale'))}}";

    var non_utf8_languages = [
        @foreach(config('constants.non_utf8_languages') as $const)
        "{{$const}}",
        @endforeach
    ];

    var __default_datatable_page_entries = "{{$default_datatable_page_entries}}";

    var __new_notification_count_interval = "{{config('constants.new_notification_count_interval', 60)}}000";
</script>

@if(file_exists(public_path('js/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    <script src="{{ asset('js/lang/' . session()->get('user.language', config('app.locale') ) . '.js?v=' . $asset_v) }}"></script>
@else
    <script src="{{ asset('js/lang/en.js?v=' . $asset_v) }}"></script>
@endif
		<script src="{{ asset('/js/tinymce/tinymce.js?v=' . $asset_v) }}"></script>
		<script src="{{ asset('/js/tinymce/matheditor/plugin.js?v=' . $asset_v) }}"></script>

<script src="{{ asset('js/functions.js?v=' . $asset_v) }}" data-pagespeed-no-defer></script>
<script src="{{ asset('js/common.js?v=' . $asset_v) }}" data-pagespeed-no-defer></script>
<script src="{{ asset('/js/apps.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/u2.js?v=' . $asset_v) }}"></script>


<!-- TODO -->
@if(file_exists(public_path('AdminLTE/plugins/select2/lang/' . session()->get('user.language', config('app.locale')) . '.js')))
    <script src="{{ asset('AdminLTE/plugins/select2/lang/' . session()->get('user.language', config('app.locale') ) . '.js?v=' . $asset_v) }}"></script>
@endif
@php
    $validation_lang_file = 'messages_' . session()->get('user.language', config('app.locale') ) . '.js';
@endphp
@if(file_exists(public_path() . '/js/jquery-validation-1.16.0/src/localization/' . $validation_lang_file))
    <script src="{{ asset('js/jquery-validation-1.16.0/src/localization/' . $validation_lang_file . '?v=' . $asset_v) }}"></script>
@endif


@yield('javascript')

<script type="text/javascript">
    $(document).ready( function(){
        var locale = "{{session()->get('user.language', config('app.locale'))}}";
        var isRTL = @if(in_array(session()->get('user.language', config('app.locale')), config('constants.langs_rtl'))) true; @else false; @endif

        $('#calendar').fullCalendar('option', {
            locale: locale,
            isRTL: isRTL
        });
      
    });
</script>