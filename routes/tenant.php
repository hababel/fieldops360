<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Tenant\DashboardController;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
	Route::get('/', fn() => view('welcome'));

	// Rutas de Breeze
	require __DIR__ . '/auth.php';

	// Debug
	Route::get('/debug', function () {
		dump(DB::connection()->getDatabaseName());
		return 'Tenant: ' . tenant('id');
	});

	Route::get('/files/{path}', function ($path) {
		$full = Storage::disk('local')->path($path); // ← usa el disk tenant-aware
		abort_unless(file_exists($full), 404);
		return response()->file($full);
	})->where('path', '.*');

	Route::get('/dashboard', [DashboardController::class, 'index'])
		->name('dashboard');
});

Route::middleware(['web', InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class])
	->group(function () {
		Route::get('/debug', function () {
			dump(DB::connection()->getDatabaseName());
			return 'Tenant: ' . tenant('id');
		});
	});

