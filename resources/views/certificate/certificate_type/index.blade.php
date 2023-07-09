@extends("admin_layouts.app")
@section('title', __('english.roles'))
@section('wrapper')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">@lang('english.mark_entry')</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/home') }} "><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">@lang('english.mark_entry')</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">

            <div class="card-body">
                <h6 class="card-title text-primary">@lang('english.select_ground')</h6>
                <hr>
                <div class="row ">

                    <div class="col-md-8 p-2 ">
                        {!! Form::label('withdraw_reason', __('english.withdraw_reason') . ':*', ['classs' => 'form-lable']) !!}
                        {!! Form::text('withdraw_reason',null, ['class' => 'form-control', 'required','placeholder' => __('english.withdraw_reason')]) !!}
                    </div>
                  
                </div>
                <div class="d-lg-flex align-items-center mt-4 gap-3">
                    <div class="ms-auto"><button class="btn btn-primary radius-30 mt-2 mt-lg-0" type="submit">
                            <i class="fas fa-plus"></i>@lang('english.withfraw_student')</button></div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection

@section('javascript')

<script type="text/javascript">
    $(document).ready(function() {


    });

</script>
@endsection

