<!-- success-page.blade.php -->
@extends('admin_layouts.sidebar-header-less')
@section('wrapper')
    <div class="page print_area" id="page">
        <div class="container mt-5">
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">Admission Form Submitted Successfully!</h4>
                <p>Thank you for applying. Your application has been received successfully.</p>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Next Steps</h5>
                            <ul>
                                <li>Check your email for a confirmation message.</li>                          </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Contact Information</h5>
                            <p>If you have any questions or concerns, feel free to contact our admissions office.</p>
                            <p>Email: {{session()->get("front_details.email") }}</p>
                            <p>Phone: {{ session()->get("front_details.phone_no") }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="jumbotron text-primary text-center">
                        <h4 class="display-4 text-primary">Welcome to {{ session()->get("front_details.school_name") }}!</h4>
                        <p class="lead">We are excited to have you as part of our community.</p>
                        <p>Explore the campus and get ready for an amazing academic journey.</p>
                        <a class="btn btn-primary btn-lg" href="{{ url('/') }}" role="button">Learn More</a>
                    </div>
                </div>
            </div>

            <!-- Add more styled sections or content as needed -->

        </div>
    </div>
@endsection
