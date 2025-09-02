<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{

	public function index()
	{
		// Métricas globales
		$tenantsCount = Tenant::count();

		// Suma de usuarios en todas las BD de tenants
		$usersCount = 0;
		foreach (Tenant::all() as $tenant) {
			try {
				tenancy()->initialize($tenant);
				$usersCount += User::count();
			} finally {
				tenancy()->end();
			}
		}

		return view('central.tenants.dashboard', compact('tenantsCount', 'usersCount'));
	}
	public function create()
	{
		return view('central.tenants.create');
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'id'     => ['required', 'alpha_dash', 'max:32', 'unique:tenants,id'],
			'domain' => ['required', 'alpha_dash', 'max:50'],
		]);

		$tenant = Tenant::create(['id' => $data['id']]);
		$tenant->domains()->create(['domain' => "{$data['domain']}.localhost"]);

		return redirect("http://{$data['domain']}.localhost/")->with('status', 'Tenant creado');
	}
}
