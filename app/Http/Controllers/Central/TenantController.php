<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Jobs\UpdateTenantUserCounts;

class TenantController extends Controller
{

	public function index()
	{
		// Métricas globales
		$tenantsCount = Tenant::count();

                // Intentar obtener el total de usuarios desde caché para evitar
                // iterar todas las bases de datos de los tenants en cada solicitud
                $usersCount = Cache::get('tenants.users.count');

                // Si el valor no existe, despachar un job que actualice el cache
                // de forma asíncrona. Se devuelve 0 mientras se recalculan los datos.
                if ($usersCount === null) {
                        UpdateTenantUserCounts::dispatch();
                        $usersCount = 0;
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
