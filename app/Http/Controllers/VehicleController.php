<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vehicle\Models\Vehicle;



class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only allow admins to access this page.
        abort_if(auth()->user()->role !== 'admin', response()->json(['error' => 'Unauthorized.'], 403));

        // Use pagination so the built-in links in the view work.
        $vehicles = Vehicle::paginate(10);

        // Remove debugging dd() for production.
        // dd($vehicles);

        // Pass the vehicles collection to the view.
        return view('pages.Vehicles.Vehicles', compact('vehicles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
