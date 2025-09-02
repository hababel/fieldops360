<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Central\TenantController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

foreach (config('tenancy.central_domains') as $domain) {
        Route::domain($domain)

        ->middleware(['web', 'auth', 'verified', 'super-admin'])

        ->group(function () {

		Route::redirect('/', '/admin');		

		Route::get('/admin', [TenantController::class, 'index'])
			->name('central.tenants.dashboard');

		Route::get('/tenants/create', [TenantController::class, 'create'])
			->name('central.tenants.create');

		Route::post('/tenants', [TenantController::class, 'store'])
			->name('central.tenants.store');
	});
}




require __DIR__.'/auth.php';
