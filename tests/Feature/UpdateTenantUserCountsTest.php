<?php

use App\Jobs\UpdateTenantUserCounts;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

it('stores user counts in cache', function () {
    $tenantA = Tenant::create(['id' => 'a']);
    $tenantA->domains()->create(['domain' => 'a.localhost']);
    tenancy()->initialize($tenantA);
    User::factory()->count(2)->create();
    tenancy()->end();

    $tenantB = Tenant::create(['id' => 'b']);
    $tenantB->domains()->create(['domain' => 'b.localhost']);
    tenancy()->initialize($tenantB);
    User::factory()->count(3)->create();
    tenancy()->end();

    Cache::flush();

    (new UpdateTenantUserCounts)->handle();

    expect(Cache::get('tenant:a:users_count'))->toBe(2);
    expect(Cache::get('tenant:b:users_count'))->toBe(3);
    expect(Cache::get('tenants.users.count'))->toBe(5);
});

it('skips tenants that cause errors', function () {
    $good = Tenant::create(['id' => 'good']);
    $good->domains()->create(['domain' => 'good.localhost']);
    tenancy()->initialize($good);
    User::factory()->count(1)->create();
    tenancy()->end();

    $bad = Tenant::create(['id' => 'bad']);
    $bad->domains()->create(['domain' => 'bad.localhost']);
    tenancy()->initialize($bad);
    Schema::drop('users');
    tenancy()->end();

    Cache::flush();

    (new UpdateTenantUserCounts)->handle();

    expect(Cache::get('tenant:good:users_count'))->toBe(1);
    expect(Cache::has('tenant:bad:users_count'))->toBeFalse();
    expect(Cache::get('tenants.users.count'))->toBe(1);
});
