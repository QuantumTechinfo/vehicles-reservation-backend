@extends('themes.layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Team Members Management</h1>

        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid d-flex justify-content-center mb-3">
                                <h5 class="input-title">Add Team Members</h5>
                            </div>

                            <!-- Vertical Form -->
                            <form class="row g-3 needs-validation" action='{{ route('user.store') }}' method="post"
                                enctype="multipart/form-data" novalidate>
                                @csrf
                                <div class="container-fluid p-2 input-container1">
                                    <div class="col-12">
                                        <label for="team-members-name" class="input-label">Team Members Name</label>
                                        <div class="input-group has-validation">
                                            <input type="text" class="form-control" name="name" id="team-members-name"
                                                placeholder="Team Members Name*" required>
                                            @error('name')
                                                <div class="invalid-feedback d-block">
                                                    required team-members-name
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid p-2 input-container2">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="address" class="input-label">Address</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="address" id="address"
                                                    placeholder="Address*" required>
                                                @error('address')
                                                    <div class="invalid-feedback d-block">
                                                        required address
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="file_upload" class="input-label">Image Upload</label>
                                            <div class="input-group has-validation">
                                                <input type="file" class="form-control" id="file_upload" name="file"
                                                    placeholder="Image Upload*" required>
                                                @error('file')
                                                    <div class="invalid-feedback">
                                                        required file_upload
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="container-fluid p-2 input-container1">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="contact_no" class="input-label">Contact Number</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="contact_no" id="contact_no"
                                                    placeholder="Contact Number*" required>
                                                @error('contact_no')
                                                    <div class="invalid-feedback d-block">
                                                        required contact_no
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="mobile_no" class="input-label">Mobile Number</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="mobile_no" id="mobile_no"
                                                    placeholder="Mobile Number*" required>
                                                @error('mobile_no')
                                                    <div class="invalid-feedback d-block">
                                                        required mobile_no
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="email_id" class="input-label">Email Id</label>
                                            <div class="input-group has-validation">
                                                <input type="email" class="form-control" required name="email"
                                                    id="email_id" placeholder="Email Id*">
                                                @error('email')
                                                    <div class="invalid-feedback d-block">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid p-2 input-container2">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="joined_date" class="input-label">Joined Date</label>
                                            <div class="input-group has-validation">
                                                <input type="date" class="form-control" name="joined_date"
                                                    id="joined_date" placeholder="Joined Date*" required>
                                                @error('joined_date')
                                                    <div class="invalid-feedback d-block">
                                                        required joined_date
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="Branch" class="input-label">Branch</label>
                                            <div class="input-group has-validation">
                                                <select id="Branch" class="form-select" name="branch_id" required>
                                                    <option value="">Choose Branch...</option>
                                                    @foreach ($variables['branch'] as $data)
                                                        <option value="{{ $data->id }}">{{ $data->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                    <div class="invalid-feedback d-block">
                                                        required Branch
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="position" class="input-label">Position</label>
                                            <div class="input-group has-validation">
                                                <select id="position" class="form-select" name="position_id" required>
                                                    <option value="">Choose Position...</option>
                                                    @foreach ($variables['position'] as $data)
                                                        <option value="{{ $data->id }}">{{ $data->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('postion_id')
                                                    <div class="invalid-feedback d-block">
                                                        required position
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid p-2 input-container1">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="department" class="input-label">Department</label>
                                            <div class="input-group has-validation">
                                                <select id="department" class="form-select" name="department_id"
                                                    required>
                                                    <option value="">Choose Department...</option>
                                                    @foreach ($variables['department'] as $data)
                                                        <option value="{{ $data->id }}">{{ $data->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('department_id')
                                                    <div class="invalid-feedback d-block">
                                                        required department
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <label for="role" class="input-label">Role</label>
                                            <div class="input-group has-validation">
                                                <select placeholder="Role*" id="role" class="form-select select2"
                                                    name="role_id[]" multiple required>
                                                    @foreach ($variables['role'] as $data)
                                                        <option value="{{ $data->id }}">{{ $data->title }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role_id[]')
                                                    <div class="invalid-feedback d-block">
                                                        required role
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="order" class="input-label">Display Order</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="order"
                                                    id="team-members-order" placeholder="Display Order*" required>
                                                @error('order')
                                                    <div class="invalid-feedback d-block">
                                                        required order
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid pt-2 input-container2">
                                    <label class="input-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-control form-control-sm">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="container-fluid pt-2 input-container1">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="password" class="input-label">Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Password*" required>
                                                @error('password')
                                                    <div class="invalid-feedback d-block">
                                                        Required password
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="cpassword" class="input-label">Confirm Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    id="cpassword" placeholder="Confirm Password*" required>
                                                @error('password')
                                                    <div class="invalid-feedback d-block">
                                                        Required confirm password
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-start">
                                    <button type="submit" class="btn"
                                        style="background-color: #0b2982; color:white">Submit</button>
                                    <button type="reset" class="btn"
                                        style="background-color: #b80000; color:white">Reset</button>
                                </div>
                            </form><!-- Vertical Form -->

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
