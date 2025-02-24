@extends('themes.layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>User Profile</h1>

        </div><!-- End Page Title -->

        <section class="section profile">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="{{ asset(basename(storage_path()) . '/' . Auth::user()?->media?->file_path) }}"
                                alt="Profile" class="rounded-circle">
                            <h2>{{ Auth::user()->name }}</h2>
                            <h3>{{ Auth::user()->employeeDetails?->position?->title }}</h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <div class="tab-content pt-2">

                                <div class="profile-edit pt-3" id="profile-edit">

                                    <!-- Profile Edit Form -->
                                    <form method="post" action={{ route('update_profile', Auth::user()->id) }}
                                        enctype="multipart/form-data" novalidate>
                                        @csrf
                                        @method('PUT')
                                        <div class="row mb-3">
                                            <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                                Image</label>
                                            <div class="col-md-8 col-lg-9">
                                                <img src="{{ asset(basename(storage_path()) . '/' . Auth::user()?->media?->file_path) }}"
                                                    alt="Profile">
                                                <div class="pt-2">
                                                    <div class="input-group has-validation">
                                                        <input type="file" name="file" class="form-control"
                                                            id="file">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group has-validation">
                                                    <input type="text" class="form-control" id="fullName" name="name"
                                                        value="{{ old('name', Auth::user()->name) }}"
                                                        placeholder="Full Name*" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="about" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group has-validation">
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        value="{{ old('address', Auth::user()->address) }}"
                                                        placeholder="Address*" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="contact_no" class="col-md-4 col-lg-3 col-form-label">Contact
                                                Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group has-validation">
                                                    <input type="text" class="form-control" id="contact_no"
                                                        name="contact_no"
                                                        value="{{ old('contact_no', Auth::user()->contact_no) }}"
                                                        placeholder="Contact Number*">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Password" class="col-md-4 col-lg-3 col-form-label">Mobile
                                                Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group has-validation">
                                                    <input type="text" class="form-control"
                                                        id="mobile_no"name="mobile_no"
                                                        value="{{ old('mobile_no', Auth::user()->mobile_no) }}"
                                                        placeholder="Mobile Number*" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="Password" class="col-md-4 col-lg-3 col-form-label">Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="input-group has-validation py-2">
                                                    <input type="password" class="form-control"
                                                        id="password"name="password"
                                                        placeholder="Password*">
                                                </div>
                                                <div class="input-group has-validation py-2">
                                                    <input type="password" class="form-control"
                                                        id="password_confirmation"name="password_confirmation"
                                                        placeholder="Confirm Password*">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form><!-- End Profile Edit Form -->

                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main><!-- End #main -->
@endsection
@section('footer')
    <script>
        $(function() {
            $('.select2').select2();
            $('.select-all').click(function() {
                let val = $(this).parent().siblings('select');
                if (val) {
                    val.children().prop("selected", "selected");
                    val.trigger("change");
                }
            })
            $('.deselect-all').click(function() {
                let val = $(this).parent().siblings('select');
                if (val) {
                    val.children().removeAttr("selected");
                    val.trigger("change");
                }
            })
        });
    </script>
@endsection
