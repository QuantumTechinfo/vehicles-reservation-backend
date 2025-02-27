<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Vehicles') }}
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
                                            <h6>Uploader Name</h6>
                                        </th>
                                        <th>
                                            <h6>Uploader Email</h6>
                                        </th>
                                        <th>
                                            <h6>Vehicle Name</h6>
                                        </th>
                                        <th>
                                            <h6>Vehicle Number</h6>
                                        </th>
                                        <th>
                                            <h6>Vehicle Description</h6>
                                        </th>
                                        <th>
                                            <h6>Vehicle Workers</h6>
                                        </th>
                                        <th>
                                            <h6>Blue Book </h6>
                                        </th>
                                        <th>
                                            <h6>Images</h6>
                                        </th>
                                        <th>
                                            <h6>Action</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vehicles as $vehicle)
                                        <tr>
                                            <td class="min-width">
                                                <p> {{ optional($vehicle->uploader)->name }} </p>
                                            </td>
                                            <td>
                                                <p> {{ optional($vehicle->uploader)->email }} </p>
                                            </td>

                                            <td class="min-width">
                                                <p>{{ $vehicle->vehicle_name }}</p>
                                            </td>
                                            <td class="min-width">
                                                <p><a href="#">{{ $vehicle->vehicle_number }}</a></p>
                                            </td>
                                            <td class="min-width">
                                                <!-- View Description -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#vehicleModal{{ $vehicle->id }}"> Description
                                                </button>

                                                <!-- Description Modal -->
                                                <div class="modal fade" id="vehicleModal{{ $vehicle->id }}" tabindex="-1"
                                                    role="dialog" aria-labelledby="vehicleModalLabel{{ $vehicle->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="vehicleModalLabel{{ $vehicle->id }}">
                                                                    Description
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- {{ $vehicle->vehicle_description }} -->
                                                                <section class="table-components">
                                                                    <div class="container-fluid">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="card-style">
                                                                                    <div
                                                                                        class="table-wrapper table-responsive">
                                                                                        <table class="table">
                                                                                            <thead>
                                                                                                <th>Facility</th>
                                                                                                <th>Status</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach(json_decode($vehicle->drivers) as $driver)
                                                                                                    <tr>
                                                                                                    <td>{{ $driver->name }}</td>
                                                                                                    <td><span class="status-btn active-btn">yes</span></td>                                                                                               </td>
                                                                                                </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </section>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="min-width">
                                                <!-- View Workers -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#vehicleModalDescription{{ $vehicle->id }}"> Workers
                                                </button>

                                                <!-- Workers Modal -->
                                                <div class="modal fade" id="vehicleModalDescription{{ $vehicle->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="vehicleModalDescriptionLabel{{ $vehicle->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="vehicleModalDescriptionLabel{{ $vehicle->id }}">
                                                                    Vehicle Workers
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <section class="table-components">
                                                                    <div class="container-fluid">
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="card-style">
                                                                                    <div
                                                                                        class="table-wrapper table-responsive">
                                                                                        <table class="table">
                                                                                            <thead>
                                                                                                <th>Name</th>
                                                                                                <th>Contact No</th>
                                                                                                <th>Lisence No</th>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                @foreach(json_decode($vehicle->drivers) as $driver)
                                                                                                    <tr>
                                                                                                    <td>{{ $driver->name }}</td>
                                                                                                    <td>{{ $driver->contact_number }}</td>
                                                                                                    <td>{{ $driver->license_no }}</td>
                                                                                                    </tr>
                                                                                                @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </section>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="min-width">
                                                <!-- Blue Book Modal Trigger -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#vehicleModalBluebook{{ $vehicle->id }}"> Blue Book
                                                </button>

                                                <!-- Blue Book Modal -->
                                                <div class="modal fade" id="vehicleModalBluebook{{ $vehicle->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="vehicleModalBluebookLabel{{ $vehicle->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="vehicleModalBluebookLabel{{ $vehicle->id }}">
                                                                    Blue Book
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                            
                                                                <img src="{{ asset('storage/' . $vehicle->blue_book) }}" alt="">

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="min-width">
                                                <!-- Images Modal Trigger -->
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#vehicleModalImages{{ $vehicle->id }}"> Images </button>

                                                <!-- Images Modal -->
                                                <div class="modal fade" id="vehicleModalImages{{ $vehicle->id }}"
                                                    tabindex="-1" role="dialog"
                                                    aria-labelledby="vehicleModalImagesLabel{{ $vehicle->id }}"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="vehicleModalImagesLabel{{ $vehicle->id }}">
                                                                    Images
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!-- Assuming this is where you want to show additional images -->
                                                                <!-- {{ $vehicle->drivers }} -->
                                                                @foreach (json_decode($vehicle->vehicle_images) as $images)
                                                                    <img src="{{ asset('storage/' . $images) }}" alt="">

                                                                @endforeach
                                                                
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
                                                <div class="d-flex">
                                                    <!-- Button trigger modal -->
                                                    <button type="button" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" class="size-5">
                                                            <path
                                                                d="m5.433 13.917 1.262-3.155A4 4 0 0 1 7.58 9.42l6.92-6.918a2.121 2.121 0 0 1 3 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 0 1-.65-.65Z" />
                                                            <path
                                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0 0 10 3H4.75A2.75 2.75 0 0 0 2 5.75v9.5A2.75 2.75 0 0 0 4.75 18h9.5A2.75 2.75 0 0 0 17 15.25V10a.75.75 0 0 0-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5Z" />
                                                        </svg>
                                                    </button>

                                                    <form class="px-2"
                                                        action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this vehicle?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                fill="currentColor" class="size-5">
                                                                <path fill-rule="evenodd"
                                                                    d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 0-1.5.06l.3 7.5a.75.75 0 1 0 1.5-.06l-.3-7.5Zm4.34.06a.75.75 0 1 0-1.5-.06l-.3 7.5a.75.75 0 1 0 1.5.06l.3-7.5Z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <!-- Laravel pagination links -->
                            <nav aria-label="Page navigation example">
                                {{ $vehicles->links() }}
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