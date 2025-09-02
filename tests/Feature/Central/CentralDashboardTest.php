<?php

use App\Models\User;

it('redirects guests from central dashboard', function () {
    $response = $this->get(route('central.tenants.dashboard'));
    $response->assertRedirect(route('login'));
});

it('allows authenticated users to access central dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('central.tenants.dashboard'));
    $response->assertOk();
});
