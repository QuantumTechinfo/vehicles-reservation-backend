<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
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
                                            <h6>#</h6>
                                        </th>
                                        <th>
                                            <h6>Name</h6>
                                        </th>
                                        <th>
                                            <h6>Email</h6>
                                        </th>
                                        <th>
                                            <h6>Role</h6>
                                        </th>
                                        <th>
                                            <h6>Phone</h6>
                                        </th>
                                        <th>
                                            <h6>Verified</h6>
                                        </th>
                                        <th>
                                            <h6>Actions</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>
                                                <div class="employee-image">
                                                    <!-- Display the user's profile image if available; fallback to a default -->
                                                    <img src="{{ asset($user->profile_image ?? 'assets/images/lead/lead-1.png') }}"
                                                        alt="{{ $user->name }}" />
                                                </div>
                                            </td>
                                            <td class="min-width">
                                                <p>{{ $user->name }}</p>
                                            </td>
                                            <td class="min-width">
                                                <p><a href="#">{{ $user->email }}</a></p>
                                            </td>
                                            <td class="min-width">
                                                <p>{{ $user->role }}</p>
                                            </td>
                                            <td class="min-width">
                                                <p>{{ $user->phone_no }}</p>
                                            </td>

                                            <td class="min-width">
                                                @if($user->is_verified)
                                                    <span class="status-btn success-btn">Verified</span>
                                                @else
                                                    <span class="status-btn danger-btn">Not Verified</span>
                                                @endif
                                            </td>
                                            
                                            <td>
                                                <div class="d-flex ">
                                                    <!-- Example action buttons with JS functions (you can modify these as needed) -->
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

                                                    <form class="px-2" action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"><svg
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
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
                                {{ $users->links() }}
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