<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TenantAdminSeeder extends Seeder
{
	public function run(): void
	{
		User::firstOrCreate(
			['email' => 'admin@tenant.test'],
			['name' => 'Tenant Admin', 'password' => Hash::make('password')]
		);
	}
}
