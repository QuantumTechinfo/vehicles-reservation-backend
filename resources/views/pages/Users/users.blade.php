@extends('Layouts.Sidebar_Layouts')


@section('content')
    <style>
        .table-components{
            margin-left: 250px;
            margin-right: 50px;
        }
    </style>
    <section class="table-components">
        <div class="container-fluid m-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <h6 class="mb-10">Data Table</h6>
                        <div class="table-wrapper table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <h6>#</h6>
                                        </th>
                                        <th>
                                            <h6>Name</h6>
                                        </th>
                                        <th>
                                            <h6>Email</h6>
                                        </th>
                                        <th>
                                            <h6>Project</h6>
                                        </th>
                                        <th>
                                            <h6>Status</h6>
                                        </th>
                                        <th>
                                            <h6>Action</h6>
                                        </th>
                                    </tr>
                                    <!-- end table row-->
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="employee-image">
                                                <img src="assets/images/lead/lead-1.png" alt="" />
                                            </div>
                                        </td>
                                        <td class="min-width">
                                            <p>Esther Howard</p>
                                        </td>
                                        <td class="min-width">
                                            <p><a href="#0">yourmail@gmail.com</a></p>
                                        </td>
                                        <td class="min-width">
                                            <p>Admin Dashboard Design</p>
                                        </td>
                                        <td class="min-width">
                                            <span class="status-btn active-btn">Active</span>
                                        </td>
                                        <td>
                                            <div class="action">
                                                <button class="text-danger">
                                                    <i class="lni lni-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- end table -->
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- ========== tables-wrapper end ========== -->
        </div>
        <!-- end container -->
    </section>

@endsection