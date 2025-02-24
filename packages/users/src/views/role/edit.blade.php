@extends('themes.layouts.app')

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

                    <div class="card">
                        <div class="card-body">
                            <div class="container-fluid d-flex justify-content-center mb-3">
                                <h5 class="input-title">Edit Role</h5>
                            </div>

                            <!-- Vertical Form -->
                            <form method="post" action="{{ route('roles.update', $role->id) }}"
                                class="row g-3 needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="col-12">
                                    <label for="title" class="form-label">Role Title</label>
                                    <div class="input-group has-validation">
                                        <input type="text" class="form-control" name="title" id="title"
                                            placeholder="Role*" value="{{ old('role->$title', $role->title) }}"required>
                                        <div class="invalid-feedback">
                                            Required Permissions Title
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="required" for="permissions">Permission</label>
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-info select-all" style="border-radius: 0">Select All</span>
                                        <span class="btn btn-info deselect-all" style="border-radius: 0">Deselect All
                                        </span>
                                    </div>
                                    <select
                                        class="form-control select2 {{ $errors->has('permissions') ? 'is-invalid' : '' }}"
                                        name="permissions[]" id="permissions" multiple required>
                                        @foreach ($permissions as $id => $permission)
                                            <option value="{{ $id }}"
                                                {{ in_array($id, old('permissions', [])) || $role->permissions->contains($id) ? 'selected' : '' }}>
                                                {{ $permission }}</option>
                                        @endforeach
                                    </select>
                                    @error('permissions')
                                        <div class="invalid-feedback">
                                            Required roles Permission
                                        </div>
                                    @enderror
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
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
