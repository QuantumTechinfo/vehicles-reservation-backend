@extends('themes.layouts.app')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Role Management</h1>

        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Role Add</h5>

                            <!-- Vertical Form -->
                            <form method="post" action="{{ route('roles.store') }}" class="row g-3 needs-validation"
                                novalidate>
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
                                        <span class="btn btn-info select-all" style="border-radius: 0">Select All</span>
                                        <span class="btn btn-info deselect-all" style="border-radius: 0">Deselect All
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
