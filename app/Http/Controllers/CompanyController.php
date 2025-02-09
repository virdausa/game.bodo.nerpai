<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
	{
		$user = Auth::user();
		$companies = $user->companies;
		return view('companies.index', compact('companies'));
	}

	public function create()
	{
		return view('companies.create');
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'location' => 'nullable|string|max:255',
		]);

		Company::create($request->all());

		return redirect()->route('companies.index')->with('success', 'Company created successfully.');
	}

	public function edit(Company $Company)
	{
		return view('companies.edit', compact('Company'));
	}

	public function update(Request $request, Company $Company)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'location' => 'nullable|string|max:255',
		]);

		$Company->update($request->all());

		return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
	}

	public function destroy(Company $Company)
	{
		$Company->delete();

		return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
	}

	public function switchCompany(Request $request, $companyId)
    {
        $company = Company::findOrFail($companyId);

        // Simpan informasi perusahaan yang dipilih di session
        Session::put('company_id', $company->id);
		Session::put('company_name', $company->name);
        Session::put('company_database_url', $company->database);

        return redirect()->route('dashboard')->with('success', "Anda masuk ke {$company->name}");
    }

	public function exitCompany(Request $request, $route = 'lobby')
	{
		// Hapus session company
		session()->forget('company_id');  
		session()->forget('company_name');
		session()->forget('company_database_url');  

		// Redirect ke halaman lobby (atau dashboard utama)
		return redirect()->route($route)->with('status', 'You have exited the company.');
	}
}
