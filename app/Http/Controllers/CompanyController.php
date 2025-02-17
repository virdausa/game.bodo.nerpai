<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

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
		$validated = $request->validate([
			'id' => 'required|string|max:255',
			'name' => 'required|string|max:255',
			'address' => 'nullable|string|max:255',
			'database' => 'nullable|string|max:255',
		]);
		
		$company = Company::updateOrCreate($validated);
		$company->id = $request->id;
		
		$this->setupNewCompany($company);

		return redirect()->route('companies.index')->with('success', "Company {$company->name} created successfully.");
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

	public function destroy(string $id)
	{
		$company = Company::findOrFail($id);
		$user = Auth::user();
		
		// config tenant
		$this->configTenant($company->id);

		// is authorized to delete company
		$company_user = DB::connection('tenant')
			->table('company_users')
			->where('user_id', $user->id)
			->first();

        if (!$company_user) {
            // Hapus session company
            return redirect()->route('companies.index')->with('error', 'Anda tidak memiliki akses ke perusahaan ini');
        }

		if($company_user->user_type != 'admin'){
			return redirect()->route('companies.index')->with('error', 'Anda tidak memiliki akses Hapus perusahaan ini');
		}

		// drop database
		$this->dropDatabase($company->id);

		$company->delete();

		return redirect()->route('companies.index')->with('success', "company {$company->name} deleted successfully.");
	}

	public function switchCompany(Request $request, $companyId)
    {
		// forget company before
		$this->forgetCompany();

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
		$this->forgetCompany();

		// Redirect ke halaman lobby (atau dashboard utama)
		return redirect()->route($route)->with('status', 'You have exited the company.');
	}

	public function forgetCompany()
	{
		// to forget from what company had
		foreach(session()->all() as $key => $value) {
			if(str_contains($key, 'company')) {
				session()->forget($key);				
			}
		}

		session()->forget('employee');
		//session()->forget('companyUser');
	}

	public function configTenant($id)
	{
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

	public function dropDatabase($databaseName)
	{
		$db_name = env('DB_DATABASE') . '_' . $databaseName;
		DB::statement("DROP DATABASE IF EXISTS $db_name");

		return $db_name;
	}

	public function createDatabase($databaseName)
	{
		$db_name = env('DB_DATABASE') . '_' . $databaseName;
		DB::statement("CREATE DATABASE IF NOT EXISTS $db_name");

		return $db_name;
	}

	public function migrateDatabase($database, $path)
	{
		Artisan::call('migrate', [
			'--database' => $database,
			'--path' => 'database/migrations/' . $path, // Sesuaikan path migration
			'--force' => true
		]);
	}
	
	public function seedDatabase($database, $seeder_class)
	{
		Artisan::call('db:seed', [
			'--database' => $database,
			'--class' => $seeder_class, // Sesuaikan dengan seeder untuk perusahaan
			'--force' => true
		]);
	}

	public function setupNewCompany(Company $company)
	{
		// create database
		$db_name = $this->createDatabase($company->id, 'tenant');
		
		// config tenant
		$this->configTenant($company->id);

		// migrate database
		$this->migrateDatabase('tenant', 'tenant');

		
		// seed database
		$this->seedDatabase('tenant', 'CompanySeeder');
		
		
		// create new companies_users
		Auth::user()->companies()->attach($company->id, ['status' => 'approved']);

		// create new ke company user
		$companyUserId = DB::connection('tenant')
					->table('company_users')
					->insertGetId([
						'user_id' => Auth::user()->id,
						'user_type' => 'admin',
						'status' => 'approved',
						'created_at' => now(),
						'updated_at' => now(),
					]);

		// create new employee to be owner
		$employeeId = DB::connection('tenant')
					->table('employees')
					->insertGetId([
						'company_user_id' => $companyUserId,
						'reg_date' => now(),
						'status' => 'active',
						'role_id' => 1, 		// assume its owner
						'created_at' => now(),
						'updated_at' => now(),
					]);
	}
}
