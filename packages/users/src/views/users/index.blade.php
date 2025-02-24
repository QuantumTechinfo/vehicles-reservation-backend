@extends('themes.layouts.app')

@section('header')
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Team Member Management</h1>

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
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @can('user_access')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Team
                                    Members</button>
                            </li>
                        @endcan
                        @can('user_create')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Add Team
                                    Members</button>
                            </li>
                        @endcan

                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card" style="min-height: 35vw;">
                                <div class="card-body">
                                    <div class="container-fluid d-flex justify-content-center mb-3">
                                        <h5 class="input-title">List of Team Members</h5>
                                    </div>
                                    <section>
                                        {{-- <div class="container">
                                            <div class="row d-flex py-2 ">
                                                <div class="col-2">
                                                    <!-- Button to Open the Modal -->
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#myModal">
                                                        Open modal
                                                    </button>

                                                    <!-- The Modal -->
                                                    <div class="modal" id="myModal">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">

                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Modal Heading</h4>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>

                                                                <!-- Modal body -->
                                                                <div class="modal-body">

                                                                </div>

                                                                <!-- Modal footer -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-2">
                                                    <button>Export in Excel </button>
                                                </div>
                                            </div>
                                        </div> --}}

                                        <!-- Modal -->
                                        {{-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ...
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary">Save changes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <!--Table-->
                                        <table class="table text-nowrap table-striped" id="table" class="display nowrap"
                                            style="width:100%">
                                            <!--Table head-->
                                            <thead style="background-color: #29469d;">
                                                <tr>
                                                    <th class="text-light" scope="col">SN</th>
                                                    <th class="text-light" scope="col">Employee Code</th>
                                                    <th class="text-light" scope="col">Image</th>
                                                    <th class="text-light" scope="col">Order</th>
                                                    <th class="text-light" scope="col">Active</th>
                                                    <th class="text-light" scope="col">Name</th>
                                                    <th class="text-light" scope="col">Branch</th>
                                                    <th class="text-light" scope="col">Position</th>
                                                    <th class="text-light" scope="col">Department</th>
                                                    <th class="text-light" scope="col">Contact No.</th>
                                                    <th class="text-light" scope="col">Mobile No.</th>
                                                    <th class="text-light" scope="col">Address</th>
                                                    <th class="text-light" scope="col">Email Id</th>
                                                    <th class="text-light" scope="col">Joined Date</th>
                                                    <th class="text-light" scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            @php
                                                if (Auth::user()->IsAdmin) {
                                                    $datas = $variables['employee'];
                                                    $datas->load('user', 'branch', 'department', 'position');
                                                } else {
                                                    $branch = Auth::user()?->employeeDetails?->branch;
                                                    $datas = $branch->employee_details()->get();
                                                    $datas->load('user', 'branch', 'department', 'position');
                                                }
                                            @endphp
                                            <tbody>
                                                @foreach ($datas as $key => $data)
                                                    <tr>
                                                        <th scope="row">{{ $key + 1 }}</th>
                                                        <td>{{ $data->user?->employee_code }}</td>
                                                        <td><a target="_blank"
                                                                href="{{ url(basename(storage_path()) . '/' . $data->user?->media?->file_path) }}"><i
                                                                    class="bi bi-image px-3"></i></a>
                                                        </td>
                                                        <td>{{ $data->order }}</td>
                                                        <td>
                                                            @if ($data->status == 1)
                                                                Active
                                                            @else
                                                                Inactive
                                                            @endif
                                                        </td>
                                                        <td>{{ $data->user?->name }}</td>
                                                        <td>{{ $data->branch?->title }}</td>
                                                        <td>{{ $data->position?->title }}</td>
                                                        <td>{{ $data->department?->title }}</td>
                                                        <td>{{ $data->user?->contact_no }}</td>
                                                        <td>{{ $data->user?->mobile_no }}</td>
                                                        <td>{{ $data->user?->address }}</td>
                                                        <td>{{ $data->user?->email }}</td>
                                                        <td>
                                                            @php
                                                                $date = \Carbon\Carbon::parse($data->user?->joined_date);
                                                                $dc = new \Nilambar\NepaliDate\NepaliDate();
                                                                $nd = $dc->convertAdToBs($date->year, $date->month, $date->day);
                                                                echo $nd['year'] . '-' . $nd['month'] . '-' . $nd['day'];
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            @if ($data->user)
                                                                <div class="container-fluid d-flex">
                                                                    @can('user_edit')
                                                                        <a href="{{ route('user.edit', $data->user->id) }}"><i
                                                                                class="bi bi-pencil-square"></i></a>
                                                                    @endcan
                                                                    @can('user_delete')
                                                                        <form
                                                                            action="{{ route('user.destroy', $data->user?->id) }}"
                                                                            method="POST">
                                                                            @csrf @method('DELETE')
                                                                            <button
                                                                                onclick="return confirm('Do you want to delete this?')"
                                                                                style="border:none;background:none">
                                                                                <i class="bi bi-trash3"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endcan
                                                                </div>
                                                                {{-- @else
                                                            @dd($data->delete()) --}}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container-fluid d-flex justify-content-center mb-3">
                                        <h5 class="input-title">Add Team Members</h5>
                                    </div>

                                    <!-- Vertical Form -->
                                    <form class="row g-3 needs-validation" action='{{ route('user.store') }}'
                                        method="post" enctype="multipart/form-data" novalidate>
                                        @csrf
                                        <div class="container-fluid p-2 input-container1">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="employee-code" class="input-label">Employee Code</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" class="form-control" name="employee_code"
                                                            id="employee-code" placeholder="Employee Code*" required>
                                                        @error('employee_code')
                                                            <div class="invalid-feedback d-block">
                                                                required employee-code
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label for="team-members-name" class="input-label">Team Members
                                                        Name</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" class="form-control" name="name"
                                                            id="team-members-name" placeholder="Team Members Name*"
                                                            required>
                                                        @error('name')
                                                            <div class="invalid-feedback d-block">
                                                                required team-members-name
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="container-fluid p-2 input-container2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <label for="address" class="input-label">Address</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" class="form-control" name="address"
                                                            id="address" placeholder="Address*" required>
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
                                                        <input type="file" class="form-control" id="file_upload"
                                                            name="file" placeholder="Image Upload*" required>
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
                                                        <input type="text" class="form-control" name="contact_no"
                                                            id="contact_no" placeholder="Contact Number*">
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
                                                        <input type="text" class="form-control" name="mobile_no"
                                                            id="mobile_no" placeholder="Mobile Number*" required>
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
                                                        <input type="email" class="form-control" required
                                                            name="email" id="email_id" placeholder="Email Id*">
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
                                                    <label for="nepali_joined_date" class="input-label">Joined
                                                        Date</label>
                                                    <div class="input-group has-validation">
                                                        <input type="text" class="form-control"
                                                            id="nepali-joined-date" id="nepali_joined_date"
                                                            placeholder="Joined Date*" required>
                                                        @error('joined_date')
                                                            <div class="invalid-feedback d-block">
                                                                required joined_date
                                                            </div>
                                                        @enderror

                                                    </div>
                                                    <div class="input-group has-validation">
                                                        <input type="date" class="form-control" id="joined-date"
                                                            name="joined_date" value="{{ old('joined_date') }}"
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
                                                        <select id="Branch" class="form-select" name="branch_id"
                                                            required>
                                                            <option value="">Choose Branch...</option>
                                                            @foreach ($variables['branch'] as $data)
                                                                <option value="{{ $data->id }}">{{ $data->title }}
                                                                </option>
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
                                                        <select id="position" class="form-select" name="position_id"
                                                            required>
                                                            <option value="">Choose Position...</option>
                                                            @foreach ($variables['position'] as $data)
                                                                <option value="{{ $data->id }}">{{ $data->title }}
                                                                </option>
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
                                                                <option value="{{ $data->id }}">{{ $data->title }}
                                                                </option>
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
                                                        <select placeholder="Role*" id="role"
                                                            class="form-select select2" name="role_id[]" multiple
                                                            required>
                                                            @foreach ($variables['role'] as $data)
                                                                <option value="{{ $data->id }}">{{ $data->title }}
                                                                </option>
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
                                                        <input type="password" class="form-control"
                                                            name="password_confirmation" id="cpassword"
                                                            placeholder="Confirm Password*" required>
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
                </div>
            </div>
        </section>
    </main><!-- End #main -->
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
