<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.withdrawal_register_print')</title>
<style>
    @page {
        margin: 8px;
        padding: 3px;
    }

    @media print {
        
        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            page-break-inside: avoid;
            font-family: sans serif;
            color: #000;
           

        }

        @page {

            size: A4 landscape;
            -webkit-print-color-adjust: exact;
            page-break-inside: avoid;
            color: #000;
             zoom:70%;
             margin:10px;

        }

        h3 {
            text-align: center;
            color:#000;
        }
         

        .info {

            display: flex;
            flex-direction: column;
        }


        .timecard {
            margin: auto;
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            /*for older IE*/
            border-style: hidden;
            zoom:80%;
            background-color:#fff;
            color:#000;
           
            
        }



        .timecard thead th {
            padding: 8px;
            background-color: #eee;
            color: #000;
            text-align: center;
        }





        .timecard th,
        .timecard td {
            padding: 0px;
            border-width: 1px;
            border-style: solid;
            border-color: #000;
        }

        .timecard td {
            text-align: center;
        }

        .timecard tbody th {
            text-align: left;
            font-weight: normal;
        }



        .timecard tr.even {
            background-color: #eee;
        }
        
.underline:after {
    content: '';
    background: black;
    width: 100%;
    height: 2px;
    display: block;
 
}

 

    }

</style>
</head>

<body style="color:#000 ; ">
     @if($withdrawal_register->count()>0)
    <table class="timecard">
        <thead>
           
            <tr>
                <th colspan="12" style="text-align: center;">
                    <h3 style="text-align: center; text-transform: uppercase"><b>@lang('english.admission') @lang('english.withdrawal_register') @lang('english.of_the') {{ session()->get('system_details.org_name')}}</b></h3>
                </th>
            <tr>
              <tr>
                <th colspan="12" style="text-align: center;">
                    <h3 style="text-align: center; text-transform: uppercase"><b>{{ ucwords($class_level->title) }}</b></h3>
                </th>
            <tr>
                 <th>@lang('english.sr_no')</th>
                <th>@lang('english.admission_date')</th>
                <th>@lang('english.admission_no')</th>
                <th>@lang('english.name_of_student')<br>@lang('english.with')<br>@lang('english.father_name')</th>
                <th>@lang('english.date_of_birth') <br> @lang('english.with') <br> @lang('english.bay_form_no')</th>
                <th>@lang('english.religion')</th>
                {{-- <th>@lang('english.cast')</th> --}}
                 <th>@lang('english.occupation')</th>
                <th>@lang('english.residence')<br>@lang('english.with')<br>@lang('english.contact_no')</th>
                <th>@lang('english.class_to')<br> @lang('english.which') <br>@lang('english.admitted')</th>
                <th>@lang('english.class_from')<br>@lang('english.which')<br>@lang('english.withdrawal')</th>
                <th>@lang('english.date_of_withdrawal')</th>
                <th>@lang('english.remarks')</th> 
            </tr>
        </thead>
        <tbody>
            @foreach ($withdrawal_register as $dt)

            <tr class="@if($loop->iteration%2==0) even @else odd @endif">
                <td>{{$loop->iteration}}</td>
                <td>{{@format_date($dt->admission_date) }}</td>
                <td>{{($dt->admission_no) }}</td>
                <td>@lang('english.name'):<u>{{ ucwords($dt->student_name) }} </u><br>
                @lang('english.father_name'):<u>{{ ucwords($dt->father_name) }}</u>
                <br>
                     Cnic No:{{ $dt->father_cnic_no }}
                </td>
                <td>
                    @php
                    $f = new \NumberFormatter('eng', \NumberFormatter::SPELLOUT);
                    $nf = new NumberFormatter('eng', NumberFormatter::ORDINAL);

                    $new_birth_date = explode('-', $dt->birth_date);
                    $year = $new_birth_date[0];
                    $month = $new_birth_date[1];
                    $day = $new_birth_date[2];
                    $birth_day=$nf->format($day);
                    $birth_year=$f->format($year);
                    $monthNum = $month;
                    $dateObj = DateTime::createFromFormat('!m', $monthNum);//Convert the number into month name
                    $monthName = ucwords($dateObj->format('F'));
                    $final_date=ucwords($birth_day).' '.$monthName.' '.ucwords($birth_year);
                    @endphp
                    (@lang('english.in_figures')):{{ @format_date($dt->birth_date) }}<br>
                    (@lang('english.in_words'):{{ $final_date }}<br>
                    @lang('english.bay_form_no'):{{ $dt->cnic_no }}
                   </td>
                   <td>{{ucwords($dt->religion)}}</td>
                 <td>{{ucwords($dt->father_occupation)}}</td>
                <td>{{ucwords($dt->std_permanent_address)}}<br>
                @lang('english.contact_no'):{{ $dt->mobile_no }}</td>
                <td>{{ucwords($dt->admission_class)}}</td>
                <td>{{ucwords($dt->leaving_class)}}</td>
                <td>@if(!empty($dt->date_of_leaving)){{@format_date($dt->date_of_leaving) }}@endif</td>
                <td>
                    @if ($dt->date_of_leaving != null)
                    {{'Took SLC'}}
                    @elseif($dt->promoted_class!=$dt->admission_class)
                    {{ 'Promoted To '.$dt->promoted_class }}
                    @endif

                </td> 



            </tr>

            @endforeach



        </tbody>
    </table>
    
  @endif

</body>

</html>

