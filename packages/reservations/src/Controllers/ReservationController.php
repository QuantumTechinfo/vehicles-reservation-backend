<?php

namespace Reservation\Controllers;

use Reservation\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        // abort_if(Gate::denies('reservation_access'), response()->json(['error' => 'Unauthorized.'], 403));

        $query = Reservation::with('history');

        // Search functionality
        if ($request->has('keyword') && !empty($request->keyword)) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('from_location', 'LIKE', "%{$keyword}%")
                    ->orWhere('to_location', 'LIKE', "%{$keyword}%")
                    ->orWhere('client_name', 'LIKE', "%{$keyword}%")
                    ->orWhere('client_email', 'LIKE', "%{$keyword}%");
            });
        }

        // Check if pagination is requested
        if ($request->has('page') || $request->has('perPage')) {
            $perPage = $request->input('perPage', 10);
            $reservations = $query->paginate($perPage);

            $data = $reservations->through(function ($reservation) {
                return $this->formatReservation($reservation);
            });

            return response()->json(['data' => $data], 200);
        } else {
            // If no pagination is requested, return all results
            $reservations = $query->get();

            $data = $reservations->map(function ($reservation) {
                return $this->formatReservation($reservation);
            });

            return response()->json(['data' => $data], 200);
        }
    }

    private function formatReservation($reservation)
    {
        return [
            'id' => $reservation->id,
            'from_location' => $reservation->from_location,
            'to_location' => $reservation->to_location,
            'start_time' => $reservation->start_time,
            'end_time' => $reservation->end_time,
            'client_name' => $reservation->client_name,
            'client_phone' => $reservation->client_phone,
            'client_email' => $reservation->client_email,
            'description' => $reservation->description,
            'status' => $reservation->status,
            'history' => $reservation->history,
        ];
    }

    public function store(Request $request)
    {
        // abort_if(Gate::denies('reservation_create'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();

            $data = $request->validate([
                'from_location' => ['required', 'string', 'max:255'],
                'to_location' => ['required', 'string', 'max:255'],
                'start_time' => ['required', 'date'],
                'end_time' => ['nullable', 'date', 'after_or_equal:start_time'],
                'ride_option' => ['required', Rule::in(['shared', 'entire_cabin'])], // Added ride_option validation
                'client_name' => ['required', 'string', 'max:255'],
                'client_phone' => ['required', 'string', 'max:20'],
                'client_email' => ['required', 'email', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['nullable', Rule::in(['pending', 'approved', 'rejected'])], // Used string values directly
            ]);

            // Convert datetime to MySQL format
            $data['start_time'] = Carbon::parse($data['start_time'])->format('Y-m-d H:i:s');
            $data['end_time'] = isset($data['end_time']) ? Carbon::parse($data['end_time'])->format('Y-m-d H:i:s') : null;
            $data['status'] = $data['status'] ?? 'pending'; // Default to 'pending' if not provided

            $reservation = Reservation::create($data);

            DB::commit();

            return response()->json([
                'message' => 'Reservation created successfully',
                'data' => $reservation
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation creation failed: ' . $e->getMessage());

            return response()->json([
                'error' => 'An unexpected error occurred. Please try again.'
            ], 500);
        }
    }

    public function show(string $id)
    {
        // abort_if(Gate::denies('reservation_access'), response()->json(['error' => 'Unauthorized.'], 403));
        try {
            $reservation = Reservation::with('history')->findOrFail($id);
            return response()->json(['data' => $reservation], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Reservation not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        // abort_if(Gate::denies('reservation_edit'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);

            $data = $request->validate([
                'from_location' => ['sometimes', 'required', 'string'],
                'to_location' => ['sometimes', 'required', 'string'],
                'start_time' => ['sometimes', 'required', 'date'],
                'end_time' => ['sometimes', 'required', 'date', 'after:start_time'],
                'client_name' => ['sometimes', 'required', 'string'],
                'client_phone' => ['sometimes', 'required', 'string'],
                'client_email' => ['sometimes', 'required', 'string', 'email'],
                'description' => ['nullable', 'string'],
                'status' => ['sometimes', 'nullable', Rule::in([Reservation::STATUS_PENDING, Reservation::STATUS_APPROVED, Reservation::STATUS_REJECTED])],
            ]);
            // Convert datetime to MySQL format
            $data['start_time'] = Carbon::parse($data['start_time'])->format('Y-m-d H:i:s');
            $data['end_time'] = Carbon::parse($data['end_time'])->format('Y-m-d H:i:s');
            $reservation->update($data);

            DB::commit();

            return response()->json(['message' => 'Reservation updated successfully', 'data' => $reservation->fresh()], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Reservation not found', 'error' => $e->getMessage()], 404);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation update failed: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to update Reservation', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        // abort_if(Gate::denies('reservation_delete'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();
            $reservation = Reservation::findOrFail($id);

            $reservation->delete();
            DB::commit();

            return response()->json(['message' => 'Reservation deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Reservation not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation deletion failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function userReservation(Request $request)
    {
        // abort_if(Gate::denies('reservation_access'), response()->json(['error' => 'Unauthorized.'], 403));
        try {
            $query = Reservation::where('client_email', Auth::user()->email);

            // Search functionality
            if ($request->has('keyword') && !empty($request->keyword)) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->where('from_location', 'LIKE', "%{$keyword}%")
                        ->orWhere('to_location', 'LIKE', "%{$keyword}%")
                        ->orWhere('client_name', 'LIKE', "%{$keyword}%");
                });
            }

            // Check if pagination is requested
            if ($request->has('page') || $request->has('perPage')) {
                $perPage = $request->input('perPage', 10);
                $reservations = $query->paginate($perPage);

                $data = $reservations->through(function ($reservation) {
                    return $this->formatReservation($reservation);
                });

                return response()->json(['data' => $data], 200);
            } else {
                // If no pagination is requested, return all results
                $reservations = $query->get();

                $data = $reservations->map(function ($reservation) {
                    return $this->formatReservation($reservation);
                });

                return response()->json(['data' => $data], 200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Reservations not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
