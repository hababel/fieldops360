<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use App\Jobs\UpdateTenantUserCounts;

use Illuminate\Support\Facades\Log;


class TenantController extends Controller
{

        public function __construct()
        {
                $this->middleware(['auth', 'verified']);
        }

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
                        'domain' => ['required', 'max:50', 'regex:/^[a-z0-9-]+$/', 'unique:domains,domain'],
                ]);

                $host = parse_url(config('app.url'), PHP_URL_HOST);

                try {
                        $tenant = Tenant::create(['id' => $data['id']]);
                        $tenant->domains()->create(['domain' => "{$data['domain']}.{$host}"]);
                } catch (\Throwable $e) {
                        if (isset($tenant)) {
                                $tenant->delete();
                        }
                        Log::error('Error al crear el dominio del tenant', ['exception' => $e]);

                        return back()
                                ->withErrors(['domain' => 'No se pudo crear el dominio'])
                                ->withInput();
                }

                $scheme = app()->environment('production') ? 'https' : 'http';

                return redirect()
                        ->to("{$scheme}://{$data['domain']}.{$host}/")
                        ->with('status', 'Tenant creado');
        }
}
