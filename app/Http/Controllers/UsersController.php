<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use app\Models\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only allow admins to access this page.
        abort_if(auth()->user()->role !== 'admin', response()->json(['error' => 'Unauthorized.'], 403));

        // Use pagination so the built-in links in the view work.
        $users = User::paginate(10);

        // Pass the users collection to the view.
        return view('pages.Users.users', compact('users'));
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
        abort_if(auth()->user()->role !== 'admin', response()->json(['error' => 'Unauthorized.'], 403));

        $user = User::findOrFail($id);

        if ($user->id === auth()->user()->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        // Redirect back with a success message.
        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }

}
