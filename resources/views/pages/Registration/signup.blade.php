@extends('Layouts')


@section('content')
  <style>
    .main-content {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    }

    .main-wrapper {
    margin-left: 0px;
    width: 75%;
    }
  </style>

  <main class="main-wrapper">
    <section class="signin-section">
    <div class="container-fluid">
      <div class="row g-0 auth-row">
      <div class="col-lg-6">
        <div class="auth-cover-wrapper bg-primary-100">
        <div class="auth-cover">
          <div class="title text-center">
          <h1 class="text-primary mb-10">Get Started</h1>
          <p class="text-medium">
            Start creating the best possible user experience
            <br class="d-sm-block" />
            for you customers.
          </p>
          </div>
          <div class="cover-image">
          <img src="assets/images/auth/signin-image.svg" alt="" />
          </div>
          <div class="shape-image">
          <img src="assets/images/auth/shape.svg" alt="" />
          </div>
        </div>
        </div>
      </div>
      <!-- end col -->
      <div class="col-lg-6">
        <div class="signup-wrapper">
        <div class="form-wrapper">
          <h6 class="mb-15">Sign Up Form</h6>
          <p class="text-sm mb-25">
          Start creating the best possible user experience for you
          customers.
          </p>
          <form action="#">
          <div class="row">
            <div class="col-12">
            <div class="input-style-1">
              <label>Name</label>
              <input type="text" placeholder="Name" />
            </div>
            </div>
            <!-- end col -->
            <div class="col-12">
            <div class="input-style-1">
              <label>Email</label>
              <input type="email" placeholder="Email" />
            </div>
            </div>
            <!-- end col -->
            <div class="col-12">
            <div class="input-style-1">
              <label>Password</label>
              <input type="password" placeholder="Password" />
            </div>
            </div>

            <div class="col-12">
            <div class="button-group d-flex justify-content-center flex-wrap">
              <button class="main-btn primary-btn btn-hover w-100 text-center">
              Sign Up
              </button>
            </div>
            </div>
          </div>
          <!-- end row -->
          </form>
          <div class="singup-option pt-40">
          <p class="text-sm text-medium text-dark text-center">
            Already have an account? <a href="signin.html">Sign In</a>
          </p>
          </div>
        </div>
        <!-- end col -->
        </div>
        <!-- end row -->
      </div>
    </section>
  </main>


@endsection