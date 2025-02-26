<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reservation') }}
        </h2>
    </x-slot>


    <section class="table-components">
        <div class="container-fluid m-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <div class="table-wrapper table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            From
                                        </th>
                                        <th>
                                            To
                                        </th>
                                        <th>
                                            Start Time
                                        </th>
                                        <th>
                                            End Time
                                        </th>
                                        <th>
                                            Ride Option
                                        </th>
                                        <th>
                                            Clinet Name
                                        </th>
                                        <th>
                                            Clinet Phone
                                        </th>
                                        <th>
                                            Clinet Email
                                        </th>
                                        <th>
                                            Description
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservations as $reservation)
                                        <tr>
                                            <td>{{ $reservation->from_location }}</td>
                                            <td>{{ $reservation->to_location }}</td>
                                            <td>{{ $reservation->start_time }}</td>
                                            <td>{{ $reservation->end_time }}</td>
                                            <td>{{ $reservation->ride_option }}</td>
                                            <td>{{ $reservation->client_name }}</td>
                                            <td>{{ $reservation->client_phone }}</td>
                                            <td>{{ $reservation->client_email }}</td>
                                            <td>{{ $reservation->description }}</td>
                                            <td>
                                                @if ($reservation->status == "pending")
                                                    <button type="button" class="btn btn-success">Success</button>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- Laravel pagination links -->
                            <nav aria-label="Page navigation example">
                                {{ $reservations->links() }}
                            </nav>
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
</x-app-layout>