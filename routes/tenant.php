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
                $path = basename($path);
                $disk = Storage::disk('local');
                abort_unless($disk->exists($path), 404);
                return $disk->download($path);
        })->where('path', '.*')->middleware('auth');

	Route::get('/dashboard', [DashboardController::class, 'index'])
		->name('dashboard');
});
