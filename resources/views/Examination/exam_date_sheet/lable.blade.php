<html>

<head>
    <title>Barcode Print</title>
    <link type="text/css" href="../assets/itsolution24/cssmin/main.css" rel="stylesheet">
    <link type="text/css" href="../assets/itsolution24/css/barcode.css" rel="stylesheet">
    <style type="text/css">
        @media print {

            html,
            body,
            .container,
            #content,
            .box-content,
            .col-lg-12,
            .table-responsive,
            .table-responsive .table,
            .dataTable,
            .box,
            .row {
                width: 100% !important;
                height: auto !important;
                padding: 0 !important;
                margin: 0 !important
            }

            .lt .sidebar-con {
                width: 0;
                display: none
            }

            body:before,
            body:after,
            .no-print,
            #header,
            #sidebar-left,
            .sidebar-nav,
            .main-menu,
            footer,
            .breadcrumb,
            .box-header,
            .box-header .fa,
            .box-icon,
            .alert,
            .introtext,
            .table-responsive .row,
            .table-responsive .table th:first-child,
            .table-responsive .table td:first-child,
            .table-responsive .table tfoot,
            .buttons,
            .modal-open #content,
            .modal-body .close,
            .pagination,
            .close,
            .staff_note,
            hr {
                display: none
            }

            .container {
                width: auto !important
            }

            h3 {
                margin-top: 0
            }

            .modal {
                position: static
            }

            .modal .table-responsive {
                display: block
            }

            .modal .table th:first-child,
            .modal .table td:first-child,
            .modal .table th,
            .modal .table td {
                display: table-cell !important
            }

            .modal-content {
                display: block !important;
                background: #fff !important;
                border: none !important
            }

            .modal-content .table tfoot {
                display: table-row-group !important
            }

            .modal-header {
                border-bottom: 0
            }

            .modal-lg {
                width: 100%
            }

            .table-responsive .table th,
            .table-responsive .table td {
                display: table-cell;
                border-top: none !important;
                border-left: none !important;
                border-right: none !important;
                border-bottom: 1px solid #ccc !important
            }

            a:after {
                display: none
            }

            .print-table thead th:first-child,
            .print-table thead th:last-child,
            .print-table td:first-child,
            .print-table td:last-child {
                display: table-cell !important
            }

            .fa-3x {
                font-size: 1.5em
            }

            .border-right {
                border-right: 0 !important
            }

            .table thead th {
                background: #f5f5f5 !important;
                background-color: #f5f5f5 !important;
                border-top: 1px solid #f5f5f5 !important
            }

            .well {
                border-top: 0 !important
            }

            .box-header {
                border: 0 !important
            }

            .box-header h2 {
                display: block;
                border: 0 !important
            }

            .order-table tfoot {
                display: table-footer-group !important
            }

            .print-only {
                display: block !important
            }

            .reports-table th,
            .reports-table td {
                display: table-cell !important
            }

            table thead {
                display: table-header-group
            }

            .white-text {
                color: #fff !important;
                text-shadow: 0 0 3px #fff !important;
                -webkit-print-color-adjust: exact
            }

            #bl .barcode td {
                padding: 2px !important
            }

            #bl .barcode .bcimg {
                max-width: 100%
            }

            #lp .labels {
                text-align: center;
                font-size: 10pt;
                /* page-break-after: always; */
                padding: 1px
            }

            #lp .labels img {
                max-width: 100%
            }

            #lp .labels .name {
                font-size: .8em;
                font-weight: 700
            }

            #lp .labels .price {
                font-size: .8em;
                font-weight: 700
            }

            .well {
                border: none !important;
                box-shadow: none
            }

            .table {
                margin-bottom: 20px !important
            }
        }

        .barcode {
            width: 210mm;
            height: 297mm;
            display: block;
            margin: 0 auto;
            padding: 0;
            /* page-break-after: always;
            overflow: hidden; */
			page-break-inside: avoid;

        }

        .barcode .item {
            display: table;
            overflow: hidden;
            text-align: center;
            font-size: 12px;
            line-height: 14px;
            text-transform: uppercase;
            float: left;
            /*padding: 3px;*/
        }

        .barcode .barcode_site,
        .barcode .barcode_name,
        .barcode .barcode_image,
        .barcode .variants {
            display: block
        }

        .barcode .barcode_price,
        .barcode .barcode_unit,
        .barcode .barcode_category {
            display: inline-block
        }

        .barcode .product_image {
            width: .8in;
            float: left;
            margin: 5px
        }

        .product_image img {
            display: inline-block;
            max-width: 100%;
        }

        .barcode .barcode_site {
            font-weight: 700
        }

        .barcode .barcode_site,
        .barcode .barcode_name {
            font-size: 14px
        }

        .barcode_site {
            font-size: 16px;
            font-weight: 700;
        }

        .barcode_image img {
            max-width: 100%;
        }

        .barcode .item-inner {
            display: table-cell;
            vertical-align: middle;
            /*width: 90%;
    height: 90%;*/
            border: 1px solid #000;
			color: #000;
        }

        /* .barcodea4 {
            width: 8.27in;
            height: 11.69in;
            display: block;
            margin: 10px auto;
            padding: 0;
            page-break-after: always
        } */

        .barcodea4 .barcode_site,
        .barcodea4 .barcode_name,
        .barcodea4 .barcode_image,
        .barcodea4 .variants {
            display: block
        }

        .barcodea4 .barcode_price,
        .barcodea4 .barcode_unit,
        .barcodea4 .barcode_category {
            display: inline-block
        }

        .barcodea4 .product_image {
            width: .8in;
            float: left;
            margin: 5px
        }

        .style1 {
            width: 210mm;
            height: 297mm;
        }

        .style2 {
            width: 210mm;
            height: 148.5mm;
        }

        .style3 {
            width: 210mm;
            height: 99mm;
        }

        .style4 {
            width: 105mm;
            height: 148.5mm;
        }

        .style6 {
            width: 70mm;
            height: 148.5mm;
        }

        .style6sq {
            width: 105mm;
            height: 99mm;
        }

        .style6rec {
            width: 210mm;
            height: 49.5mm;
        }

        .style8 {
            width: 105mm;
            height: 74mm;
        }

        .style8rnd {
            width: 99.1mm;
            height: 67.7mm;
        }

        .style8rnd .item-inner {
            border-radius: 10px;
        }

        .style10 {
            width: 105mm;
            height: 57mm;
        }

        .style12 {
            width: 105mm;
            height: 48mm;
        }

        .style12oval {
            width: 60mm;
            height: 60mm;
        }

        .style12oval .item-inner {
            border-radius: 50%;
        }

        .style14 {
            width: 105mm;
            height: 42.4mm;
        }

        .style14rnd {
            width: 99.1mm;
            height: 38.1mm;
        }

        .style4rnd .item-inner {
            border-radius: 10px;
        }

        .style16 {
            width: 105mm;
            height: 37mm;
        }

        .style21 {
            width: 70mm;
            height: 42.4mm;
        }

        .style21rnd {
            width: 63.5mm;
            height: 38.1mm;
        }

        .style21rnd .item-inner {
            border-radius: 10px;
        }

        .style24 {
            width: 70mm;
            height: 37mm;
        }

        .style24rnd {
            width: 63.5mm;
            height: 33.9mm;
        }

        .style24rnd .item-inner {
            border-radius: 10px;
        }

        .style28oval {
            width: 40mm;
            height: 40mm;
        }

        .style28oval .item-inner {
            border-radius: 50%;
        }

        .style40 {
			page-break-inside: avoid;
            width: 52.5mm;
            height: 29.74mm;
        }

        .style40ma {
            width: 48.5mm;
            height: 25.4mm;
        }

        .style52 {
            width: 52.5mm;
            height: 21.2mm;
        }

        .style56 {
            width: 52.5mm;
            height: 21.2mm;
        }

        .style64 {
            width: 48.5mm;
            height: 16.9mm;
        }

        .style70 {
            width: 38mm;
            height: 21.2mm;
        }
    </style>
