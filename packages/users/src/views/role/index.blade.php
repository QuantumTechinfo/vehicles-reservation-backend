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
            <h1>Role Management</h1>

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

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            @can('role_access')
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">View
                                    Roles</button>
                            @endcan
                            @can('permission_access')
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                                    type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Add
                                    Roles</button>
                            @endcan
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container-fluid d-flex justify-content-center mb-3">
                                        <h5 class="input-title">List of Role</h5>
                                    </div>
                                    {{-- @can('role_create')
                                        <div class="container-fluid py-2">
                                            <a class="btn btn-primary" href="{{ route('roles.create') }}">Add Role</a>
                                        </div>
                                    @endcan --}}
                                    <!-- Default Table -->
                                    <table class="table text-nowrap table-striped" id="table" style="width:100%">
                                        <!--Table head-->
                                        <thead style="background-color: #29469d;">
                                            <tr>
                                                <th class="text-light" scope="col">#</th>
                                                <th class="text-light" scope="col">Role</th>
                                                <th class="text-light" scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $key => $role)
                                                <tr>
                                                    <th scope="row">{{ $key + 1 }}</th>
                                                    <td>{{ $role->title }}</td>
                                                    <td>
                                                        <div class="container-fluid d-flex">
                                                            @can('role_edit')
                                                                <a href="{{ route('roles.edit', $role->id) }}"><i
                                                                        class="bi bi-pencil-square"></i></a>
                                                            @endcan
                                                            @can('role_delete')
                                                                <form action="{{ route('roles.destroy', $role->id) }}"
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
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- End Default Table Example -->
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container-fluid d-flex justify-content-center mb-3">
                                        <h5 class="input-title">Add Role</h5>
                                    </div>
                                    <!-- Vertical Form -->
                                    <form method="post" action="{{ route('roles.store') }}"
                                        class="row g-3 needs-validation" novalidate>
                                        @csrf
                                        <div class="col-12">
                                            <label for="title" class="input-label">Role Title</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="title" id="title"
                                                    placeholder="Role*" required>
                                                @error('title')
                                                    <div class="invalid-feedback">
                                                        Required roles Title
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="required" for="permissions">Permission</label>
                                            <div style="padding-bottom: 4px">
                                                <span class="btn btn-info select-all" style="border-radius: 0">Select
                                                    All</span>
                                                <span class="btn btn-info deselect-all" style="border-radius: 0">Deselect
                                                    All
                                                </span>
                                            </div>
                                            <select
                                                class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}"
                                                name="permissions[]" id="permissions" multiple required>
                                                @foreach ($permissions as $id => $permissions)
                                                    <option value="{{ $id }}"
                                                        {{ in_array($id, old('permissions', [])) ? 'selected' : '' }}>
                                                        {{ $permissions }}</option>
                                                @endforeach
                                            </select>
                                            @error('permissions')
                                                <div class="invalid-feedback">
                                                    Required roles Permission
                                                </div>
                                            @enderror
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
