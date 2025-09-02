<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// TODO: Reemplaza cuando exista tu modelo real
// use App\Models\Order;  // p. ej. Ordenes de servicio

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		// Métricas base
		$stats = [
			'tenant'        => tenant('id'),
			'users_count'   => User::count(),
			// 'orders_count'  => Order::count(),                     // <-- cuando exista
			// 'open_orders'   => Order::where('status','open')->count(),
			// 'closed_orders' => Order::where('status','closed')->count(),
		];

		// Últimos registros (ejemplo con usuarios)
		$latestUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);

		// Tabla demo (placeholder) hasta conectar tu modelo real
		$latestOrders = collect([
			// Cuando tengas modelo real, reemplaza por:
			// Order::latest()->take(5)->get(['id','title','status','created_at'])
		]);

		return view('tenant.dashboard', compact('stats', 'latestUsers', 'latestOrders'));
	}
}