</head>

<body style="background:#ffffff;">


    <div class="barcode barcodea4">
		@foreach($product_details as $details)
        <div class="item style40">
            <div class="item-inner">
                {{-- <span style="min-height:70px;" class="product_image">
                    <img src="http://itsolution24.com/posv33/storage/products//id_ajmul_432543534_3443.jpg"
                        style="width:60px;height:auto;">
                </span> --}}
                {{-- <div style="margin-bottom:2px;">
                    <span style="font-size:12px;" class="barcode_site">{{''}}</span>
                </div>
                <div style="line-height:1.222;font-weight:700;">
                    <span style="font-size:10px;" class="barcode_name">Home Delivery</span>
                </div>
                <div style="font-size:15px;line-height:1.222;font-weight:700;">
                    USD
                    100.00
                </div>
                <span class="barcode_image">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAE8AAAAeAQMAAABXKNGXAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAABhJREFUGJVjuDy7Sy3RY+/RjdcYRpmUMAE2wq5hmIHRKAAAAABJRU5ErkJggg=="
                        alt="</php echo $product['p_code'];?>" class="bcimg">
                    <div class="text-center" style="font-size:12px;">
                        52057894 </div>
                </span> --}}
				<div style="margin-bottom:2px;">
                    <span style="font-size:12px;" class="barcode_site"><b>Antalya</b></span>
                </div>
                <div style="line-height:1.222;font-weight:700;">
                    <span style="font-size:10px;" class="barcode_name"><b>{{ ucwords($details['details']->student->first_name . ' ' . $details['details']->student->last_name) }}<b>
						<br><b> {{ $details['details']->student->roll_no }}</b><br>
						<b>{{ ucwords($details['details']->student->current_class->title) . ' '.$details['details']->student->current_class_section->section_name }}</b>
					</span>
                </div>
				
          

			<img class="center-block" style="width:90%; !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($details['details']->student->roll_no , 'C128', 1,30,array(39, 48, 54), true)}}">

            </div>
        </div>
    @endforeach
    </div>

</body>

</html>
