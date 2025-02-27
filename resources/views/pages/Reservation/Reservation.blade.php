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
                                            Start Date
                                        </th>
                                        <th>
                                            Start Time
                                        </th>
                                        <th>
                                            End Date
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
                                            <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('h:i A') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('M d, Y ') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('h:i A') }}</td>
                                            <td>{{ $reservation->ride_option }}</td>
                                            <td>{{ $reservation->client_name }}</td>
                                            <td>{{ $reservation->client_phone }}</td>
                                            <td>{{ $reservation->client_email }}</td>
                                            <td class="min-width">
                                                <!-- View Description -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#reservationModal{{ $reservation->id }}"> Description
                                                </button>

                                                <!-- Description Modal -->
                                                <div class="modal fade" id="reservationModal{{ $reservation->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="reservationModalLabel{{ $reservation->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="reservationModalLabel{{ $reservation->id }}">
                                                                    Description
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                {{ $reservation->description }}
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <form style="display:flex;"
                                                    action="{{ route('reservation.update', $reservation->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" style="border-radius: 4px; margin-right:2px;"
                                                        class="form-select" aria-label="Select Status">
                                                        <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $reservation->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="rejected" {{ $reservation->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                    <button style="border-radius: 4px; margin-left:2px;" type="submit"
                                                        class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="size-5">
                                                            <path fill-rule="evenodd"
                                                                d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>

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