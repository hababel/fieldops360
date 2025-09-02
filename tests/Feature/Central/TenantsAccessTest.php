<?php

use App\Models\User;


test('guests are redirected to the login page for central dashboard', function () {
    $response = $this->get(route('central.tenants.dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the central dashboard', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $this->actingAs($user);

    $response = $this->get(route('central.tenants.dashboard'));
    $response->assertStatus(200);
});

test('guests are redirected to the login page for tenant creation', function () {
    $response = $this->get(route('central.tenants.create'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the tenant creation page', function () {
    $user = User::factory()->create();
    $user->markEmailAsVerified();
    $this->actingAs($user);

    $response = $this->get(route('central.tenants.create'));
    $response->assertStatus(200);
});

