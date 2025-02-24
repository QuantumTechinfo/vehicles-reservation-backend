@extends('themes.layouts.app')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Permission Management</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item">Permission</li>
            <li class="breadcrumb-item active">Add</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->

      <section class="section">
        <div class="row">

          <div class="col-lg-12">

            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Permission Add</h5>

                <!-- Vertical Form -->
                <form method="post" action="{{route('permissions.store')}}" class="row g-3 needs-validation" novalidate >
                    @csrf
                  <div class="col-12">
                    <label for="title" class="input-label">Permission Title</label>
                    <div class="input-group has-validation">
                        <input type="text" class="form-control" name="title" id="title" placeholder="Permission*" required>
                        <div class="invalid-feedback">
                            Required Permissions Title
                        </div>
                    </div>
                  </div>

                  <div class="text-start">
                    <button type="submit" class="btn" style="background-color: #0b2982; color:white">Submit</button>
                    <button type="reset" class="btn" style="background-color: #b80000; color:white">Reset</button>
                  </div>
                </form><!-- Vertical Form -->

              </div>
            </div>


          </div>
        </div>
      </section>
  </main><!-- End #main -->
@endsection
