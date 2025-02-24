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
                                <h5 class="input-title">Edit Team Members</h5>
                            </div>
                            <!-- Vertical Form -->
                            <form class="row g-3 needs-validation" action='{{ route('user.update', $item->id) }}'
                                method="post" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PATCH')
                                <div class="container-fluid pt-3 input-container1">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="employee_code" class="input-label">Employee Code</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="employee_code"
                                                    id="employee_code"
                                                    value="{{ old('employee_code', $item->employee_code) }}"
                                                    placeholder="Employee Code*" required>
                                                @error('employee_code')
                                                    <div class="invalid-feedback">
                                                        required employee_code
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="team-members-name" class="input-label">Team Members Name</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="name"
                                                    id="team-members-name" value="{{ old('name', $item->name) }}"
                                                    placeholder="Team Members Name*" required>
                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        required team-members-name
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="container-fluid pt-1 input-container2">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="address" class="input-label">Address</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="address" id="address"
                                                    value="{{ old('address', $item->address) }}" placeholder="Address*"
                                                    required>
                                                <div class="invalid-feedback">
                                                    required address
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="file_upload" class="input-label">Image Upload</label>
                                            <div class="input-group has-validation">
                                                <input type="file" class="form-control" id="file_upload" name="file"
                                                    placeholder="Image Upload*">
                                                @error('file')
                                                    <div class="invalid-feedback">
                                                        required file_upload
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid pt-3 input-container1">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="contact_no" class="input-label">Contact Number</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="contact_no" id="contact_no"
                                                    value="{{ old('contact_no', $item->contact_no) }}"
                                                    placeholder="Contact Number*" >
                                                @error('contact_no')
                                                    <div class="invalid-feedback">
                                                        required contact_no
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="mobile_no" class="input-label">Mobile Number</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="mobile_no" id="mobile_no"
                                                    value="{{ old('mobile_no', $item->mobile_no) }}"
                                                    placeholder="Mobile Number*" required>
                                                @error('mobile_no')
                                                    <div class="invalid-feedback">
                                                        required mobile_no
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="email_id" class="input-label">Email Id</label>
                                            <div class="input-group has-validation">
                                                <input type="email" class="form-control" name="email" id="email_id"
                                                    value="{{ old('email', $item->email) }}" placeholder="Email Id*"
                                                    required @if (!Auth::user()->IsAdmin) readonly @endif>
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        required email_id
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid pt-3 input-container2">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="joined_date" class="input-label">Joined Date</label>
                                                                                                <div class="input-group has-validation">
                                                        <input type="text" class="form-control"
                                                            id="nepali-joined-date" id="nepali_joined_date"
                                                            placeholder="Joined Date*" required @if (!Auth::user()->IsAdmin) readonly @endif>
                                                        @error('joined_date')
                                                            <div class="invalid-feedback d-block">
                                                                required joined_date
                                                            </div>
                                                        @enderror

                                                    </div>
                                                    <div class="input-group has-validation">
                                                        <input type="date" class="form-control" id="joined-date"
                                                            name="joined_date" value="{{ old('joined_date', $item->joined_date) }}"
                                                            placeholder="Joined Date*" required readonly>
                                                        @error('joined_date')
                                                            <div class="invalid-feedback">
                                                                required Joined Date
                                                            </div>
                                                        @enderror
                                                    </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="Branch" class="input-label">Branch</label>
                                            <div class="input-group has-validation">
                                                <select id="Branch" class="form-select" name="branch_id" required>
                                                    @foreach ($variables['branch'] as $data)
                                                        @if (Auth::user()->IsAdmin || $data->id === $item->employeeDetails->branch_id)
                                                            <option
                                                                value="{{ $data->id }}"{{ in_array($data->id, old('branch', [])) || $data->id === $item->employeeDetails->branch_id ? 'selected' : '' }}>
                                                                {{ $data->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('branch_id')
                                                    <div class="invalid-feedback">
                                                        required Branch
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="position" class="input-label">Position</label>
                                            <div class="input-group has-validation">
                                                <select id="position" class="form-select" name="position_id" required>
                                                    @foreach ($variables['position'] as $data)
                                                        @if (Auth::user()->IsAdmin || $data->id === $item->employeeDetails->position_id)
                                                            <option
                                                                value="{{ $data->id }}"{{ in_array($data->id, old('position', [])) || $data->id === $item->employeeDetails->position_id ? 'selected' : '' }}>
                                                                {{ $data->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('position_id')
                                                    <div class="invalid-feedback">
                                                        required position
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid pt-3 input-container1">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="department" class="input-label">Department</label>
                                            <div class="input-group has-validation">
                                                <select id="department" class="form-select" name="department_id"
                                                    required>
                                                    @foreach ($variables['department'] as $data)
                                                        @if (Auth::user()->IsAdmin || $data->id === $item->employeeDetails->department_id)
                                                            <option
                                                                value="{{ $data->id }}"{{ in_array($data->id, old('deparment', [])) || $data->id === $item->employeeDetails->department_id ? 'selected' : '' }}>
                                                                {{ $data->title }}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                                @error('department_id')
                                                    <div class="invalid-feedback">
                                                        required department
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="role" class="input-label">Role</label>
                                            <div class="input-group has-validation">
                                                <select id="role" class="form-select select2" name="role_id[]"
                                                    required multiple>
                                                    @foreach ($variables['role'] as $data)
                                                        @if (Auth::user()->IsAdmin || $item->roles->contains($data->id))
                                                            <option value="{{ $data->id }}"
                                                                {{ $item->roles->contains($data->id) ? 'selected' : '' }}>
                                                                {{ $data->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('role_id[]')
                                                    <div class="invalid-feedback">
                                                        required role
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="order" class="input-label">Display Order</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="order"
                                                    id="team-members-order" placeholder="Display Order*"
                                                    value="{{ old('order', $item->employeeDetails->order) }}" required
                                                    @if (!Auth::user()->IsAdmin) readonly @endif>
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
                                        @if (Auth::user()->IsAdmin || $item->employeeDetails->status == 1)
                                            <option value="1"
                                                {{ $item->employeeDetails->status == 1 ? 'selected' : '' }}>
                                                Active</option>
                                        @endif
                                        @if (Auth::user()->IsAdmin || $item->employeeDetails->status == 0)
                                            <option value="0"
                                                {{ $item->employeeDetails->status == 0 ? 'selected' : '' }}>
                                                Inactive</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="container-fluid pt-3 ">
                                    <div class="row">
                                        <div class="col-6">
                                            <label for="password" class="input-label">Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" class="form-control" name="password"
                                                    id="password" placeholder="Password*">
                                                @error('password')
                                                    <div class="invalid-feedback">
                                                        required password
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="cpassword" class="input-label">Confirm Password</label>
                                            <div class="input-group has-validation">
                                                <input type="password" class="form-control" name="password_confirmation"
                                                    id="password_confirmation" placeholder="Confirm Password*">
                                                @error('password_confirmation')
                                                    <div class="invalid-feedback">
                                                        required confrimation password
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
        <script>
        $('#nepali-joined-date').nepaliDatePicker({
            dateFormat: '%y-%m-%d',
            closeOnDateSelect: true,
        });
        $('#nepali-joined-date').on("dateChange", function(event) {

            let formattedDate = getDate(event.datePickerData.adDate); // Format the date as YYYY-MM-DD

            $('#joined-date').val(formattedDate)
        });
    </script>
@endsection
