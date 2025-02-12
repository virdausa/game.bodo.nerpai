<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

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

	public function configTenant($id){
		$company = Company::findOrFail($id);

		$dbUrl = env('DB_URL');
		$dbConfig = parse_url($dbUrl);
		if(!$dbConfig) {
			return redirect()->route('companies.index')->with('error', 'Database Company tidak valid');
		}
		$dbName = $dbConfig ? (ltrim($dbConfig['path'], '/') . "_" . $id) : ltrim($dbConfig['path'], '/');

		// Konfigurasi koneksi ke database tenant
		// Atur koneksi database dinamis
		Config::set("database.connections.tenant", [
			'driver'    => 'mysql',
			'host'      => $dbConfig['host'],
			'port'      => env('DB_PORT', '3306'),
			'database'  => $dbName,
			'username'  => $dbConfig['user'],
			'password'  => $dbConfig['pass'],
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
			'strict'    => true,
			'engine'    => null,
		]);
	}

	public function acceptInvite(Request $request, $id)
	{
		$company = Company::findOrFail($id);
		$user = Auth::user();
		
		$this->configTenant($company->id);

		$userExist = DB::connection('tenant')
			->table('company_users')
			->where('user_id', $user->id)
			->exists();

		if (!$userExist) {
			// delete data dari companies_users
			$user->companies()->detach($company->id);

			return redirect()->route('companies.index')->with('error', 'Invitation tidak valid');
		}

		// update data ke companies_users
		$user->companies()->updateExistingPivot($company->id, ['status' => 'approved']);

		// update data ke company_user
		DB::connection('tenant')
			->table('company_users')
			->where('user_id', $user->id)
			->update([
				'status' => 'approved',
			]);

		return redirect()->route('companies.index')->with('success', 'Invite accepted successfully.');
	}

	public function rejectInvite(string $id)
    {
		$company = Company::findOrFail($id);
		$user = Auth::user();
		
		$this->configTenant($company->id);

		$userExist = DB::connection('tenant')
			->table('company_users')
			->where('user_id', $user->id)
			->exists();

		if (!$userExist) {
			// delete data dari companies_users
			$user->companies()->detach($company->id);

			return redirect()->route('companies.index')->with('error', 'Invitation tidak valid');
		}

		// update data ke companies_users
		$user->companies()->updateExistingPivot($company->id, ['status' => 'rejected']);

		// update data ke company_user
		DB::connection('tenant')
			->table('company_users')
			->where('user_id', $user->id)
			->update([
				'status' => 'rejected',
			]);

        return redirect()->route('companies.index')->with('success', "Invite {$company->name} ditolak successfully.");
    }
}
