@if($status=='UPCOMING')
<div class="col">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group" role="group">
            <button type="button" class="btn-badge  badge  rounded-pill text-white bg-info p-2 text-uppercase px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">@lang('english.upcoming')</button>
            <ul class="dropdown-menu">
                <li>
                <a data-href="{{action('SessionController@activateSession', [$id])}}" class="dropdown-item session_activate">@lang('english.activate')</a></li>

                </li>
                
            </ul>
        </div>
    </div>
</div>
@elseif($status=='ACTIVE')
<div class="col">
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <div class="btn-group" role="group">
            <button type="button" class="btn badge  rounded-pill text-white bg-success p-2 text-uppercase px-3 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">@lang('english.active')</button>
            <ul class="dropdown-menu">
                <li>
                <a data-href="{{action('SessionController@activateSession', [$id])}}" class="dropdown-item session_activate">@lang('english.mark_passed')</a></li>

                </li>
                
            </ul>
        </div>
    </div>
</div>
@else
<div class="badge btn-badge  rounded-pill text-white bg-danger p-2 text-uppercase px-3">@lang('english.passed')</div>
@endif