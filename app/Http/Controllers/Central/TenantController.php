<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
