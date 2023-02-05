     <div class="card">
         <div class="card-body">
             <h5 class="card-title text-primary">@lang('english.colors')
             </h5>
             <hr>

             <div class="row">
                 <div class="col-md-4 p-3">
                     {!! Form::label('main_color', __('english.main_color') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('main_color', $front_settings->main_color, ['class' => 'form-control colorpicker-element','required','id'=>'main_color','placeholder' => __('english.main_color')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('hover_color', __('english.hover_color') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('hover_color', $front_settings->hover_color, ['class' => 'form-control colorpicker-element','required','id'=>'hover_color','placeholder' => __('english.hover_color')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('linear_gradient', __('english.linear_gradient') . ':*', ['classs' => 'form-lable']) !!}
                    {!! Form::text('linear_gradient', $front_settings->linear_gradient, ['class' => 'form-control colorpicker-element','required','id'=>'linear_gradient','placeholder' => __('english.linear_gradient')]) !!}
                 </div>
                 <div class="clearfix"></div>




             </div>
         </div>


     </div>
