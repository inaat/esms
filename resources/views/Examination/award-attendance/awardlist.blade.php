

<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.exam_award_list_attendance_print')</title>
<style>
    @page {
        margin: 20px;
        padding: 0px;
        color: #000;
    }
    @media print {
      table {
        color: #000;
        zoom:92%
      }
          
      @media print and (-webkit-min-device-pixel-ratio:0) {
        table {
          color: #000;
          -webkit-print-color-adjust: exact;
        }
      }
   

        .pace-progress {
            display: none;
        }

        * {
            margin: 0px;
            padding: 0px;
            background: #fff;
            color: #000;

        }

        @page {

            size: A4;
           

        }
        table {
        width: 100%;
        
        border-collapse: collapse;
        border-bottom: 2px solid black;
        word-wrap: break-word;


    }
    td,
    th {
        padding: 0px;
        border: 1px solid black;
        text-align: center;
 
        word-wrap: break-word;
    }
   
     .left{
         float: left;
     }
     .right{
         float: right;
         position: relative;
         bottom: 50%;
         margin-right: px;
         color: #000;
     }
       thead {
        display: table-header-group
    }

    tfoot {
        display: table-row-group
    }

    tr {
        page-break-inside: avoid
    }
    }

</style>
</head>

<body >
  
    @include('common.logo')
    <div style="margin-top: 10px">
    <div style="height: 70px;">
        <p class="left">
            @lang('english.class')  <span style="width:120px;text-align:center;border-bottom:1px solid black;display: inline-block; "> {{ $data[0]->student->current_class->title .' '. $data[0]->student->current_class_section->section_name}}</span><br>
            <br>
           @lang('english.subject')  <span style="width:60px;text-align:center;border-bottom:1px solid black;display: inline-block; "></span>
           @lang('english.max_marks') <span style="width:60px;text-align:center;border-bottom:1px solid black;display: inline-block; "></span>
    
    
        </p>
        <h6 style="width:30%; margin:0 auto;text-align: center;border:1px solid black;        ">@lang('english.award')  @lang('english.list')</h6>
        <p class="right">
            @lang('english.examination') <span style="width:150px;text-align:center;border-bottom:1px solid black;display: inline-block; ">{{  $data[0]->exam_create->term->name }}({{ $data[0]->session->title }})</span>

        </p>
    </div>
<table id="" class=""  width="100%">
    <thead>
         <tr>
             <th rowspan="2">@lang('english.roll_no')</th>
             <th rowspan="2">@lang('english.student_name')</th>
             <th rowspan="2">@lang('english.father_name')</th>
             <th rowspan="2">@lang('english.sheet_no')</th>
             <th rowspan="2">@lang('english.student_sign')</th>
             <th colspan="3">@lang('english.marks_obtained')</th>                                                                                        
         </tr>
         <tr>                                                                       
             <th>@lang('english.marks_theory')</th>                                            
             <th>@lang('english.prac/oral')</th>                                            
             <th>@lang('english.total')</th>                                            
            
         </tr>
     </thead>
     <tbody >   
        
         @foreach ($data as $std)
     
        <tr>                                                                                                                                                                                                    
                     <td>{{ $std->student->roll_no }}</td>
                     <td>{{ ucwords($std->student->first_name .' '.$std->student->last_name) }}</td>
                    <td>{{ ucwords($std->student->father_name) }}</td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td></td>
                 <tr>
                 @endforeach 
                
        </tbody>
        
 </table>
 @lang('english.total_students'):{{  '    '.$data->count() }}

</div>
<p class="left">
    <b>@lang('english.printed') {{ @format_date('now') }} <b>
</p>
<p class="right">
    @lang('english.subject_teacher') <span style="width:150px;text-align:center;border-bottom:1px solid black;display: inline-block; "></span><br><br>
    @lang('english.class_teacher') <span style="width:160px;text-align:center;border-bottom:1px solid black;display: inline-block; "></span>

</p>
</body>

</html>


