

<!DOCTYPE html>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@lang('english.fee_card')</title>
<style>
    @page {
        margin: 10px;
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

        }

        @page {

            size: A4;
            -webkit-print-color-adjust: exact;
            page-break-inside: avoid;

        }
        h3{
            text-align: center;
        }
            

        .info{

  display: flex;
  flex-direction: column;
}
.content {
  display: flex;
  flex: 1;
  color: #000; 
}
.columns{
  display: flex;
  flex:1;
}
.main{
  flex: 1;
  order: 1;
}
.sidebar-first{
  width:20%;
  order: 1;
}

body {
	background-color: #fff;
	font-family: Calibri, Myriad;
}

#main {
	width: 100%;
	padding: 20px;
	margin: auto;
}

.timecard {
	margin: auto;
	width: 100%;
	border-collapse: collapse;
	border: 2px solid #000; /*for older IE*/
	border-style: hidden;
}



.timecard thead th {
	padding: 8px;
	background-color: #fde9d9;
	font-size: large;
}





.timecard th, .timecard td {
	padding: 0px;
	border-width: 1px;
	border-style: solid;
	border-color: #f79646 #ccc;
}

.timecard td {
	text-align: center;
}

.timecard tbody th {
	text-align: left;
	font-weight: normal;
}

.timecard tfoot {
	font-weight: bold;
	font-size: large;
	background-color: #687886;
	color: #fff;
}

.timecard tr.even {
	background-color: #fde9d9;
}
    }

</style>
</head>

<body style="color:#000">

        <div style=" border:1px solid black; padding:10px; page-break-after: always; ">
            @include('common.logo')
        
            <div id="head">
                <h3><b>@lang('english.strength_list')<b></h3> 
                    <hr>
            </div>
       <div id="main">

                <table class="timecard">
                    <thead>

                        <tr>
                          
                            <th>#</th>
                              <th>@lang('english.campus_name')</th>
                                <th>@lang('english.class_name')</th>
                                <th>@lang('english.section_name')</th>
                                <th>@lang('english.total_strength')</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $total=0;
                    @endphp
                        @foreach ( $data as $dt)
                         @php
                             $total+=$dt->total_student;
                         @endphp
                        <tr class="@if($loop->iteration%2==0) even @else odd @endif">
                            <td>{{$loop->iteration}}</td>
                            <td>{{$dt->campus_name }}</td>
                            <td>{{ $dt->title}}</td>
                            <td>@if(!empty($dt->section_name)){{ $dt->section_name}}@endif</td>
                            <td>{{ $dt->total_student }}</td>
                        </tr>
                            
                        @endforeach
                        <tfoot>
                        <tr>
                          <td colspan="4">@lang('english.total_strength')</td>
                          <td>{{ $total}}</td>
                        </tr>
                        <tfoot>
                        
                       
                    </tbody>
                    
                </table>

                </div>
                  


                  
       </div>

</body>

</html>
