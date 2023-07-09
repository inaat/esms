    <style type="text/css">
        :root {
            --blue: #007bff;
            --indigo: #6610f2;
            --purple: #6f42c1;
            --pink: #e83e8c;
            --red: #dc3545;
            --orange: #fd7e14;
            --yellow: #ffc107;
            --green: #28a745;
            --teal: #20c997;
            --cyan: #17a2b8;
            --white: #fff;
            --gray: #6c757d;
            --gray-dark: #343a40;
            --primary: #007bff;
            --secondary: #6c757d;
            --success: #28a745;
            --info: #17a2b8;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #343a40;
            /* --green: #157D4C;
   --orange:#FF9F29;
  --greengredient:linear_gradient linear-gradient(125deg, #144B2F, #34495e);*/
            --green: @php echo session()->get("front_details.main_color")@endphp;
            --orange:@php echo session()->get("front_details.hover_color")@endphp;
            --greengredient:@php echo session()->get("front_details.linear_gradient")@endphp;
            --primary: @php echo session()->get("front_details.main_color")@endphp;
		--btn-primary-hover: @php echo session()->get("front_details.hover_color")@endphp;
		--box-shadow: @php echo session()->get("front_details.hover_color")@endphp;
		--primary1: @php echo session()->get("front_details.linear_gradient")@endphp;
           --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --font-family-sans-serif: -apple-system,
            BlinkMacSystemFont,
            "Segoe UI",
            Roboto,
            "Helvetica Neue",
            Arial,
            sans-serif,
            "Apple Color Emoji",
            "Segoe UI Emoji",
            "Segoe UI Symbol";
            --font-family-monospace: SFMono-Regular,
            Menlo,
            Monaco,
            Consolas,
            "Liberation Mono",
            "Courier New",
            monospace;


        }
        


/* header colors */

.switcher-body .headercolor1 {

	background: @php echo session()->get("front_details.hover_color")@endphp !important;

}

.switcher-body .headercolor2 {

	background: #23282c !important;

}

.switcher-body .headercolor3 {

	background: #e10a1f !important;

}

.switcher-body .headercolor4 {

	background: #157d4c !important;

}

.switcher-body .headercolor5 {

	background: #673ab7 !important;

}

.switcher-body .headercolor6 {

	background: #795548 !important;

}

.switcher-body .headercolor7 {

	background: #d3094e !important;

}

.switcher-body .headercolor8 {

	background: #ff9800 !important;

}

html.headercolor1 .topbar {

	background: @php echo session()->get("front_details.hover_color")@endphp;

}

html.headercolor2 .topbar {

	background: #23282c;

}

html.headercolor3 .topbar {

	background: #e10a1f;

}
html.headercolor4 .topbar {

	background: #157d4c !important;
}



html.headercolor5 .topbar {

	background: #673ab7;

}

html.headercolor6 .topbar {

	background: #795548;

}

html.headercolor7 .topbar {

	background: #d3094e;

}

html.headercolor8 .topbar {

	background: #ff9800;

}




/* sidebar color */


html.color-sidebar .sidebar-wrapper{
	background-color: #171717;
    border-right: 1px solid rgb(228 228 228 / 0%);
}
html.color-sidebar .sidebar-header{
	background-color: transparent;
    border-right: 1px solid #e4e4e400;
    border-bottom: 1px solid rgb(255 255 255 / 15%);
}
/* html.color-sidebar .logo-icon {
    filter: invert(1) grayscale(100%) brightness(200%);
} */

html.color-sidebar .menu-label{
	color: rgb(255 255 255 / 65%);
}

html.color-sidebar .sidebar-wrapper .sidebar-header .logo-text {
    color: #ffffff;
}

html.color-sidebar .sidebar-wrapper .sidebar-header .toggle-icon {
    color: #ffffff;
}

html.color-sidebar .simplebar-scrollbar:before {
	background: rgba(255, 255, 255, .4)
}

html.color-sidebar .sidebar-wrapper .metismenu .mm-active>a, html.color-sidebar .sidebar-wrapper .metismenu a:active, html.color-sidebar .sidebar-wrapper .metismenu a:focus, html.color-sidebar .sidebar-wrapper .metismenu a:hover {
    color: #fff;
    text-decoration: none;
    background: rgb(255 255 255 / 18%);
}

html.color-sidebar .sidebar-wrapper .metismenu a {
    color: rgb(255 255 255 / 85%);
}

html.color-sidebar .sidebar-wrapper .metismenu ul {
    border: 1px solid #ffffff00;
    background: rgb(255 255 255 / 8%);
}


html.color-sidebar .sidebar-wrapper {
    background-size: 100% 100%;
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
}


.switcher-wrapper .sidebarcolor1 {
	background: @php echo session()->get("front_details.main_color")@endphp !important;
}
.switcher-wrapper .sidebarcolor2 {
	background: #23282c !important;


}
.switcher-wrapper .sidebarcolor3 {
	background: #e10a1f !important;

}
.switcher-wrapper .sidebarcolor4 {
	background:#157d4c;

}
.switcher-wrapper .sidebarcolor5 {
	background: #673ab7 !important;
}
.switcher-wrapper .sidebarcolor6 {
	background: #795548 !important;

}
.switcher-wrapper .sidebarcolor7 {
	background: #d3094e;

}
.switcher-wrapper .sidebarcolor8 {
	background: #ff9800 !important;

}




html.color-sidebar.sidebarcolor1 .sidebar-wrapper {
	background: @php echo session()->get("front_details.main_color")@endphp !important;


}

html.color-sidebar.sidebarcolor2 .sidebar-wrapper {
	background: #23282c !important;


}

html.color-sidebar.sidebarcolor3 .sidebar-wrapper {
	background: #e10a1f !important;

}

html.color-sidebar.sidebarcolor4 .sidebar-wrapper {
	background:#157d4c;

}

html.color-sidebar.sidebarcolor5 .sidebar-wrapper {
	background: #673ab7 !important;
}

html.color-sidebar.sidebarcolor6 .sidebar-wrapper {
	background: #795548 !important;

}

html.color-sidebar.sidebarcolor7 .sidebar-wrapper {
	background: #d3094e;

}

html.color-sidebar.sidebarcolor8 .sidebar-wrapper {
	background: #ff9800 !important;

}



    </style>