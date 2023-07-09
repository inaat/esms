@extends("admin_layouts.app")
@section('title', __('english.expenses_report'))
@section("style")
<link href="{{ asset('assets/plugins/highcharts/css/highcharts.css')}}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
@endsection
 @section('wrapper')
 <div class="page-wrapper">
     <div class="page-content">
         <!--breadcrumb-->
         {!! Form::open(['url' => action('Report\ReportController@getExpenseReport'), 'method' => 'get' ]) !!}

         <div class="card">

             <div class="card-body">
                 <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                 <hr>
                 <div class="row m-0">
                     <div class="col-md-4  ">
                         {!! Form::label('english.student', __('english.campuses') . ':*') !!}
                         {!! Form::select('campus_id', $campuses, null, ['class' => 'form-select select2 global-campuses', 'id' => 'students_list_filter_campus_id', 'style' => 'width:100%', 'placeholder' => __('english.all')]) !!}
                     </div>
                     <div class="col-md-4  ">
                         {!! Form::label('english.category', __('english.category') . ':*') !!}
                         {!! Form::select('category', $categories, null, ['placeholder' =>
                         __('english.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']); !!}
                      </div>
                      <div class="col-md-4">
                        {!! Form::label('transaction_date_range', __('english.date_range') . ':') !!}

                        <div class="input-group flex-nowrap"> <span class="input-group-text" id="addon-wrapping"><i class="fa fa-calendar"></i></span>

                            {!! Form::text('list_filter_date_range', null, ['class' => 'form-control', 'id'=>'list_filter_date_range','readonly', 'placeholder' => __('english.date_range')]) !!}

                        </div>
                    </div> 
                         <input type="hidden" id="lable" value="{{ $labels }}"/>
                         <input type="hidden" id="values" value="{{ $values }}"/>
                    

                 </div>
                 <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-filter"></i>@lang('english.filter')</button></div>
                </div>
                 <div class="row">

                    <div class="col-12 d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="card radius-10 shadow-none bg-transparent border">
       
                                    <div class="" id="chart5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
           

             <div class="row">
                
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                            <hr>
                    <table class="table" id="expense_report_table">
                        <thead>
                            <tr>
                                <th>@lang( 'english.expense_categories' )</th>
                                <th>@lang( 'english.total_expense' )</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_expense = 0;
                            @endphp
                            @foreach($expenses as $expense)
                                <tr>
                                    <td>{{$expense['category'] ?? __('english.others')}}</td>
                                    <td><span class="display_currency" data-currency_symbol="true">{{$expense['total_expense']}}</span></td>
                                </tr>
                                @php
                                    $total_expense += $expense['total_expense'];
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>@lang('english.total')</td>
                                <td><span class="display_currency" data-currency_symbol="true">{{$total_expense}}</span></td>
                            </tr>
                        </tfoot>
                    </table>
                        </div>
                </div>
         </div>
     </div>


     {{ Form::close() }}



 </div>
 </div>

 @endsection
 @section('javascript')
 <script src="{{ asset('/js/apps.js?v=' . $asset_v) }}"></script>
 <script src="{{ asset('assets/plugins/highcharts/js/highcharts.js?v=' . $asset_v) }}"></script>
 <script src="{{ asset('assets/plugins/highcharts/js/exporting.js?v=' . $asset_v) }}"></script>
 <script src="{{ asset('assets/plugins/highcharts/js/variable-pie.js?v=' . $asset_v) }}"></script>
 <script src="{{ asset('assets/plugins/highcharts/js/export-data.js?v=' . $asset_v) }}"></script>
 <script src="{{ asset('assets/plugins/highcharts/js/accessibility.js?v=' . $asset_v) }}"></script>
<script type="text/javascript">
    $(document).ready(function() {

        
var labels= JSON.parse($('#lable').val());
var values= JSON.parse($('#values').val());
console.log(labels);
// console.log(values);
barChart(labels,values);
});
var converters = {
    // Latin to Farsi
    fa: function (number) {
        return number.toString().replace(/\d/g, function (d) {
            return String.fromCharCode(d.charCodeAt(0) + 1728);
        });
    },
    // Latin to Arabic
    ar: function (number) {
        return number.toString().replace(/\d/g, function (d) {
            return String.fromCharCode(d.charCodeAt(0) + 1584);
        });
    }
};

    function barChart(labels, chartData_expense) {
    Highcharts.chart('chart5', {
        chart: {
            numberFormatter: function () {
            var ret = Highcharts.numberFormat.apply(0, arguments);
            return __currency_trans_from_en(ret, true)
        },
            type: 'column',
            styledMode: true

        },
        credits: {
            enabled: false,
        },
        exporting: {
            buttons: {
                contextButton: {
                    enabled: true,
                },
            },
        },
        title: {
            text: LANG.expenses
        },

        legend: {
            align: 'right',
            verticalAlign: top,
            floating: true,
            layout: 'vertical'
        },
        xAxis: {
            categories: labels,
            
        },

        yAxis: {
            title: {
                text: LANG.total_amount
            },
            labels: {
                formatter: function() {
                    return this.value / 1000 + 'k';
                }
            }
        },
        tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}</b><br/>'
    },
        plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}'
            }
        }
    },


        series: [ {
                name: LANG.expenses,
                data: chartData_expense

            }

        ]
    });

}
</script>
 @endsection