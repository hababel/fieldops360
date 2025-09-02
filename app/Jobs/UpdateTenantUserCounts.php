<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class UpdateTenantUserCounts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Recalcula el número de usuarios por tenant y el total global.
     *
     * El resultado se almacena en el caché para evitar contar usuarios
     * en cada solicitud al dashboard central.
     */
    public function handle(): void
    {
        $total = 0;

        foreach (Tenant::all() as $tenant) {
            try {
                tenancy()->initialize($tenant);
                $count = User::count();
            } finally {
                tenancy()->end();
            }

            Cache::put("tenant:{$tenant->id}:users_count", $count, now()->addMinutes(10));
            $total += $count;
        }

        Cache::put('tenants.users.count', $total, now()->addMinutes(10));
    }
}
