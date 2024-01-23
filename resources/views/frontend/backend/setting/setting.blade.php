     <div class="card">
         <div class="card-body">
             <h5 class="card-title text-primary">@lang('english.general_settings')
                 <small class="text-info font-13">@lang('english.setting_help_text')</small>
             </h5>
             <hr>

             <div class="row">
                 <div class="col-md-4 p-3">
                     {!! Form::label('school_name', __('english.school_name') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('school_name', $front_settings->school_name, ['class' => 'form-control','required','placeholder' => __('english.school_name')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('address', __('english.address') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('address', $front_settings->address, ['class' => 'form-control','required','placeholder' => __('english.address')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('reg_no', __('english.reg_no') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('reg_no', $front_settings->reg_no, ['class' => 'form-control','required','placeholder' => __('english.reg_no')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('email', __('english.email') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('email', $front_settings->email, ['class' => 'form-control','required','placeholder' => __('english.email')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('phone_no', __('english.phone_no') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::text('phone_no', $front_settings->phone_no, ['class' => 'form-control','required','placeholder' => __('english.phone_no')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('facebook', __('english.facebook') . ':', ['classs' => 'form-lable']) !!}
                     {!! Form::text('facebook', $front_settings->facebook, ['class' => 'form-control','placeholder' => __('english.facebook')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('youTube', __('english.youTube') . ':', ['classs' => 'form-lable']) !!}
                     {!! Form::text('youTube', $front_settings->youTube, ['class' => 'form-control','placeholder' => __('english.youTube')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('instagram', __('english.instagram') . ':', ['classs' => 'form-lable']) !!}
                     {!! Form::text('instagram', $front_settings->instagram, ['class' => 'form-control','placeholder' => __('english.instagram')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('linkedin', __('english.linkedin') . ':', ['classs' => 'form-lable']) !!}
                     {!! Form::text('linkedin', $front_settings->linkedin, ['class' => 'form-control','placeholder' => __('english.linkedin')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('twitter', __('english.twitter') . ':', ['classs' => 'form-lable']) !!}
                     {!! Form::text('twitter', $front_settings->twitter, ['class' => 'form-control','placeholder' => __('english.twitter')]) !!}
                 </div>
                 <div class="col-md-4 p-3">
                     {!! Form::label('skype', __('english.skype') . ':', ['classs' => 'form-lable']) !!}
                     {!! Form::text('skype', $front_settings->skype, ['class' => 'form-control','placeholder' => __('english.skype')]) !!}
                 </div>
                                  <div class="clearfix"></div>

                 <div class="col-md-6 p-3">
                     {!! Form::label('facebook_page', __('english.facebook_page') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::textarea('facebook_embed', $front_settings->facebook_embed, ['class' => 'form-control','placeholder' => __('english.facebook_page')]) !!}
                 </div>
                 <div class="col-md-6 p-3">
                     {!! Form::label('map_url', __('english.map_url') . ':*', ['classs' => 'form-lable']) !!}
                     {!! Form::textarea('map_url', $front_settings->map_url, ['class' => 'form-control','placeholder' => __('english.map_url')]) !!}
                 </div>

                 <div class="clearfix"></div>


                 <div class="row p-3">
                     <div class="col-sm-4">
                         <h5 class="card-title ">@lang('english.logo')</h5>
                         <img src="{{'uploads/front_image/'.$front_settings->logo_image }}" class="logo_image card-img-top" alt="...">
                         {!! Form::label('logo_image', __('english.logo') . ':') !!}
                         {!! Form::file('logo_image', ['accept' => 'image/*','class' => 'form-control upload_logo_image']); !!}
                         <p class="card-text fs-6 help-block"></p>
                     </div>


                     <div class="col-sm-4">
                         <h5 class="card-title ">@lang('english.page_banner')</h5>
                         <img src="{{'uploads/front_image/'.$front_settings->page_banner }}" class="page_banner card-img-top" alt="...">
                         {!! Form::label('page_banner', __('english.page_banner') . ':') !!}
                         {!! Form::file('page_banner', ['accept' => 'image/*','class' => 'form-control upload_page_banner']); !!}
                         <p class="card-text fs-6 help-block"></p>
                     </div>


                 </div>



             </div>
         </div>


     </div>
