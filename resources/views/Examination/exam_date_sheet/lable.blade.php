<title>{{__('barcode.print_labels')}}</title>

@php
	$loop_count = 0;
@endphp
@foreach($product_details as $details)

    
	@while($details['qty'] > 0)
		@php
			$loop_count += 1;
			$is_new_row = ($barcode_details->stickers_in_one_row == 1 || ($loop_count % $barcode_details->stickers_in_one_row) == 1) ? true : false;
 
			$is_new_paper = ($barcode_details->is_continuous && $is_new_row) || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 1));

			$is_paper_end = (($barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_row == 0)) || (!$barcode_details->is_continuous && ($loop_count % $barcode_details->stickers_in_one_sheet == 0)));

			
		@endphp

		@if($is_new_paper)
			{{-- Actual Paper --}}
			<div style="@if(!$barcode_details->is_continuous) height:{{$barcode_details->paper_height}}mm !important; @else height:{{$barcode_details->height}}mm !important; @endif width:{{$barcode_details->paper_width}}mm !important; line-height: 16px !important; page-break-after: always;" class="@if(!$barcode_details->is_continuous) label-border-outer @endif">

			{{-- Paper Internal --}}
			<div style="@if(!$barcode_details->is_continuous)margin-top:{{$barcode_details->top_margin}}mm !important; margin-bottom:{{$barcode_details->top_margin}}mm !important; margin-left:{{$barcode_details->left_margin}}mm !important;margin-right:{{$barcode_details->left_margin}}mm !important;@endif" class="label-border-internal">
		@endif

		@if((!$barcode_details->is_continuous) && ($loop_count % $barcode_details->stickers_in_one_sheet) <= $barcode_details->stickers_in_one_row)
			@php $first_row = true @endphp
		@elseif($barcode_details->is_continuous && ($loop_count <= $barcode_details->stickers_in_one_row) )
			@php $first_row = true @endphp
		@else
			@php $first_row = false @endphp
		@endif

		<div style="height:{{$barcode_details->height}}mm !important; line-height: {{$barcode_details->height}}mm; width:{{$barcode_details->width}}mm !important; display: inline-block; @if(!$is_new_row) margin-left:{{$barcode_details->col_distance}}mm;  !important; @endif @if(!$first_row)margin-top:2mm !important; @endif" class="sticker-border text-center">
		<div style="display:inline-block;vertical-align:middle;line-height:16px !important; text-align:center">
			{{-- Business Name --}}
			<b style="display: block  ;font-size:10px" class="text-uppercase">Collegiate</b>
            <b style="zoom:50%;"> {{ ucwords($details['details']->student->first_name . ' ' . $details['details']->student->last_name) }}</b style="zoom:50%;"><br>
            <b> {{ $details['details']->student->roll_no }}</b><br>
            <b>{{ ucwords($details['details']->student->current_class->title) . ' '.$details['details']->student->current_class_section->section_name }}</b>

			<img class="center-block" style="width:90%; !important;height: 5mm !important;" src="data:image/png;base64,{{DNS1D::getBarcodePNG($details['details']->student->roll_no , 'C128', 1,50,array(39, 48, 54), true)}}">

		</div>
		</div>
			
			


		@php
			$details['qty'] = $details['qty'] - 1;
		@endphp
	@endwhile

@endforeach





<script type="text/javascript">

</script>

<style type="text/css">

	.text-center{
		text-align: center;
	}

	.text-uppercase{
		text-transform: uppercase;
	}

	/*Css related to printing of barcode*/
	.label-border-outer{
	    border: 0.1px solid grey !important;
	}
	.label-border-internal{
	    /*border: 0.1px dotted grey !important;*/
	}
	.sticker-border{
	    border: 0.1px dotted grey !important;
	    overflow: hidden;
	    box-sizing: border-box;
	}
	#preview_box{
	    padding-left: 30px !important;
	}
	@media print{
	    .content-wrapper{
	      border-left: none !important; /*fix border issue on invoice*/
	    }
	    .label-border-outer{
	        border: none !important;
	    }
	    .label-border-internal{
	        border: none !important;
	    }
	    .sticker-border{
	        border: none !important;
	    }
	    #preview_box{
	        padding-left: 0px !important;
	    }
	    #toast-container{
	        display: none !important;
	    }
	    .tooltip{
	        display: none !important;
	    }
	    .btn{
	    	display: none !important;
	    }
	}

	@media print{
		#preview_body{
			display: block !important;
		}
	}
	@page {
		size: {{$barcode_details->paper_width}}mm @if(!$barcode_details->is_continuous && $barcode_details->paper_height != 0){{$barcode_details->paper_height}}mm @endif;

		/*width: {{$barcode_details->paper_width}}in !important;*/
		/*height:@if($barcode_details->paper_height != 0){{$barcode_details->paper_height}}in !important @else auto @endif;*/
		margin-top: 0mm;
		margin-bottom: 0mm;
		margin-left: 0mm;
		margin-right: 0mm;
		
	}
</style>