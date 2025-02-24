<?php

namespace Reservation\Controllers;

use Reservation\Models\Reservation;
use Reservation\Models\ReservationHistory; // Import the ReservationHistory model
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateReservationStatusController extends Controller
{
    public function updateStatus(Request $request, string $id)
    {
        // abort_if(Gate::denies('update_reservation_status'), response()->json(['error' => 'Unauthorized.'], 403));

        try {
            DB::beginTransaction();

            $reservation = Reservation::findOrFail($id);
            // Check if the reservation status is already approved or rejected
            if (in_array($reservation->status, ['approved', 'rejected'])) {
                return response()->json([
                    'message' => 'Cannot update reservation that is already approved or rejected.'
                ], 400);
            }
            $request->validate([
                'status' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
                'remarks' => ['nullable', 'string'],
                'no_of_days' => ['nullable', 'integer'],
                'rate' => ['nullable', 'numeric'],
                'total_amount' => ['nullable', 'numeric'],
                'commission' => ['nullable', 'numeric'],
                'vehicle_id' => ['nullable', 'exists:vehicles,id'], // Validate vehicle_id exists if provided
            ]);

            $status = $request->input('status');

            $data = [
                'status' => $status,
                'remarks' => $request->input('remarks'),
            ];

            // Create a history record only if the status is approved
            if ($status === 'approved') {
                $vehicleId = $request->input('vehicle_id'); // Get vehicle_id from the request

                // Check if vehicle_id is provided
                if (is_null($vehicleId)) {
                    throw new \Exception('Vehicle ID cannot be null for approved status.');
                }

                // Check for overlapping reservation history entries for the vehicle
                $overlappingReservations = ReservationHistory::where('vehicle_id', $vehicleId)
                    ->where(function ($query) use ($reservation) {
                        $query->whereBetween('entry_date', [$reservation->start_time, $reservation->end_time])
                            ->orWhereBetween('entry_date', [$reservation->start_time, $reservation->end_time]);
                    })
                    ->exists();

                if ($overlappingReservations) {
                    throw new \Exception('The selected vehicle is already booked for the specified dates.');
                }

                ReservationHistory::create([
                    'reservation_id' => $reservation->id,
                    'vehicle_id' => $vehicleId, // Use the vehicle_id from the request
                    'no_of_days' => $request->input('no_of_days'), // Get from request input
                    'rate' => $request->input('rate'), // Get from request input
                    'total_amount' => $request->input('total_amount'), // Get from request input
                    'commission' => $request->input('commission'), // Get from request input
                    'entry_date' => now(), // Set entry date to now or as needed
                ]);
            }

            // Log the data to be updated
            \Log::info('Updating reservation with data:', $data);

            // Update the reservation
            $reservation->update($data);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'message' => 'Reservation status updated successfully',
                'data' => $reservation->fresh()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Reservation not found',
                'error' => $e->getMessage()
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reservation status update failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update reservation',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
