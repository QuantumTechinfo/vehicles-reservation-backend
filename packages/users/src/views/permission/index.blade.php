@extends('themes.layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Permission Management</h1>
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
                        @can('permission_access')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Permission
                                    List</button>
                            </li>
                        @endcan
                        @can('permission_create')
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Add
                                    Permission</button>
                            </li>
                        @endcan
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container-fluid d-flex justify-content-center mb-3">
                                        <h5 class="input-title">List of Permission</h5>
                                    </div>
                                    {{-- <div class="container-fluid py-2">
                            <a class="btn btn-primary" href="{{route('permissions.create')}}">Add Permission</a>
                        </div> --}}
                                    <!-- Default Table -->
                                    <table class="table table-striped table-hover" id="table" style="width:100%">
                                        <thead style="background-color: #29469d;">
                                            <tr>
                                                <th class="text-light" scope="col">#</th>
                                                <th class="text-light" scope="col">Permission</th>
                                                <th class="text-light" scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $key => $permission)
                                                <tr>
                                                    <th scope="row">{{ $key + 1 }}</th>
                                                    <td>{{ $permission->title }}</td>
                                                    <td>
                                                        <div class="container-fluid d-flex">
                                                            @can('permission_edit')
                                                                <a href="{{ route('permissions.edit', $permission->id) }}"><i
                                                                        class="bi bi-pencil-square"></i></a>
                                                            @endcan
                                                            @can('permission_delete')
                                                                <form
                                                                    action="{{ route('permissions.destroy', $permission->id) }}"
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
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container-fluid d-flex justify-content-center mb-3">
                                        <h5 class="input-title">Add Permission</h5>
                                    </div>

                                    <!-- Vertical Form -->
                                    <form method="post" action="{{ route('permissions.store') }}"
                                        class="row g-3 needs-validation" novalidate>
                                        @csrf
                                        <div class="col-12">
                                            <label for="title" class="input-label">Permission Title</label>
                                            <div class="input-group has-validation">
                                                <input type="text" class="form-control" name="title" id="title"
                                                    placeholder="Permission*" required>
                                                <div class="invalid-feedback">
                                                    Required Permissions Title
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
@endsection
