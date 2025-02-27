<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Reservation\Models\Reservation;
use Validator;
use RequireLoader;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::paginate(10);
        return view("pages.Reservation.Reservation", compact("reservations"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($request)
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // // Validate the input
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // Find the reservation
        $reservation = Reservation::findOrFail($id);
        
        // Update the status
        $reservation->update(['status' => $request->status]);

        // Redirect with success message
        // return RequireLoader::success('');
        return redirect()->back()->with('success', 'Reservation status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
