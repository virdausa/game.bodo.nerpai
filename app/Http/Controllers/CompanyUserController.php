<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyUser;
use App\Models\User;

class CompanyUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company_users = CompanyUser::all();
        $users = User::all();
        
        return view('company_users.index', compact('company_users', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company_users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        
        $user = User::find($request->user_id);      // validasi user_id
        if(!$user){
            return redirect()->route('company_users.index')->with('error', "User tidak ditemukan!");
        }

        CompanyUser::create([
            'user_id' => $request->user_id,
            'user_type' => 'guest',
            'status' => 'invited',
        ]);

        // update data ke companies_users
        $user->companies()->attach(session('company_id'), ['status' => 'invited']);

        return redirect()->route('company_users.index')->with('success', "User {$user->name} has been invited successfully!");
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

    public function cancelInvite(string $id)
    {
        // delete data ke company_user
        $company_user = CompanyUser::with('user')->find($id);
        if (!$company_user) {
            return redirect()->route('company_users.index')->with('error', 'Invite not found.');
        }

        $company_user->delete();
        
        $user = $company_user->user;
        if (!$user) {
            return redirect()->route('company_users.index')->with('error', 'User not found.');
        }

        // delete data ke companies_users
        $user->companies()->detach(session('company_id'));

        return redirect()->route('company_users.index')->with('success', 'Invite ditarik successfully.');
    }
}
