<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Session;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
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
        
    }

    public function switchStore(Request $request, $id)
    {
        $this->forgetStore();
        
        $store = Store::findOrFail($id);

        Session::put('company_store_id', $store->id);
        Session::put('company_store_code', $store->code);
        Session::put('company_store_name', $store->name);

        return redirect()->route('dashboard-store')->with('success', "Anda masuk ke {$store->name}");
    }

    public function exitStore(Request $request, $route = 'dashboard')
    {
        $this->forgetStore();

        return redirect()->route($route)->with('success', 'Anda telah keluar dari store!');
    }

    public function forgetStore()
    {
        foreach(session()->all() as $key => $value) {
            if(str_contains($key, 'store')) {
                session()->forget($key);				
            }
        }
    }
}
