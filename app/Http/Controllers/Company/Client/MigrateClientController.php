<?php

namespace App\Http\Controllers\Company\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

use App\Models\Space\Company;

use App\Models\Company\Customer;

class MigrateClientController extends Controller
{
    public function index()
    {
        $company_id = session('company_id');
        $company = Company::findOrFail($company_id);

        return view('company.client.migrate.index', compact('company'));
    }



    public function migrateCustomer($customers){
        $result = [];

        $customers_data = [];
        foreach($customers as $customer){
            $contact = json_decode($customer->CONTACT, true);

            $address = [];
            if($customer->ADDRESS != '' || $customer->ADDRESS != null){
                $address = json_decode($customer->ADDRESS, true);
                
                if(!is_array($address)){
                    $address = [];
                }
            }

            $customers_data[] = [
                'id' => $customer->CUSTOMER_NO,
                'name' => $customer->CUSTOMER_NAME,
                'address' => json_encode($address),
                'email' => $contact['CONTACT_EMAIL'] ?? null,
                'phone_number' => $contact['CONTACT_PHONE'] ?? null,
            ];
        }

        Customer::upsert($customers_data, 
            ['id'], 
            ['name', 'address', 'email', 'phone_number']
        );

        // testing
        //dd($customers_data);
        // for($i = 0; $i < 2; $i++){
        //     $customers_data += $customers_data;
        // }
        // foreach($customers_data as $customer){
        //     Customer::create($customer);
        // }

        return $result;
    }

    public function configTenant($code)
	{
        $config = config('migrate_client.haebot.' . $code);

		// Atur koneksi database haebot
		Config::set("database.connections.client", [
			'driver'    => 'mysql',
			'host'      => $config['host'],
			'port'      => $config['port'],
			'database'  => $config['db'],
			'username'  => $config['user'],
			'password'  => $config['pass'],
			'charset'   => 'utf8mb4',
			'collation' => 'utf8mb4_unicode_ci',
			'prefix'    => '',
			'strict'    => true,
			'engine'    => null,
		]);
	}



    public function store(Request $request){
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'query' => 'required|string|max:255',
        ]);

        $code = $validated['code'];
        $query = $validated['query'];


        // setup connection to client
        $this->configTenant($code);


        $result = '';
        switch ($query) {
            case 'customer':
                $result = $this->migrateCustomer(DB::connection('client')->table('customers')->get());
                break;
            default: ;
        }

        return view('company.client.migrate.index', compact('result'));
    }
}
