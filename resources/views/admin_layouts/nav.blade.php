<div class="sidebar-wrapper navbar-vertical-content" data-simplebar="true" id="navbar-vertical-content">
    <div class="sidebar-header">
        <div>
            <img src="{{ url('/uploads/business_logos/' . session()->get('system_details.org_logo', '')) }}"
                class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">{{ session()->get('system_details.org_name', config('app.name', 'Explainer School')) }}
            </h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
        </div>

    </div>
   
    <!--navigation-->
    <!-- Sidebar Menu -->
    {!! Menu::render('admin-sidebar-menu', 'adminltecustom') !!}

</div>
