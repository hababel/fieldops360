<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('creates tenants with domain and database', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('central.tenants.store'), [
        'id' => 'acme',
        'domain' => 'acme',
    ]);

    $response->assertRedirect('http://acme.localhost/');

    $tenant = Tenant::firstWhere('id', 'acme');
    expect($tenant)->not->toBeNull();
    expect($tenant->domains()->where('domain', 'acme.localhost')->exists())->toBeTrue();

    tenancy()->initialize($tenant);
    expect(DB::connection('tenant')->getDatabaseName())->toContain('tenantacme');
    expect(Schema::hasTable('users'))->toBeTrue();
    tenancy()->end();
});
